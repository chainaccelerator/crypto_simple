# crypto_simple

A trait for crypt and uncrypt very easly with ou without pgp and a rsa implementation + cert management and compress data

```
<?

class YourClass {

  use Crypto_simple;
}
```

# Just one time (isntall)
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
# After

A want to send a crypted message to B, only B can read

## A side
```
$text4B = 'A text for B';
$instanceA = new YourClass();
$cypher = $instanceA->crypt($text4B);
echo $cypher;
```
## B side
```
$instanceB = new YourClass();
$text4B = $instanceA->uncrypt($cypher);
echo $text4B;
```

## Welcome to enriching this work base with you!
