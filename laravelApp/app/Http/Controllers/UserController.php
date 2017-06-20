<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;    
use Illuminate\Support\Facades\Validator;
use App\User;
use App\PlantChangedWorker;
use App\Events\PlantChanged;
use Sse\SSE;

use Hash;



class UserController extends Controller
{

	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }
    
    public function getKey(Request $request){
		$response =[];		
		if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){	
			$response=['error' => 'Authentication failed',
				];
		}else{
			$user = DB::table('users')->where('email',$request->email)->get();
			$response=['api_token' => $user[0]->api_token];
		}
		return Response::json($response);
	}
    
    public function createUser(Request $request){
		$response = [];			
		$data = [
			'name' => $request->name,
			'email' => $request->email,
			'password' => $request->password,
		];
		if (!Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
		])){
			$response = ['error' => 'Invalid information'];
		}else{
			User::create([
				'name' => $request['name'],
				'email' => $request['email'],
				'api_token' => str_random(60),
				'password' => bcrypt($request['password']),
			]);
			$response = ['success' => 'User created'];
		}   
		return Response::json($response);     
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
	
	public function getEventStream(Request $request){		
		$user = Auth::guard('api')->user();			
		
		$sse = new SSE();
		$sse->addEventListener('message',new PlantChanged($user));
		
		//To handle threading invert comment below
		
		//$worker = new PlantChangedWorker($sse);
		//$worker->start();
		$sse->start();
	}

}
