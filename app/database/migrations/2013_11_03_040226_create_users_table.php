<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name_first', 45);
			$table->string('name_last', 45);
			$table->string('company', 45)->default('');
			$table->string('email', 128);
			$table->string('address_1', 100);
			$table->string('address_2', 200)->default('');
			$table->string('city', 45);
			$table->string('state', 45);
			$table->string('postcode', 20);
			$table->string('country', 45);
			$table->string('phone', 20);
			$table->string('password', 200);
			$table->decimal('credit', 15, 2)->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Users');
	}

}
