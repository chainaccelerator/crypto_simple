<?

trait Pgp_simple {

    use Rsa_simple;

    private static $pgp_env = '/var/www/example.com/.gnupg';
    private static $pgp_separator = '____PGP_CUSTOM_SEP____';
    private static $pgp_resource;
    private static $pgp_passphrase_file = '../data/pgp/passphrase.pgp';
    private static $pgp_passphrase;

    private static function php_init(){

        putenv('GNUPGHOME='.self::$pgp_env);

        self::$pgp_resource = gnupg_init();
        self::$pgp_passphrase = file_get_contents(self::$pgp_passphrase_file);

        return true;
    }

    private function pgp_crypt(string $msg)
    {
        $session_key = hash(self::$rsa_digest_alg, self::rsa_public_key_get() . time() . uniqid());

        gnupg_addencryptkey(self::$pgp_resource, $session_key, self::$pgp_passphrase);

        $msg_crypted = gnupg_encrypt(self::$pgp_resource, $msg);

        $session_key_crypted = self::rsa_crypt($session_key);

        $cypher = $msg_crypted.self::$pgp_separator.$session_key_crypted;

        return $cypher;
    }

    public function pgp_uncrypt(string $cypher) {

        $cypher_parts = explode(self::$pgp_separator, $cypher);
        $msg_crypted = $cypher_parts[0];
        $session_key_crypted = $cypher_parts[1];
        $session_key = self::rsa_uncrypt($session_key_crypted);
        $plaintext = "";

        gnupg_adddecryptkey(self::$pgp_resource, $session_key, self::$pgp_passphrase);

        gnupg_decryptverify(self::$pgp_resource, $cypher, $plaintext);

        return $plaintext;
    }

}
