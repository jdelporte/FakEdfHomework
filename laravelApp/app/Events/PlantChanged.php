<?php

namespace App\Events;

use DB;
use App\Plant;
use Sse\Event;
use Sse\SSE;

class PlantChanged implements Event {
	
	private $user;
	private $lastCheck;
	
	public function __construct($user) {
		$this->user = $user;
    }
	
    public function check(){
		$result = DB::table('user_last_checks')
					->where('user_id','=',$this->user->id)
					->get();
		if (count($result)<1){			
			$this->lastCheck = date('Y-m-d H:i:s',time());
			DB::table('user_last_checks')
				->insert(['user_id' => $this->user->id ,'time' => $lastCheck]);
			return false;
		}else{
			$this->lastCheck = $result[0]->time;			
			DB::table('user_last_checks')
				->where('user_id','=',$this->user->id)
				->update(['time' => date('Y-m-d H:i:s',time())]);;
			return true;
		}
    }

    public function update(){
		$changes = DB::table('plant_energies')
					->join('plants','plants.id','=','plant_energies.plant_id')
					->join('user_last_checks','user_last_checks.user_id','=','plants.owner_id')
					->where('plants.owner_id',$this->user->id)
					->whereRaw("plant_energies.time >= '".$this->lastCheck."'")
					->select('plant_energies.*')
					->get();
		return $changes;
    }
}
