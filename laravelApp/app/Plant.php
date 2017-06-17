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
		
		public function type(){
			return $this->hasOne(PlantEnergy::class,'plant_id');
		}
		
		public function getCurrentEnergy(){
			return $this->plantEnergies[count($this->plantEnergies)-1];
		}
				
		public function produce($e){
			$curr = $this->getCurrentEnergy();
			$this->plantEnergies()->create([
				'time' => date('Y-m-d H:i:s',time()),
				'production' => min($e,$this->capacity),
				'wanted_consumption' => min($curr->wanted_consumption,$this->capacity),
				'actual_consumption' => min($e<=$curr->wanted_consumption?$e:$curr->wanted_consumption,$this->capacity),
			]);
			
		}
		
		public function consume($e){
			$curr = $this->getCurrentEnergy();
			$this->plantEnergies()->create([
				'time' => date('Y-m-d H:i:s',time()),
				'production' => min($curr->production,$this->capacity),
				'wanted_consumption' => min($e,$this->capacity),
				'actual_consumption' => min($curr->production<=$e?$curr->production:$e,$this->capacity),
			]);
		}
		
		public function getHistoric(){
			return $this->plantEnergies;
		}
}
