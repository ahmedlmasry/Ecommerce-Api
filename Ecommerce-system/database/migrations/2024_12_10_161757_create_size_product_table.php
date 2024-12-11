<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSizeProductTable extends Migration {

	public function up()
	{
		Schema::create('product_size', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('size_id');
			$table->integer('product_id');
		});
	}

	public function down()
	{
		Schema::drop('product_size');
	}
}
