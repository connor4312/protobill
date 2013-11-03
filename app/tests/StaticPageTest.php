<?php

class StaticPageTest extends TestCase {
	public function test() {
		$response = $this->call('GET', '/');
		$this->assertTrue($response->isOk());
	}
}