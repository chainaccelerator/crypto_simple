#! /usr/local/bin/php
<?php

require_once('../src/compress_simple.php');
require_once('../src/rsa_simple.php');
require_once('../src/cert_simple.php');
require_once('../src/pgp_simple.php');
require_once('../src/crypto_simple.php');

class Test {

    use Crypto_simple;
}

// Public
$public_key_B = '5E2EF8BA46CEED1249FA580C48881E262C4987ECC53F729F7E2625B10EF9AB9A';

// A side
$text4B = 'A text for B';

Test::$crypt_pgp_state = false;
$instanceA = new Test();
$cypher = $instanceA->crypt($text4B, $public_key_B);
echo $cypher;

// B side
Test::$crypt_pgp_state = false;
$instanceB = new Test();
$text4B = $instanceA->uncrypt($cypher);
echo $text4B;

