<?php

class AuthenticationControllerTest extends TestCase {

	public function __construct() {
		$this->mock = Mockery::mock('Repository\UserRepositoryInterface');
	}

	public function testInvalid() {
		$response = $this->call('POST', '/api/authenticate', array(
			'email' => 'john@example.com',
			'password' => 'invalid'
		));
		$this->assertFalse($response->isOk());
	}

	public function testValid() {
		$this->mock->shouldReceive('getCurrentUser')->andReturn(array('success' => 'yep!'));
		App::instance('Repository\UserRepositoryInterface', $this->mock);

		$response = $this->call('POST', '/api/authenticate', array(
			'email' => 'john@example.com',
			'password' => 'seekrit123'
		));
		$this->assertTrue($response->isOk());

		$result = json_decode($response->getContent());

		$this->assertTrue(count($result) === 1);
	}

	public function testLogout() {
		Session::set('foo', 'bar');
		Auth::loginUsingId(1);

		$response = $this->call('POST', '/api/authenticate/logout');
		$this->assertTrue($response->isOk());

		$this->assertTrue(! Session::get('foo'));
		$this->assertTrue(Auth::guest());
	}

	public function testCurrent() {
		Auth::loginUsingId(1);

		$response = $this->call('POST', '/api/authenticate/current');
		$this->assertTrue($response->isOk());

		$result = json_decode($response->getContent());

		$this->assertTrue(count($result) === 1);
	}
}