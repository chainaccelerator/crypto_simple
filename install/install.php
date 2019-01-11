#! /usr/local/bin/php
<?php

require_once('../src/compress_simple.php');
require_once('../src/rsa_simple.php');
require_once('../src/cert_simple.php');
require_once('../src/pgp_simple.php');
require_once('../src/crypto_simple.php');

class Install {

    use Crypto_simple;
}

Install::crypto_install();

