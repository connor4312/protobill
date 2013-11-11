<?php

class UserSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->insert(array(
			'name_first' => 'Firstname',
			'name_last' => 'Lastname',
			'company' => 'Company Name',
			'email' => 'admin@localhost.com',
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