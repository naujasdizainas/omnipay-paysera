<?php

namespace Omnipay\Paysera;

use Omnipay\Common\Exception\InvalidRequestException;


class Helper
{

    private static $pub_key = 'http://www.paysera.com/download/public.key';


    public static function createParam($param, $length = 255, $required = true)
    {
        return new Param($param, $length, $required);
    }

    public static function validateData($params)
    {
        foreach ($params as $key => $param) {
            if (!$param->isValid()) {
                throw new InvalidRequestException("The $key parameter is invalid");
            }
        }
    }

    public static function prepareData($params)
    {
        $data = array();
        foreach ($params as $key => $param) {
            $value = $param->getValue();
            if ($param->isRequired() || ($value !== '' && $value !== null)) {
                $data[$key] = $value;
            }
        }
        return self::encodeSafeUrlBase64(http_build_query($data, '', '&'));
    }

    public static function parseData($data)
    {
        $params = array();
        parse_str(self::decodeSafeUrlBase64($data), $params);
        return $params;
    }

    public static function signData($data, $sign_password)
    {
        return md5($data . $sign_password);
    }

    public static function verifySignData($data, $sign_password, $httpClient)
    {
        return self::verifySignDataSS2($data, $httpClient) || self::verifySignDataSS1($data, $sign_password);
    }

    private static function verifySignDataSS1($data, $sign_password)
    {
        return md5($data['data'] . $sign_password) === $data['ss1'];
    }

    private static function verifySignDataSS2($data, $httpClient)
    {
        $httpResponse = $httpClient->get(self::$pub_key)->send();
        if ($httpResponse->getStatusCode() == 200) {
            if ($pub_key_id = openssl_get_publickey($httpResponse->getBody())) {
                return openssl_verify($data['data'], self::decodeSafeUrlBase64($data['ss2']), $pub_key_id) === 1;
            }
        }
        return false;
    }

    private static function encodeSafeUrlBase64($str) {
        return strtr(base64_encode($str), array('/' => '_', '+' => '-'));
    }

    private static function decodeSafeUrlBase64($str) {
        return base64_decode(strtr($str, array('-' => '+', '_' => '/')));
    }

    public static function checkDataParam($resp, $req, $param) {
        if (!isset($resp[$param]) && !isset($req[$param])) {
            return true;
        }
        if (isset($resp[$param]) && isset($req[$param])) {
            return $resp[$param] === $req[$param]->getValue();
        }
        return false;
    }
}
