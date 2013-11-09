<?php

use Illuminate\Support\Facades\Config;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	public function setUp() {
		parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed');

		Mail::pretend(true);
	}

	public function tearDown() {
		parent::tearDown();

		Artisan::call('migrate:reset');
		Mockery::close();
	}

	protected function parseJson($response)
	{
		return json_decode($response->getContent());
	}

	protected function assertIsJson($data)
	{
		$this->assertEquals(0, json_last_error());
	}
}
