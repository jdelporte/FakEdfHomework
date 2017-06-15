<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
    
    public function login(Request $request){
	}
    
    public function getUser(Request $request){
		$user = Auth::guard('api')->user();
		return Response::json($user);
	}
    
    public function createPlant(Request $request){
		$user = Auth::guard('api')->user();
		$plant = $user->plants()->create([
			'name' => $request->name,
			'type_id' => $request->type_id,
			'capacity' => $request->capacity
		]);
		$plantEnergy = $plant->plantEnergies()->create([		
			'time' => date('Y-m-d H:i:s',time()),
			'energy' => 0
		]);
		return Response::json($plant);
	}
    
    public function getListOfPlants(Request $request) {
		$user = Auth::guard('api')->user();
		$plants = $user->plants;
		return Response::json($plants);
	}

}
