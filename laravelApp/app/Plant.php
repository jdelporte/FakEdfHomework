<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
		public $timestamps = false;
	
	    protected $fillable = [
			'name', 'type_id', 'capacity',
		];
				

		public function __construct($attributes = array())  {
			parent::__construct($attributes);
			/*$this->plantEnergies()->create([
				'time' => date('Y-m-d H:i:s',time()),
				'energy' => 0
			]);*/
		}

		
		public function plantEnergies(){
			return $this->hasMany(PlantEnergy::class,'plant_id')->orderBy('time','asc');
		}
		
		public function getCurrentEnergy(){
			return $this->plantEnergies[count($this->plantEnergies)-1];
		}
				
		public function produce($e){
			$this->plantEnergies()->create([
				'time' => date('Y-m-d H:i:s',time()),
				'energy' => min($this->getCurrentEnergy()->energy+$e,$this->capacity)
			]);
			
		}
		
		public function consume($e){
			$this->plantEnergies()->create([
				'time' => date('Y-m-d H:i:s',time()),
				'energy' => max($this->getCurrentEnergy()->energy-$e,0)
			]);
		}
		
		public function getHistoric(){
			return $this->plantEnergies;
		}
}
