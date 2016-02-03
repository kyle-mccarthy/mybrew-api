<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->integer('brewery_id');
            $table->integer('category_id');
            $table->integer('style_id');
            $table->integer('body');
            $table->integer('sweetness');
            $table->integer('color');
            $table->decimal('abv');
            $table->integer('ibu');
            $table->integer('hoppiness');
            $table->integer('maltiness');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('beers');
    }
}
