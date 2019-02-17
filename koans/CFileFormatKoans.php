<?php

namespace TlsKoans;

defined('__') or define('__', null);

class CFileFormatKoans extends TestCase {

    public function testPrivateKeyWasInPemFormat() {
        $command = "openssl asn1parse -in files/1.key -inform PEM";
        $this->validateCommandAgainstRegex($command, '/SEQUENCE/', "can't parse key");
    }

    // Convert the 1.key file from PEM format to DER
    // Then fix a few lines below to figure out how the conversion works
    public function testPrivateKeyConvertedToDerFormat() {
        $command = "openssl asn1parse -in files/5.key -inform DER";

        // Validate that the key is a valid DER key
        $this->validateCommandAgainstRegex($command, '/SEQUENCE/', "can't parse key");
        $derKeyContents = file_get_contents('files/5.key');

        // !See the existing 1.key file to figure out what goes in type
        $type = "changeme";

        // !Convert the key, you will need to fix the der2pem function
        // in TestCase.php to understand how it works
        $convertToPemContents = $this->der2pem($derKeyContents, $type);
        file_put_contents('files/5.pem', $convertToPemContents);

        $k1 = openssl_pkey_get_private('file://files/5.pem');
        $k2 = openssl_pkey_get_private('file://files/1.key');

        $this->verifyKeysMatch($k1, $k2);

        // What we learnt:
        // Understand that the PEM and DER file formats are identical
        // except in just encoding
        $this->verifyFilesMatch('files/1.key', 'files/5.pem');
    }
}
