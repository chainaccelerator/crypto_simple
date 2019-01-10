<?

trait Cert_simple {

    use Rsa_simple;

    private static $cert_csr_file = '../data/cert/mine/src.pem';
    private static $cert_x509_file = '../data/cert/mine/x509.pem';
    private static $cert_pkey_file = '../data/cert/mine/private_pwd.pem';
    private static $cert_password;
    private static $cert_user_data;
    public $cert_client;
    public $cert_client_signed;

    public function cert_client_set(string $cert_client){

        $this->cert_client = $cert_client;
    }

    public function cert_user_data_set(

        return true;
    }

    private static function cert_init(string $countryName,
                                     string $stateOrProvinceName,
                                     string $localityName,
                                     string $organizationName,
                                     string $organizationalUnitName,
                                     string $commonName,
                                     string $emailAddress, string $password) {

        self::$cert_user_data = array(
        'countryName' => $countryName,
        'stateOrProvinceName' => $stateOrProvinceName,
        'localityName' => $localityName,
        'organizationName' => $organizationName,
        'organizationalUnitName' => $organizationalUnitName,
        'commonName' => $commonName,
        'emailAddress' => $emailAddress);

        self::$cert_password = $password;

        $privkey = self::rsa_private_key_get();
        $csr = openssl_csr_new(self::$cert_user_data, $privkey);
        $sscert = openssl_csr_sign($csr, null, $privkey, self::$rsa_key_days);

        openssl_csr_export($csr, $csrout);

        file_put_contents($csrout, self::$cert_csr_file);

        openssl_x509_export($sscert, $certout);

        file_put_contents($certout, self::$cert_x509_file);

        openssl_pkey_export($privkey, $pkeyout, self::$cert_password);

        file_put_contents($pkeyout, self::$cert_pkey_file);

        return true;
    }

    public function cert_client_sign(){

        $usercert = openssl_csr_sign($this->cert_client, self::cert_x509_get(), self::rsa_private_key_get(), self::$rsa_key_days);

        openssl_x509_export($usercert, $csrout);

        $this->cert_client_signed = $csrout;

        return true;
    }
}
