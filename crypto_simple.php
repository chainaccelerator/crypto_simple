<?

Trait Crypto_simple {

  use Compress_simple, Ppg_simple, Cert_simple;

  private static $crypt_pgp_state = true;

  private function crypto_init(string $countryName,
                          string $stateOrProvinceName,
                          string $localityName,
                          string $organizationName,
                          string $organizationalUnitName,
                          string $commonName,
                          string $emailAddress,
                          string $password){

      self::rsa_init();
      self::cert_init($countryName,
          $stateOrProvinceName,
          $localityName, $organizationName,
          $organizationalUnitName,
          $commonName,
          $emailAddress,
          $password);

      return true;
  }

  public function crypt(string $msg) {

    // process
    $msg = self::compress($msg);

    if(self::$crypt_pgp_state === true) $cypher = self::pgp_crypt($msg);
    else                          $cypher = self::rsa_crypt($msg);

    return $cypher;
  }

  public function uncrypt(string $cypher) {

    if(self::$crypt_pgp_state === true) $msg = self::pgp_uncrypt($cypher);
    else                          $msg = self::rsa_uncrypt($cypher);

    $msg = self::uncompress($msg);

    return $msg;
  }
}
