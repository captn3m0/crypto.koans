<?php

namespace TlsKoans;

defined('__') or define('__', null);

class BOpensslKeyGenerationKoans extends TestCase {

    const PASSPHRASE = 'changeme';

    /**
     * !Generate a private key in files/1.key
     * with no passphrase
     */
    public function testEasyPrivateKeyGenerated() {

        $key = openssl_pkey_get_private('file://files/1.key');

        if ($key === false) {
            $this->fail("Private key file not valid");
        }

        $this->assertFalse($key===false);
    }

    /**
     * !Generate a key with a given passphrase and save it to 2.key
     */
    public function testPrivateKeyGeneratedWithPassphrase() {
        $key = openssl_pkey_get_private('file://files/2.key', self::PASSPHRASE);

        if ($key === false) {
            $this->fail("Private key file not valid");
        }

        $this->assertFalse($key===false);
    }

    /**
     * !Generate a key with size 2048 and a given passphrase
     */
    public function testPrivateKeyGeneratedWithPassphraseAndSize() {
        $key = openssl_pkey_get_private('file://files/3.key', self::PASSPHRASE);

        if ($key === false) {
            $this->fail("Private key file not valid");
        }

        $info = openssl_pkey_get_details($key);

        // Verify the key size
        $this->assertEquals(2048, $info['bits']);
        // And the type
        $this->assertEquals(OPENSSL_KEYTYPE_RSA, $info['type']);
    }

    // !Strip the passphrase from 2.key and save it to 4.key
    public function testPassphraseStrippedFromKey() {
        $keyWithoutPassphrase = openssl_pkey_get_private('file://files/4.key');
        $keyWithPassphrase = openssl_pkey_get_private('file://files/2.key', self::PASSPHRASE);

        $this->assertNotFalse($keyWithoutPassphrase, "Check your keys");
        $this->assertNotFalse($keyWithPassphrase, "Are you sure this key has a passphrase");

        $this->verifyKeysMatch($keyWithPassphrase, $keyWithoutPassphrase);
    }

    // !Extract the public key from 1.key and save it to 1.pub
    public function testExtractPublicKey() {
        $key = openssl_pkey_get_private('file://files/1.key', self::PASSPHRASE);
        $info = openssl_pkey_get_details($key);

        $this->assertEquals($info['key'], file_get_contents('files/1.pub'));
    }
}
