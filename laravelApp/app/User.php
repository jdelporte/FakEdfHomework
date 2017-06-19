<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','api_token', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at',
    ];
    
    public function plants(){
		return $this->hasMany(Plant::class,'owner_id');
	}
	
	public function getBalance(){
		$plants = $this->plants;
		$prod = 0;
		$cons =0;
		for ($i=0; $i< count($plants); $i++){
			$curr = $plants[$i]->getCurrentEnergy();
			$prod += $curr->production;
			$cons += $curr->actual_consumption;
		}
		$response['production']=$prod;
		$response['consumption']=$cons;
		return $response;
	}
	
	public function getPlantSummary(){
		$plants = $this->plants;
		$response = [];
		for ($i=0; $i< count($plants); $i++){
			$curr = $plants[$i]->getCurrentEnergy();
			if (array_key_exists($plants[$i]->type_id,$response)){
				$response[$plants[$i]->type_id]['production']+=$curr->production;
				$response[$plants[$i]->type_id]['consumption']+=$curr->actual_consumption;
				
			}else{
				$response[$plants[$i]->type_id]=[
					'production'  => $curr->production,
					'consumption' => $curr->actual_consumption
				];
			}
		}
		return $response;
		
	}
    
}
