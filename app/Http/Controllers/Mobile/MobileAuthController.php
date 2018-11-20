<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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
	 public function logout(Request $request){

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
				
        $credentials = $request->only('email','password');
		
        if ( ! Auth::attempt($credentials))
        {
            return response()->json(['ok'=>'false','success'=>false,'reason'=>'Invalid credentials']);
        }else{
            $user = User::where('email',$request->email)->first();
            $u = User::find($user->id);
            $u->imei = $request->imei;
            $u->save();
            return response()->json(['ok'=>'success','success'=>true]);
        }

    }
}
