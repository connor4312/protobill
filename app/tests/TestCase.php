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

		$this->resetTables();

		Mail::pretend(true);
	}

	public function tearDown() {
		parent::tearDown();

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

	protected function resetTables() {

		Artisan::call('migrate');

		DB::table('users')->insert(array(
			'name_first' => 'Firstname',
			'name_last' => 'Lastname',
			'company' => 'Company Name',
			'email' => 'john@example.com',
			'address_1' => 'Address Line 1',
			'address_2' => 'Address Line 2',
			'city' => 'City',
			'state' => 'State',
			'postcode' => '123456',
			'country' => 'USA',
			'phone' => '+123-456-1234',
			'password' => Hash::make('seekrit123'),
			'credit' => 10,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
	}
}
