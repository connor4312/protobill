<?php

class EntrustSeeder extends Seeder {

	public function run()
	{
		DB::table('permissions')->delete();

		$permissions = Config::get('protobill.permissionsList');
		$insert = array();

		foreach ($permissions as $name => $display_name) {
			$insert[] = compact('name', 'display_name') + array(
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			);
		}

		DB::table('permissions')->insert($insert);
	}

}