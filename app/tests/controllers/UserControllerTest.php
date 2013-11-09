<?php

class UserControllerTest extends TestCase {

	public function __construct() {
		// Auth::loginUsingId(1);
	}

	public function testAll() {
		$response = $this->call('GET', '/api/user');
		
		$this->assertTrue($response->isOk());
		$data = $this->parseJson($response);
		$this->assertIsJson($data);
	}
}