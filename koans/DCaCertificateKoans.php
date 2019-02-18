<?php

namespace TlsKoans;

defined('__') or define('__', null);

class DCaCertificateKoans extends TestCase {
    /**
     * !Generate a self-signed certificate in files/6.pem
     *  And the corresponding private key (with no passphrase) in files/6.key
     *  Use common name as crypto.koans.invalid
     *
     * If generated with defaults, it will automatically be a CA certificate
     */
    public function testCaCertificateExists() {
        $key = openssl_pkey_get_private('file://files/ca.key');
        $cert = openssl_x509_read(file_get_contents('files/ca.pem'));

        $info = openssl_x509_parse($cert);

        $this->assertEquals('crypto.koans.invalid', $info['subject']['CN']);
        $this->assertEquals('CA:TRUE', $info['extensions']['basicConstraints']);
        $this->assertTrue(
            openssl_x509_check_private_key($cert, $key)
        );
    }
}
