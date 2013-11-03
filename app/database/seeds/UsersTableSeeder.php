<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->truncate();

		$users = array(
			array(
				'name_first' => 'John',
				'name_last' => 'Doe',
				'company' => '',
				'email' => 'john@example.com',
				'address_1' => '123 Null Road',
				'address_2' => '',
				'city' => 'Ithaca',
				'state' => 'NY',
				'postcode' => '123456',
				'country' => 'USA',
				'phone' => '+123-456-1234',
				'password' => '',
				'credit' => 0
			),
			array(
				'name_first' => 'Sherlock',
				'name_last' => 'Holmes',
				'company' => '',
				'email' => 'john@example.com',
				'address_1' => '221B Baker Street',
				'address_2' => '',
				'city' => 'London',
				'state' => '',
				'postcode' => 'NW1 6XE',
				'country' => 'England',
				'phone' => '+123-456-1234',
				'password' => '',
				'credit' => 100.05
			),
		);

		DB::table('users')->insert($users);
	}

}
