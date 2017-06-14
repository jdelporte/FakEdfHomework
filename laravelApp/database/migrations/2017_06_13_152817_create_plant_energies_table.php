<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantEnergiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('plant_energies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plant_id')->references('id')->on('plants');
            $table->dateTime('time')->default(date('Y-m-d H:i:s',time()));
            $table->integer('energy')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plant_energies');
    }
}
