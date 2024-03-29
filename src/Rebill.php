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
    const API_SANDBOX = 'https://api.rebill.dev/v2';

    /**
     * API endpoint Production
     *
     * @var string
     */
    const API_PROD = 'https://api.rebill.to/v2';
    
    /**
     * Callback debug
     *
     * @var mixed
     */
    private static $callback_debug = null;
    
    /**
     * Is debug mode
     *
     * @var bool
     */
    public $isDebug = false;
    
    /**
     * Is sandbox mode
     *
     * @var bool
     */
    protected $sandbox;

    /**
     * Organization Alias
     *
     * @var string
     */
    protected $orgAlias;

    /**
     * Default Organization ID - Optional
     *
     * @var string
     */
    protected $orgId;

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
     * API-Key
     *
     * @var string
     */
    protected $access_token = false;

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
        $time_init = time();
        $base_url = $this->getUrl();
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        $url             = $base_url.$method;
        if (\is_array($args)) {
            $args = \http_build_query($args);
            $url .= '?'.$args;
        } elseif (\is_string($args) && !empty($args)) {
            $url .= '?'.$args;
        }
        if (isset($this->orgId) && !empty($this->orgId)) {
            $headers   = \array_merge(
                $headers,
                [
                    'organization_id: '.$this->orgId,
                ]
            );
        }
        if ($post_data) {
            \curl_setopt($ch, CURLOPT_POST, 1);
            if ($to_json) {
                $post_data = \json_encode($post_data);
                $headers   = \array_merge(
                    $headers,
                    [
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length: '.strlen($post_data),
                    ]
                );
            } else {
                $headers = \array_merge(
                    $headers,
                    [
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length: '.strlen($post_data),
                    ]
                );
            }
            self::log('call '.$http_method.' ['.$url.'] -> '.\var_export($headers, true).' - '.\var_export($args, true).'
                        - '.\var_export($post_data, true));
            self::log('DATA Send: '.$post_data);
            \curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        } else {
            self::log('call '.$http_method.' ['.$url.'] -> '.\var_export($headers, true).' - '.\var_export($args, true).'
                        - '.\var_export($post_data, true));
        }
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = \curl_exec($ch);
        $total_time = time() - $time_init;
        $info = \curl_getinfo($ch);
        if ($info["http_code"] >= 200 && $info["http_code"] < 300) {
            self::log('Result for '.$http_method.' ['.$url.']: '.\var_export($response, true));
            if (empty($response)) {
                return [
                    'data'    => true,
                ];
            }
            if ($return_decode) {
                return [
                    'data'    => \json_decode($response, true),
                ];
            }
            return [
                'data'    => $response,
            ];
        } else {
            $errno = \curl_errno($ch);
            $curl_error = $errno?('Curl['.$errno. ']-> '.\curl_error($ch)):'';
            $log_data = $post_data?"\n\nData send: $post_data":'';
            self::log('Error '.$info["http_code"].' ['.$http_method.' '.$url.']'.($errno?' - '.$curl_error:'').': '.\var_export($response, true).$log_data);
        }
        if ($total_time > 8) {
            self::log('Time response is very high: '.$total_time.'seg');
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
        if (\count(\func_get_args()) >= 8 && !empty($response)) {
            if ($return_decode) {
                $error_data = \json_decode($response, true);
            } else {
                $error_data = $response;
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
        if ($this->access_token) {
            return $this->access_token;
        }
        if (!$this->orgAlias || !$this->user || !$this->pass) {
            return false;
        }
        $key = $this->orgAlias.':'.$this->user.':'.$this->pass;
        $cache_file = \dirname(__FILE__).'/cache/token_'.\md5($key.($this->sandbox ? '1' : '0')).'.php';
        if (!$forece_reload && \file_exists($cache_file)) {
            $result = \file_get_contents($cache_file);
            if ($result) {
                $data = \json_decode(\str_replace('<?php exit; ?>', '', $result), true);
                if (\time() < $data['expire']) {
                    return $this->access_token = ('error' === $data['result'] ? false : $data['result']);
                }
            }
        }
        if (\file_exists($cache_file)) {
            \unlink($cache_file);
        }
        $result = $this->call('/auth/login/'.$this->orgAlias, [], (new \Rebill\SDK\Models\Shared\User)->setAttributes([
            'email' => $this->user,
            'password' => $this->pass
        ])->validate()->toArray(), 'POST');
        if ($result &&
                isset($result['data']['authToken']) &&
                !empty($result['data']['authToken'])) {
            $this->access_token = $result['data']['authToken'];
            \file_put_contents($cache_file, '<?php exit; ?>'.\json_encode([
                'result' => $this->access_token,
                'expire' => \time() + (24 * 3600 - 120)
            ]));
            return $this->access_token;
        }
        \file_put_contents($cache_file, '<?php exit; ?>'.\json_encode([
            'result' => 'error',
            'expire' => \time() + 60
        ]));
        return false;
    }

    /**
     * Call GET method of API
     *
     * @param   string            $method Method.
     * @param   bool|array        $args URL Params.
     * @param   array             $headers Headers request.
     * @param   bool|string|array $error_data Return error details.
     *
     * @return  bool|array
     */
    public function callApiGet($method, $args = false, $headers = [], &$error_data = null, $is_guest = false)
    {
        $headers_request = \array_merge($headers, []);
        if (!$is_guest) {
            $token    = $this->getToken();
            if ($token) {
                $headers_request[] = 'Authorization: Bearer '.$token;
            }
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
    public function callApiDelete($method, $args = false, $headers = [], &$error_data = null, $is_guest = false)
    {
        $headers_request = \array_merge($headers, []);
        if (!$is_guest) {
            $token    = $this->getToken();
            if ($token) {
                $headers_request[] = 'Authorization: Bearer '.$token;
            }
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
    public function callApiPost($method, $post_data, $args = false, $headers = [], &$error_data = null, $is_guest = false)
    {
        $headers_request = \array_merge($headers, []);
        if (!$is_guest) {
            $token    = $this->getToken();
            if ($token) {
                $headers_request[] = 'Authorization: Bearer '.$token;
            }
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
    public function callApiPut($method, $post_data, $args = false, $headers = [], &$error_data = null, $is_guest = false)
    {
        $headers_request = \array_merge($headers, []);
        if (!$is_guest) {
            $token    = $this->getToken();
            if ($token) {
                $headers_request[] = 'Authorization: Bearer '.$token;
            }
        }
        $result = $this->call($method, $headers_request, $post_data, 'PUT', $args, true, true, $error_data);
        if ($result) {
            return $result['data'];
        }
        return false;
    }
    public static function setCallBackDebugLog($callback)
    {
        if (\is_callable($callback)) {
            self::$callback_debug = $callback;
        } else {
            throw new \Exception("Callback ".var_export($callback, true)." not is callable.");
        }
    }
    public static function log($msg, $level = 'info')
    {
        if (self::getInstance()->isDebug) {
            if (\is_callable(self::$callback_debug)) {
                $callback = self::$callback_debug;
                $callback($msg, $level);
            } elseif ($level != 'info') {
                \error_log($level.': '.$msg);
            }
        }
    }
}
