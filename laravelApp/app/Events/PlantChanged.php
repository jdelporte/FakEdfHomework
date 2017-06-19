<?php

namespace App\Events;

use DB;
use App\Plant;
use Sse\Event;
use Sse\SSE;

class PlantChanged implements Event {
	
	private $user;
	
	public function __construct($user) {
		$this->user = $user;
    }
	
    public function check(){
		$result = DB::table('user_last_checks')
					->where('user_id','=',$this->user->id)
					->get();
		if (count($result)<1){
			DB::table('user_last_checks')
				->insert(['user_id' => $this->user->id ,'time' => date('Y-m-d H:i:s',time())]);
			return false;
		}else{
			return true;
		}
    }

    public function update(){
		$changes = DB::table('plant_energies')
					->join('plants','plants.id','=','plant_energies.plant_id')
					->join('user_last_checks','user_last_checks.user_id','=','plants.owner_id')
					->where('plants.owner_id',$this->user->id)
					->whereRaw('plant_energies.time >= user_last_checks.time')
					->select('plant_energies.*')
					->get();
		DB::table('user_last_checks')
				->where('user_id','=',$this->user->id)
				->update(['time' => date('Y-m-d H:i:s',time())]);;
		return $changes;
    }
}
