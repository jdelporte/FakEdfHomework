<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;


class PlantTypeController extends Controller
{
	    
	public function getTypesList(Request $request){
		$response = DB::table('plant_types')->get();
		return Response::json($response);
	}

}
