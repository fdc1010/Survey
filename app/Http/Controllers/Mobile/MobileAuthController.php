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
    public function mobilelogin(Request $request)
	{	
		
		$validator = Validator::make($request->all(), [
			'imei'=>'required',
			'password'=>'required',
			'email'=>'required',
		]);

		if ($validator->fails()) {
			return Response::json(array(
				'reason' => $validator->getMessageBag()->toArray(),
				'success'=>false
			), 400);
		}
		if ( ! Auth::attempt($credentials))
		{
			return response()->json(['success'=>false,'reason'=>'Invalid credentials'],401);
		}
		else{
			return response()->json(['success'=>true,'reason'=>"You've Logged In!"],200);
		}
		
		
	}
	 public function mobilelogout(Request $request){

        $validator = Validator::make($request->all(), [
            'imei'=>'required',
            'password'=>'required',
            'email'=>'required',
        ]);

        if ($validator->fails()) {
            return Response::json(array(
                'errors' => $validator->getMessageBag()->toArray(),
                'success'=>false
            ), 400);
        }
				
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
}
