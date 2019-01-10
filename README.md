# crypto_simple

A trait for crypt and uncrypt very easly 
with ou without pgp and a rsa implementation + cert management + compress data.
Without framework ou externals projects.

## Integration
```
require_once('compress_simple.php');
require_once('rsa_simple.php');
require_once('cert_simple.php');
require_once('pgp_simple.php');
require_once('crypto_simple.php');

class YourClass {

  use Crypto_simple; // <- here :)
}
```

## Just one time (install)
in YourClass, code call :
```
self::crypto_init(
                    $crypt_pgp_state,
                    $countryName,
                    $stateOrProvinceName,
                    $localityName,
                    $organizationName,
                    $organizationalUnitName,
                    $commonName,
                    $emailAddress,
                    $password,
                    $pgp_passphrase); 
```
## After

A want to send a crypted message to B, only B can read

### A side
```
$text4B = 'A text for B';
$instanceA = new YourClass();
$cypher = $instanceA->crypt($text4B);
echo $cypher;
```
### B side
```
$instanceB = new YourClass();
$text4B = $instanceA->uncrypt($cypher);
echo $text4B;
```

# Welcome to enriching this work base with you!
