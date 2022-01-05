<?php namespace Rebill\SDK;

/**
*  Main class
*
*  @author Kijam
*/
class Rebill extends RebillModel
{
    /**
     * API endpoint Sandbox
     *
     * @var string
     */
    const API_SANDBOX = 'https://api-staging.rebill.to/v1';

    /**
     * API endpoint Production
     *
     * @var string
     */
    const API_PROD = 'https://api.rebill.to/v1';
    
    /**
     * Is debug mode
     *
     * @var bool
     */
    protected $isDebug = false;
    
    /**
     * Is sandbox mode
     *
     * @var bool
     */
    protected $sandbox;

    /**
     * Organization UUID
     *
     * @var string
     */
    protected $organization;

    /**
     * Username
     *
     * @var string
     */
    protected $user;

    /**
     * Password
     *
     * @var string
     */
    protected $pass;

    /**
     * Singleton pattern
     */
    private function __construct()
    {
        // dummy...
    }

    /**
     * Return unique instance
     *
     * @return Rebill
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance) {
            return $instance;
        }
        $instance = new self;
        return $instance;
    }
    
    /**
     * Return unique instance
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->sandbox ? self::API_SANDBOX : self::API_PROD;
    }
    
    /**
     * Call method of API
     *
     * @param   string            $method Methood.
     * @param   array             $headers Headers request.
     * @param   bool|string|array $post_data Post DATA.
     * @param   string            $http_method HTTP Method (GET/POST/PUT/DELETE).
     * @param   bool|array        $args URL Params.
     * @param   bool              $to_json Send post data in json encode.
     * @param   bool              $return_decode Return json data decode.
     * @param   bool|string|array $error_data Return error details.
     *
     * @return  bool|array
     */
    private function call(
        $method,
        $headers = [],
        $post_data = false,
        $http_method = 'GET',
        $args = false,
        $to_json = true,
        $return_decode = true,
        &$error_data = null
    ) {
        static $is_retry = false;
        $base_url = $this->getUrl();
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $wp_data         = [
            'method'  => $http_method,
        ];
        $url             = $base_url.$method;
        self::log('call '.$http_method.' ['.$url.'] -> '.\var_export($headers, true).' - '.\var_export($args, true).'
                    - '.\var_export($post_data, true));
        if (\is_array($args)) {
            $args = \http_build_query($args);
            $url .= '?'.$args;
        } elseif (\is_string($args) && !empty($args)) {
            $url .= '?'.$args;
        }
        if ($post_data) {
            \curl_setopt($ch, CURLOPT_POST, 1);
            if ($to_json) {
                $post_data = \json_encode($post_data);
                $headers   = array_merge(
                    $headers,
                    [
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length: '.strlen($post_data),
                    ]
                );
            } else {
                $headers = array_merge(
                    $headers,
                    [
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length: '.strlen($post_data),
                    ]
                );
            }
            self::log('DATA Send: '.$post_data);
            \curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = \curl_exec($ch);
        $info = \curl_getinfo($ch);
        if ($info["http_code"] >= 200 && $info["http_code"] < 300) {
            self::log('Result for '.$http_method.' ['.$url.']: '.\var_export($response, true));
            if ($return_decode) {
                return [
                    'data'    => \json_decode($response, true),
                ];
            }
            return [
                'data'    => $response,
            ];
        } else {
            self::log('Error '.$info["http_code"].' ['.$url.']: '.\var_export($response, true));
        }
        if (!$is_retry &&
            (\stristr($response, 'Invalid or expired') !== false || \stristr($response, 'Unauthorized') !== false)) {
            $is_retry = true;
            $token    = $this->getToken(true);
            if ($token) {
                $found = false;
                foreach ($headers as &$h) {
                    if (\stristr($h, 'Authorization:') !== false) {
                        $h     = 'Authorization: Bearer '.$token;
                        $found = true;
                        break;
                    }
                }
                if (! $found) {
                    $headers[] = 'Authorization: Bearer '.$token;
                }
                self::log('Error ['.$url.'] -> retry with new token...');
                $result = $this->call(
                    $method,
                    $headers,
                    $post_data,
                    $http_method,
                    $args,
                    $to_json,
                    $return_decode,
                    $error_data
                );
                $is_retry = false;
                return $result;
            }
            $is_retry = false;
        }
        if (\count(\func_get_args()) >= 8 && !empty($rsp)) {
            if ($return_decode) {
                $error_data = \json_decode($rsp, true);
            } else {
                $error_data = $rsp;
            }
        }
        return false;
    }

    /**
     * Get API Token
     *
     * @param   bool        $forece_reload Force Reload.
     *
     * @return  bool|string
     */
    public function getToken($forece_reload = false)
    {
        $key = \base64_encode($this->user.':'.$this->pass);
        $cache_file = \dirname(__FILE__).'/cache/token_'.\md5($key.($this->sandbox ? '1' : '0').'.php');
        if (!$forece_reload && \file_exists($cache_file)) {
            $result = \file_get_contents($cache_file);
            if ($result) {
                $data = \json_decode(\str_replace('<?php exit; ?>', '', $result), true);
                if (\time() < $data['expire']) {
                    return 'error' === $data['result'] ? false : $data['result'];
                }
            }
        }
        if (\file_exists($cache_file)) {
            \unlink($cache_file);
        }
        $result = $this->call('/getToken', [ 'Authorization: Basic '.$key ]);
        if ($result &&
                isset($result['data']['response']) &&
                isset($result['data']['response']['token']) &&
                !empty($result['data']['response']['token'])) {
            $current = \strtotime($result['data']['response']['currentTime']);
            $expire  = \strtotime($result['data']['response']['expires']);
            \file_put_contents($cache_file, '<?php exit; ?>'.\json_encode([
                'result' => $result['data']['response']['token'],
                'expire' => \time() + ($expire - $current)
            ]));
            return $result['data']['response']['token'];
        }
        \file_put_contents($cache_file, '<?php exit; ?>'.\json_encode([
            'result' => 'error',
            'expire' => \time() + 600
        ]));
        return false;
    }

    /**
     * Call GET method of API
     *
     * @param   string            $method Method.
     * @param   bool|array        $args URL Params.
     * @param   int               $ttl TTL.
     * @param   array             $headers Headers request.
     * @param   bool|string|array $error_data Return error details.
     *
     * @return  bool|array
     */
    public function callApiGet($method, $args = false, $ttl = 3600, $headers = [], &$error_data = null)
    {
        $token    = $this->getToken();
        $headers_request = \array_merge($headers, []);
        if ($token) {
            $headers_request[] = 'Authorization: Bearer '.$token;
        }
        $result = $this->call($method, $headers_request, false, 'GET', $args, true, true, $error_data);
        if ($result) {
            return $result['data'];
        }
        return false;
    }

    /**
     * Call DELETE method of API
     *
     * @param   string            $method Method.
     * @param   bool|array        $args URL Params.
     * @param   array             $headers Headers request.
     * @param   bool|string|array $error_data Return error details.
     *
     * @return  bool|array
     */
    public function callApiDelete($method, $args = false, $headers = [], &$error_data = null)
    {
        $token           = $this->getToken();
        $headers_request = \array_merge($headers, []);
        if ($token) {
            $headers_request[] = 'Authorization: Bearer '.$token;
        }
        $result = $this->call($method, $headers_request, false, 'DELETE', $args, true, true, $error_data);
        if ($result) {
            return $result['data'];
        }
        return false;
    }

    /**
     * Call POST method of API
     *
     * @param   string            $method Method.
     * @param   array             $post_data URL Params.
     * @param   bool|array        $args URL Params.
     * @param   array             $headers Headers request.
     * @param   bool|string|array $error_data Return error details.
     *
     * @return  bool|array
     */
    public function callApiPost($method, $post_data, $args = false, $headers = [], &$error_data = null)
    {
        $token = $this->getToken();
        $headers_request = \array_merge($headers, []);
        if ($token) {
            $headers_request[] = 'Authorization: Bearer '.$token;
        }
        $result = $this->call($method, $headers_request, $post_data, 'POST', $args, true, true, $error_data);
        if ($result) {
            return $result['data'];
        }
        return false;
    }

    /**
     * Call POST method of API
     *
     * @param   string            $method Method.
     * @param   array             $post_data URL Params.
     * @param   bool|array        $args URL Params.
     * @param   array             $headers Headers request.
     * @param   bool|string|array $error_data Return error details.
     *
     * @return  bool|array
     */
    public function callApiPut($method, $post_data, $args = false, $headers = [], &$error_data = null)
    {
        $token           = $this->getToken();
        $headers_request = \array_merge($headers, []);
        if ($token) {
            $headers_request[] = 'Authorization: Bearer '.$token;
        }
        $result = $this->call($method, $headers_request, $post_data, 'PUT', $args, true, true, $error_data);
        if ($result) {
            return $result['data'];
        }
        return false;
    }
    private static function log($msg)
    {
        if (self::getInstance()->isDebug) {
            \error_log($msg);
        }
    }
}
