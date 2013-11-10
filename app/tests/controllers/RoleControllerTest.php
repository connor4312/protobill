<?php

class RoleControllerTest extends TestCase {


	public function testIndex() {
		$mock = Mockery::mock('Repository\RoleRepositoryInterface');
		$mock->shouldReceive('all')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\RoleRepositoryInterface', $mock);

		$response = $this->call('GET', '/api/role');
		
		$this->assertTrue($response->isOk());;
	}

	public function testShow() {
		$mock = Mockery::mock('Repository\RoleRepositoryInterface');
		$mock->shouldReceive('show')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\RoleRepositoryInterface', $mock);

		$response = $this->call('GET', '/api/role/1');
		
		$this->assertTrue($response->isOk());
	}

	public function testCreate() {
		$mock = Mockery::mock('Repository\RoleRepositoryInterface');
		$mock->shouldReceive('create')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\RoleRepositoryInterface', $mock);

		$response = $this->call('POST', '/api/role');
		
		$this->assertTrue($response->isOk());
	}

	public function testDelete() {
		$mock = Mockery::mock('Repository\RoleRepositoryInterface');
		$mock->shouldReceive('delete')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\RoleRepositoryInterface', $mock);

		$response = $this->call('DELETE', '/api/role/1');
		
		$this->assertTrue($response->isOk());
	}

	public function testEdit() {
		$mock = Mockery::mock('Repository\RoleRepositoryInterface');
		$mock->shouldReceive('edit')->once()->andReturn(Response::json(array(), 200));
		App::instance('Repository\RoleRepositoryInterface', $mock);

		$response = $this->call('PUT', '/api/role/1');
		
		$this->assertTrue($response->isOk());
	}
}