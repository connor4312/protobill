<?php

class UserControllerTest extends TestCase {
	public function testAll() {
		$response = $this->call('GET', '/api/user');
		$this->assertTrue($response->isOk());
	}
}