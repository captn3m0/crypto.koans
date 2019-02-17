<?php

namespace TlsKoans;

defined('__') or define('__', null);

class SetupKoans extends TestCase {
	public function testEnvironmentIsZen() {
		$this->assertNotNull(`which openssl 2>/dev/null`);
		$this->assertNotNull(`which docker 2>/dev/null`);
		$this->assertTrue(count(array_intersect(get_loaded_extensions(), ['curl', 'openssl'])) == 2);
	}

	public function testOpensslVersion() {
		// Change Me
		$command = "changeme";

		exec("openssl $command 2>&1", $output);

		$this->assertRegExp('/OpenSSL 1.*/', $output[0]);
	}
}
