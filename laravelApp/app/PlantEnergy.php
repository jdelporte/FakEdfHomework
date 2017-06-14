<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantEnergy extends Model
{
		public $timestamps = false;
		
    	protected $fillable = [
			'plant_id','time' 'value',
		];
		
		
}
