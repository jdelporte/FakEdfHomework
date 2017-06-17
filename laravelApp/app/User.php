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
	
	public function getRatios(){
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
	
	public function getBalance(){
		$plants = $this->plants;
		for ($i=0; $i< $plants.count; $i++){
			
		}
		
	}
    
}
