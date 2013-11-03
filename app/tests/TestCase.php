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
		Mail::pretend(true);
	}

	public function tearDown() {
		parent::tearDown();
	}
}
