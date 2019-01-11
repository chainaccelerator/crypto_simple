# crypto_simple

A trait for crypt and uncrypt very easly 
with ou without pgp and a rsa implementation + cert management + compress data.
Without framework ou externals projects.
FOR LEARNING AND PLAY WITH CRYPTO

## Integration

You need PHP PHP 7.3.1+  (no need any webserver) compiled with :
* openssl extension and
* gnupg extension.

shell> cd install
shell> php install.php --crypt_pgp_state 0 \ \
--countryName "YourCountry" \ \
--stateOrProvinceName "YourState" \ \
--localityName "YourLocality" \ \
--organizationName "YourOrganization" \ \
--organizationalUnitName "YourUnit" \ \
--commonName "YourCommonName" \ \
--emailAddress "YourEmail" \ \
--password: "YourPwd" \ \
--pgp_passphrase "YourPhrase"

### In your Code :
```
\#! /usr/local/bin/php
<?php

require_once('../src/compress_simple.php');
require_once('../src/rsa_simple.php');
require_once('../src/cert_simple.php');
require_once('../src/pgp_simple.php');
require_once('../src/crypto_simple.php');

class YourClass {

    use Crypto_simple;
}
```
## Test
A want to send a crypted message to B, only B can read
```
$public_key_B = '5E2EF8BA46CEED1249FA580C48881E262C4987ECC53F729F7E2625B10EF9AB9A';
```

### With PGP
Test: test/with_php.php

#### A side
```
$text4B = 'A text for B';

Test::$crypt_pgp_state = true;
$instanceA = new Test();
$cypher = $instanceA->crypt($text4B, $public_key_B);
echo $cypher;
```
#### B side
```
Test::$crypt_pgp_state = true;
$instanceB = new Test();
$text4B = $instanceA->uncrypt($cypher);
echo $text4B;
```

### Without PGP
Test: test/without_php.php

#### A side
```
$text4B = 'A text for B';

Test::$crypt_pgp_state = false;
$instanceA = new Test();
$cypher = $instanceA->crypt($text4B, $public_key_B);
echo $cypher;
```
#### B side
```
Test::$crypt_pgp_state = false;
$instanceB = new Test();
$text4B = $instanceA->uncrypt($cypher);
echo $text4B;
```


# Welcome to enriching this work base with you!
