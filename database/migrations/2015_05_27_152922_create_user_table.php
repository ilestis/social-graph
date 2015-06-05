<?php

/**
 * Written by Jeremy Payne
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Users
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('firstname', 50);
			$table->string('surname', 50);
			$table->tinyInteger('age')->unsigned()->nullable();
			$table->enum('gender', ['male', 'female']);

			// Track created et updated_at
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
		Schema::drop('users');
	}

}
