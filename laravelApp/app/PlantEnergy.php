<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantEnergy extends Model
{
		public $timestamps = false;
		
    	protected $fillable = [
			'time', 'production','wanted_consumption','actual_consumption',
		];
		
		protected $hidden = [
			'id','plant_id'
		];

		
		
}
