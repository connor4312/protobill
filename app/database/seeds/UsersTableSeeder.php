<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();

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
				'password' => Hash::make('seekrit123'),
				'credit' => 0,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			)
		);

		DB::table('users')->insert($users);
	}

}
