<?php

class PermissionsControllerTest extends TestCase {


	public function testIndex() {
		$response = $this->call('GET', '/api/permission');
		
		$this->assertTrue($response->isOk());
		$data = $this->parseJson($response);
		$this->assertIsJson($data);
	}

	public function testShow() {
		$response = $this->call('GET', '/api/permission');
		
		$this->assertTrue($response->isOk());
		$data = $this->parseJson($response);
		$this->assertIsJson($data);
	}
}