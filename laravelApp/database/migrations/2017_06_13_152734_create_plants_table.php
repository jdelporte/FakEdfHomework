<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('plants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->references('id')->on('users');
            $table->string('name');
            $table->integer('type_id')->references('id')->on('plant_types');
            $table->integer('capacity')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plants');
    }
}
