<?php

namespace TlsKoans;

defined('__') or define('__', null);

class DCaCertificateKoans extends TestCase {
    /**
     * !Generate a self-signed certificate in files/ca.pem
     *  And the corresponding private key (with no passphrase) in files/ca.key
     *  Use common name as crypto.koans.invalid
     *
     * If generated with defaults, it will automatically be a CA certificate
     *
     * HELP: `man openssl req`
     *
     * SUPERHINT: Pass -subj '/CN=crypto.koans.invalid' to bypass CSR prompts.
     */
    public function testCaCertificateExists() {
        $key = openssl_pkey_get_private('file://files/ca.key');
        $cert = openssl_x509_read(file_get_contents('files/ca.pem'));

        $info = openssl_x509_parse($cert);

        $this->assertEquals('crypto.koans.invalid', $info['subject']['CN']);

        // If you'd like more details on what is this
        // Search for Key Usage RFC
        $this->assertEquals('CA:TRUE', $info['extensions']['basicConstraints']);
        $this->assertTrue(
            openssl_x509_check_private_key($cert, $key)
        );

        // What we learned:
        // A CA certificate is just a self-signed certificate
        // with CA: true
    }

    /**
     * !Generate a Certificate-Signing-Request using the 1.key
     * Save it in files/1.csr
     *
     * Keep the common name of the CSR as server.crypto.koans.invalid
     *
     * Pass a dot (.) for all other values if asked
     * HELP: openssl req -new ...
     *
     * SUPERHINT: Use -subj as from previous hint
     */
    public function testGenerateCsr() {
        $csrSubject = openssl_csr_get_subject('file://files/1.csr');
        $this->assertEquals('server.crypto.koans.invalid', $csrSubject['CN']);

        $public = openssl_csr_get_public_key('file://files/1.csr');
        $publicKeyDetails = openssl_pkey_get_details($public);

        // Now we validate that $key matches the public key component of our private key

        $private = openssl_pkey_get_private('file://files/1.key');
        $privateKeyDetails = openssl_pkey_get_details($private);

        // The 'key' part only holds the public key
        $this->assertEquals($publicKeyDetails['key'], $privateKeyDetails['key']);
        // THis is comparing the modulus
        $this->assertEquals($publicKeyDetails['rsa']['n'], $privateKeyDetails['rsa']['n']);
    }

    /**
     * !Sign a certificate using ca.key as the CA
     * and 1.csr as the signing request
     *
     * Keep the common name as crypto.koans
     *
     * HELP: openssl x509 -req ...
     *
     * IMP: You will need to pass -CAcreateserial
     */
    public function testCertificateSignedUsingCA() {
        // Validate that the certificate and the key match
        // This compares the public key in the certificate
        // and the public key in the private key
        $this->assertTrue(openssl_x509_check_private_key(
            'file://files/1.crt',
            'file://files/1.key'
        ));

        // Next, we need to validate that you indeed used the correct
        // CA to sign this certificate.
        //
        // This is quite hard to do in PHP so we shell this instead
        // See https://stackoverflow.com/a/45625492
        //
        // The phpseclib code has proper support for this btw
        exec("openssl verify -CAfile files/ca.pem files/1.crt", $output, $return);

        $this->assertTrue($return === 0);
    }

    /**
     * Understand what is the difference
     */
    public function testGenerateClientCertificate() {

    }
}
