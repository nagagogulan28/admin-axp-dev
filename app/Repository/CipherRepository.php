<?php

namespace App\Repository;
use Illuminate\Support\Facades\Config;

class CipherRepository
{
    private $secretKey;
    private $iv;

    public function __construct()
    {
        $this->secretKey = hex2bin(config('encryption.secret_key'));
        $this->iv = hex2bin(config('encryption.iv'));
    }

    public function encrypt($data)
    {
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->secretKey, 0, $this->iv);
        return base64_encode($encrypted);
    }

    public function decrypt($data)
    {
        $decodedData = base64_decode($data);
        return openssl_decrypt($decodedData, 'AES-256-CBC', $this->secretKey, 0, $this->iv);
    }

    public static $payLoadiv = "01581a31d2a34e70";
    public static $payLoadKey = "0a1d0c01a2e013c4de2c0gdtwd001921wmaqporueez8g50zknuxeh0s09k0c1du";
    public static $payLoadMethod = "AES-256-CBC";

    public function decryptPayload($data)
    {
        // $method = Config::get('app.decrypt_algorithm');

        // $key = Config::get('app.decrypt_key');

        $options = 0;

        // $iv = Config::get('app.decrypt_iv');

        $iv = self::$payLoadiv;
        $key = self::$payLoadKey;
        $method = self::$payLoadMethod;

        return openssl_decrypt($data, $method, $key, 0, $iv);
    }

}
