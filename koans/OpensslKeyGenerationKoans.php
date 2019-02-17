<?php

namespace TlsKoans;

use PHPUnit\Framework\TestCase;

defined('__') or define('__', null);

class OpensslKeyGenerationKoans extends TestCase {

    const PASSPHRASE = 'changeme';

    /**
     * Generate a private key in files/1.key
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
     * Generate a key with a given passphrase
     */
    public function testPrivateKeyGeneratedWithPassphrase() {
        $key = openssl_pkey_get_private('file://files/2.key', self::PASSPHRASE);

        if ($key === false) {
            $this->fail("Private key file not valid");
        }

        $this->assertFalse($key===false);
    }

    /**
     * Generate a key with size 2048 and a given passphrase
     */
    public function testPrivateKeyGeneratedWithPassphraseAndSize() {
        $key = openssl_pkey_get_private('file://files/3.key', self::PASSPHRASE);

        if ($key === false) {
            $this->fail("Private key file not valid");
        }

        $info = openssl_pkey_get_details($key);

        $this->assertEquals(2048, $info['bits']);
        $this->assertEquals(OPENSSL_KEYTYPE_RSA, $info['type']);
    }

    public function testPassphraseStrippedFromKey() {
        $keyWithoutPassphrase = openssl_pkey_get_private('file://files/4.key');
        $keyWithPassphrase = openssl_pkey_get_private('file://files/2.key', self::PASSPHRASE);

        $this->assertNotFalse($keyWithoutPassphrase, "Check your keys");
        $this->assertNotFalse($keyWithPassphrase, "Are you sure this key has a passphrase");

        $info1 = openssl_pkey_get_details($keyWithPassphrase);
        $info2 = openssl_pkey_get_details($keyWithoutPassphrase);

        // Ensure that the public keys in 2 and 4 match!
        $this->assertEquals($info1['key'], $info2['key']);
    }
}
