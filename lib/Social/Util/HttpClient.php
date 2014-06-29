<?php

namespace Social\Util;


class HttpClient
{
    public static $httpCode = -1;
    public static $httpInfo = array();

    public static function exec($method, $url, $data = null)
    {
        $method = strtoupper($method);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'php-social');

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'GET':
            case 'DELETE':
                if (is_array($data) && count($data)) {
                    $data = http_build_query($data);
                }

                if ($data) {
                    $url .= '?' . $data;
                }

                if ($method == 'DELETE') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                }

                break;
            default:
                throw new \LogicException(sprintf('Http method "%s" not implement!', $method));
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        $buffer = curl_exec($ch);

        self::$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        self::$httpInfo = curl_getinfo($ch);

        if ($buffer === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return json_encode(array('error' => sprintf('Curl error "%s"', $error)));
        }

        curl_close($ch);

        return $buffer;
    }

} 