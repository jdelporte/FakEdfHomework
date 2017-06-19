<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getKey(Request $request){
		if (Auth::attempt(['email'=>$request->email,'password'=>$request->password],true)){
			return Response::json(Auth::user());
		}
		
	}
    
    public function getUser(Request $request){
		/*$response=[];
		$user = Auth::guard('api')->user();
		if ($user->id != $request->user){
			$response['error']='Unauthorized';
		}else{			
			$response = User::find($user->id);
		}	*/
		$response = Auth::guard('api')->user();
		return Response::json($response);
	}
	
	public function getBalance(Request $request){		
		$user = Auth::guard('api')->user();
		if ($user->id != $request->user){
			$response['error']='Unauthorized';
		}else{	
			return Response::json($user->getBalance());
		}
	}
	
		public function getSummary(Request $request){		
		$user = Auth::guard('api')->user();
		if ($user->id != $request->user){
			$response['error']='Unauthorized';
		}else{	
			return Response::json($user->getPlantSummary());
		}
	}
    
    public function createPlant(Request $request){		
		$response=[];
		$user = Auth::guard('api')->user();
		if ($user->id != $request->user){
			$response['error']='Unauthorized';
		}else{		
			$user =  User::find($user->id);
			$plant = $user->plants()->create([
				'name' => $request->name,
				'type_id' => $request->type_id,
				'capacity' => $request->capacity
			]);		
			$plantEnergy = $plant->plantEnergies()->create([		
				'time' => date('Y-m-d H:i:s',time()),
				'production' => 0,
				'wanted_consumption' => 0,
				'actual_consumption' => 0,
			]);
			$response = $plant;
		}
		return Response::json($response);
	}
    
    public function getListOfPlants(Request $request) {		
		$response=[];
		$user = Auth::guard('api')->user();		
		if ($user->id != $request->user){
			$response['error']='Unauthorized';
		}else{		
			$user =  User::find($user->id);
			$plants = $user->plants;		
			//$plant['current_energy']=$plant->getCurrentEnergy();
			$response = $plants;
		}
		return Response::json($response);
	}

}
