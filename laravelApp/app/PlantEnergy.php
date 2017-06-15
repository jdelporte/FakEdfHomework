<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantEnergy extends Model
{
		public $timestamps = false;
		
    	protected $fillable = [
			'time', 'energy',
		];
		
		protected $hidden = [
			'id','plant_id'
		];

		
		
}
