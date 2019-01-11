<?

trait Pgp_simple {

    use Rsa_simple;

    private static $pgp_env = '../data/pgp/.gnupg';
    private static $pgp_separator = '____PGP_CUSTOM_SEP____';
    private static $pgp_resource;
    private static $pgp_passphrase_file = '../data/pgp/passphrase.pgp';
    private static $pgp_passphrase;

    private static function pgp_init(string $pgp_passphrase){

        putenv('GNUPGHOME='.self::$pgp_env);

        self::$pgp_resource = gnupg_init();
        file_put_contents(self::$pgp_passphrase_file, $pgp_passphrase);

        return true;
    }

    private static function pgp_passphrase_get() {

        return file_get_contents(self::$pgp_passphrase_file);
    }

    public function pgp_crypt(string $msg, string $rsa_public_key)
    {
        $session_key = hash(self::$rsa_digest_alg, self::rsa_public_key_get() . time() . uniqid());

        $pgp_passphrase = self::pgp_passphrase_get();

        gnupg_addencryptkey(self::$pgp_resource, $session_key, $pgp_passphrase);

        $msg_crypted = gnupg_encrypt(self::$pgp_resource, $msg);

        $session_key_crypted = self::rsa_crypt($session_key, $rsa_public_key);

        $cypher = $msg_crypted.self::$pgp_separator.$session_key_crypted;

        return $cypher;
    }

    public function pgp_uncrypt(string $cypher, string $pgp_passphrase) {

        $cypher_parts = explode(self::$pgp_separator, $cypher);
        $msg_crypted = $cypher_parts[0];
        $session_key_crypted = $cypher_parts[1];
        $session_key = self::rsa_uncrypt($session_key_crypted);
        $plaintext = "";

        gnupg_adddecryptkey(self::$pgp_resource, $session_key, $pgp_passphrase);

        gnupg_decryptverify(self::$pgp_resource, $cypher, $plaintext);

        return $plaintext;
    }

}
