<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Plant;
use App\Events\PlantChangedEvent;

class PlantController extends Controller
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
    
	public function getPlant(Request $request){
		$user = Auth::guard('api')->user();
		$plant = Plant::find($request->plant);
		$plantEnergies = $plant->plantEnergies;
		$plant['current_energy']=$plant->getCurrentEnergy();
		//$plant['energies']=$plantEnergies;
		return Response::json($plant);
	}

	public function produce(Request $request){
		$user = Auth::guard('api')->user();
		$plant = Plant::find($request->plant);
		$plant->produce((int)$request->energy);			
		
		$plant = Plant::find($request->plant);	
		$plantEnergies = $plant->plantEnergies;
		
		return Response::json($plant);			
	}
	
	public function consume(Request $request){
		$user = Auth::guard('api')->user();
		$plant = Plant::find($request->plant);
		$plant->consume((int)$request->energy);
		
		$plant = Plant::find($request->plant);	
		$plantEnergies = $plant->plantEnergies;
				
		return Response::json($plant);		
	}
}
