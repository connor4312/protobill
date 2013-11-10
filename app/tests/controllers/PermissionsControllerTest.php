<?php

class PermissionsControllerTest extends TestCase {


	public function testIndex() {
		$mock = Mockery::mock('Repository\PermissionRepositoryInterface');
		$mock->shouldReceive('all')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\PermissionRepositoryInterface', $mock);

		$response = $this->call('GET', '/api/permission');
		
		$this->assertTrue($response->isOk());
	}

	public function testShow() {
		$mock = Mockery::mock('Repository\PermissionRepositoryInterface');
		$mock->shouldReceive('show')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\PermissionRepositoryInterface', $mock);

		$response = $this->call('GET', '/api/permission/1');
		
		$this->assertTrue($response->isOk());
	}
}