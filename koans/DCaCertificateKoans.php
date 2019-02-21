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
        // See RFC 5280: https://tools.ietf.org/html/rfc5280#section-4.2.1.9
        //
        // ! See if you can get this verified using the openssl command line
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
    public function testGenerateCsr($csr = '1.csr', $key = '1.key', $hostname = 'server.crypto.koans.invalid') {
        $csrSubject = openssl_csr_get_subject("file://files/$csr");
        $this->assertEquals($hostname, $csrSubject['CN']);

        $public = openssl_csr_get_public_key("file://files/$csr");
        $publicKeyDetails = openssl_pkey_get_details($public);

        // Now we validate that $key matches the public key component of our private key

        $private = openssl_pkey_get_private("file://files/$key");
        $privateKeyDetails = openssl_pkey_get_details($private);

        // The 'key' part only holds the public key
        $this->assertEquals($publicKeyDetails['key'], $privateKeyDetails['key']);
        // This is comparing the modulus
        $this->assertEquals($publicKeyDetails['rsa']['n'], $privateKeyDetails['rsa']['n']);
    }

    /**
     * !Sign a certificate using ca.key as the CA certificate
     * and ca.pem as the CA key
     * and 1.csr as the signing request
     *
     * Keep the common name as crypto.koans
     *
     * HELP: openssl x509 -req ...
     *
     * IMP: You will need to pass -CAcreateserial
     */
    public function testCertificateSignedUsingCA($crt = '1.crt', $key = '1.key', $ca = 'ca.pem') {
        // Validate that the certificate and the key match
        // This compares the public key in the certificate
        // and the public key in the private key
        $this->assertTrue(openssl_x509_check_private_key(
            "file://files/$crt",
            "file://files/$key"
        ));

        // Next, we need to validate that you indeed used the correct
        // CA to sign this certificate.
        //
        // This is quite hard to do in PHP so we shell this instead
        // See https://stackoverflow.com/a/45625492
        //
        // The phpseclib code has proper support for this btw
        exec("openssl verify -CAfile files/$ca files/$crt", $output, $return);

        $this->assertTrue($return === 0);
    }

    /**
     * !Read the google.pem certificate file and get the allowed purposes it has
     *
     * aside: The file was downloaded by the command
     *
     * openssl s_client -showcerts -connect google.com:443 </dev/null 2>/dev/null|openssl x509 -outform PEM > google.pem
     *
     * ! Then, fill the blanks (__) in the testcase below from what you just learnt
     */
    function testGoogleCertificate() {
        $crt = 'file://google.pem';

        $this->assertPurpose($crt, X509_PURPOSE_SSL_CLIENT, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_SSL_SERVER, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_NS_SSL_SERVER, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_SMIME_SIGN, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_SMIME_ENCRYPT, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_CRL_SIGN, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_ANY, 'files/ca.pem', __LINE__, __);
    }


    /**
     * Do the same as above, but for the certificate we just signed
     * (1.crt)
     */
    function testOurCertificate() {
        $crt = 'file://files/1.crt';

        $this->assertPurpose($crt, X509_PURPOSE_SSL_CLIENT, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_SSL_SERVER, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_NS_SSL_SERVER, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_SMIME_SIGN, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_SMIME_ENCRYPT, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_CRL_SIGN, 'files/ca.pem', __LINE__, __);
        $this->assertPurpose($crt, X509_PURPOSE_ANY, 'files/ca.pem', __LINE__, __);
    }

    /**
     * Understand what is the difference between a client certificate and a server certificate
     *
     * Now that we know that server and client certificates are just flags on a x509 certificate, we
     * can try to generate a purely client certificate:
     *
     * 1. Create a file called client.cnf in root directory
     * 2. Write the following block in that file:
     *
     * ```
     * extendedKeyUsage=clientAuth
     * keyUsage=digitalSignature
     * ```
     *
     * 3. Generate a key with no passphrase called client.key
     * 4. Generate a CSR in files/client.csr (common name = `YOURNAMEGOESHERE.crypto.koans` (remember -subj))
     * 5. Pass the client.csr file to the person next to you.
     *5b. Save the csr file you received to files/alice.csr
     * 5. Sign a certificate for alice.csr using ca.pem as the CA certificate
     *    and ca.key as the ca.key (same as last test case), BUT:
     * 6. Pass an extra `-extfile client.cnf` parameter
     * 7. Save the new certificate in files/alice.crt
     * 8. Return the certificate file back to the other person
     *8b. Save the client certificate you received as files/client.pem
     * 9. Give a copy of your ca.pem file to the other person
     *10. Save the ca certificate you recieved as files/bob.pem
     */

    public function testGenerateClientCertificate() {

        // Validate whether we generated our CSR correctly:
        $this->testGenerateCsr("client.csr", "client.key", 'client.crypto.koans');

        // Now let us validate our client certificate
        $crt = 'file://files/client.crt';

        // We also test whether bob signed our certificate:
        $this->testCertificateSignedUsingCA("client.crt", "client.key", "bob.pem");

        $this->assertPurpose($crt, X509_PURPOSE_SSL_CLIENT, 'files/bob.pem', __LINE__, true);
        $this->assertPurpose($crt, X509_PURPOSE_SSL_SERVER, 'files/bob.pem', __LINE__, false);
        $this->assertPurpose($crt, X509_PURPOSE_NS_SSL_SERVER, 'files/bob.pem', __LINE__, false);
        $this->assertPurpose($crt, X509_PURPOSE_SMIME_SIGN, 'files/bob.pem', __LINE__, false);
        $this->assertPurpose($crt, X509_PURPOSE_SMIME_ENCRYPT, 'files/bob.pem', __LINE__, false);
        $this->assertPurpose($crt, X509_PURPOSE_CRL_SIGN, 'files/bob.pem', __LINE__, false);
        $this->assertPurpose($crt, X509_PURPOSE_ANY, 'files/bob.pem', __LINE__, true);
    }

    /**
     * !Generate a client bundle which contains your client.key and client certificate
     *
     * openssl pkcs12 -export -out files/bundle.pfx -inkey files/client.key -in files/client.crt -certfile files/bob.pem
     *
     * Then import it in your browser (Search for Certificate in your browser settings)
     */
    public function testClientBundleGenerated() {
        $this->assertTrue(file_exists('files/bundle.pfx'));
        // TODO: Test for a valid bundle
    }

    private function assertPurpose($certificate, $purpose, $ca, $line, $answer) {

        $PURPOSES = [
            '',
            'the client side of an SSL connection',
            'the server side of an SSL connection',
            'Netscape SSL server',
            'signing S/MIME email',
            'encrypting S/MIME email',
            'signing a certificate revocation list (CRL)',
            'Any/All purposes',
        ];
        $purposeAllowed = openssl_x509_checkpurpose($certificate, $purpose, [$ca]);

        if ($purposeAllowed !== $answer) {
            $this->fail("Can the certificate $certificate be used for " . $PURPOSES[$purpose] . "? Check your answer on line $line");
        }

        // This double check is to avoid giving the answer in the testcase
        $this->assertSame($purposeAllowed, $answer);
    }
}
