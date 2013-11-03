<?php

class UserControllerTest extends TestCase {

	public function __construct() {
		Auth::loginUsingId(1);
	}

	public function testAll() {
		$response = $this->call('GET', '/api/user');
		$this->assertTrue($response->isOk());

		$this->assertTrue(!! json_decode($response->getContent()));
	}
}