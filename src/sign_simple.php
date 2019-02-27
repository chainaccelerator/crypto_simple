<?php

Trait Sign_simple
{

    private static $sign_private_key_bits = 2048;
    private static $sign_private_key_type = OPENSSL_KEYTYPE_RSA;
    private static $sign_algo = OPENSSL_ALGO_SHA1;
    private static $sign_public_key_file = '../data/key/mine/private.pem';
    private static $sign_private_key_file = '../data/key/mine/public.pem';

    public $sign_public_key;

    public function sign_init()
    {

        $private_key_res = openssl_pkey_new(array(
            "private_key_bits" => self::$sign_private_key_bits,
            "private_key_type" => self::$sign_private_key_type,
        ));

        file_put_contents(self::$sign_private_key_file, $private_key_res);

        $details = openssl_pkey_get_details($private_key_res);
        $this->sign_public_key = openssl_pkey_get_public($details['key']);

        return true;
    }

    public function sign(string $data)
    {

        $private_key_res = $this->sign_private_key_get();
        $signature = openssl_sign($data, $signature, $private_key_res, self::$sign_algo);

        return $signature;
    }

    public static function sign_private_key_get()
    {

        return file_get_contents(self::$sign_private_key_file);
    }

    public function sign_verify(string $data, string $signature, $public_key_res = false)
    {

        if ($public_key_res === false) {

            $public_key_res = $this->sign_public_key_get();
        }
        $res = openssl_verify($data, $signature, $public_key_res, self::$sign_algo);

        if ($res == 1) return true;

        return false;
    }

    public static function sign_public_key_get()
    {

        return file_get_contents(self::$sign_public_key_file);
    }
}