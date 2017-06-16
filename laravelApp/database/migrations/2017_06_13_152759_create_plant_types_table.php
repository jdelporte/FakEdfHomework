<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantTypesTable extends Migration
{
	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {		
		Schema::create('plant_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
        });
        
        DB::table('plant_types')->insert(
			array(
				['type' => 'nuclear'],
				['type' => 'wind'],
				['type' => 'hydrolic'],
				['type' => 'thermic'],
			)
		);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plant_types');
    }
}
