<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\User;

class MobileAuthController extends Controller
{
    public function login(Request $request)
	{	
		info($request);
		$this->validate($request, [
		   'email' => 'required|email',
		   'password' => 'required|string|min:8',
		]);
		
		
		if(User::where('email', $request->get('email'))->exists()){
		   $user = User::where('email', $request->get('email'))->first();
		   $auth = Hash::check($request->get('password'), $user->password);
		   if($user && auth){
		
			  $user->rollApiKey(); //Model Function
		
			  return response(array(
				 'currentUser' => $user,
				 'message' => 'Authorization Successful!',
			  ));
		   }
		}
		return response(array(
		   'message' => 'Unauthorized, check your credentials.',
		), 401);
		
		
	}
	 public function logout(Request $request){
		info("logged Out!");
		return response()->json(['success'=>true,'msg'=>'ok!']);
    }
}
