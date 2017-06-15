<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Plant;

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
		//$plant['energies']=$plantEnergies;
		return Response::json($plant);
	}

}
