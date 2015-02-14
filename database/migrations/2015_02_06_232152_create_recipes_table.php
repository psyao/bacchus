<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recipes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slug')->unique();
			$table->integer('preparation_time')->default(0);
			$table->integer('cooking_time')->default(0);
			$table->integer('total_time')->default(0);
			$table->integer('difficulty')->default(1);
			$table->integer('guests')->default(4);
			$table->integer('price')->default(1);
			$table->string('url')->nullable();
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
		Schema::drop('recipes');
	}

}
