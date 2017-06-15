<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
		public $timestamps = false;
	
	    protected $fillable = [
			'name', 'type_id', 'capacity',
		];
		
		public function plantEnergies(){
			return $this->hasMany(PlantEnergy::class,'plant_id')->orderBy('time','asc');
		}
		
		public function getCurrentEnergy(){
			return $this->plantEnergies[count($this->plantEnergies)];
		}
		
		/*public function consume(int e){
			$plantEnergy = create
			$this->plantEnergies->fill(new PlantEnergy($this->id,max(getCurrentEnergy()->value-e,0));
		}
		
		public function reload(int e){
			$this->plantEnergies->fill(new PlantEnergy($this->id,min(getCurrentEnergy()->value+e,$this->capacity));
		}
		
		public function getHistoric(){
			return $this->plantEnergies;
		}*/
}
