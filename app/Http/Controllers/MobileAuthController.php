<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\User;

class MobileAuthController extends Controller
{
    public function mobilelogin(Request $request)
	{	
		info($request);	
		$validator = Validator::make($request->all(), [
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
}
