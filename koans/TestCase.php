<?php

namespace TlsKoans;

use PHPUnit\Framework\TestCase as Base;

class TestCase extends Base {
    protected function validateCommandAgainstRegex(string $cmd, $regex, $message = "", $index = 0) {
        exec($cmd, $output);

        $this->assertRegExp($regex, $output[$index], $message);
    }

    protected function verifyKeysMatch($k1, $k2) {
        $info1 = openssl_pkey_get_details($k1);
        $info2 = openssl_pkey_get_details($k2);

        // Ensure that the public keys in 2 and 4 match!
        $this->assertEquals($info1['key'], $info2['key']);
    }

    // ! Change this function till you get it right
    protected function der2pem($der_data, $type = 'I DONT KNOW') {
        // ! CHANGEME
        // Open a pem encoded file and figure out the correct chunk size
        $characters_per_line = 128;
        $encoded = base64_encode($der_data);
        $pem = trim(chunk_split($encoded, $characters_per_line, "\n"));
        $pem = <<<EOT
-----BEGIN $type-----
$pem
-----END $type-----
EOT;
        return $pem;
    }

    // Simple helper function to strip whitespace
    // and verify files match each other
    protected function verifyFilesMatch($f1, $f2) {
        $this->assertEquals(
            hash("sha256", trim(file_get_contents($f1))),
            hash("sha256", trim(file_get_contents($f2))),
        "Files $f1 and $f2 don't match");
    }
}
