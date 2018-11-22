<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\User;
class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
	public function login(Request $request)
	{	
		
		/*$this->validate($request, [
		   'email' => 'required|email',
		   'password' => 'required|string',
		]);*/
		$validator = Validator::make($request->all(), [
			'imei'=>'required',
			'password'=>'required',
			'email'=>'required',
		]);
		if ($validator->fails()) {
			return Response::json(array(
				'reason' => $validator->getMessageBag()->toArray(),
				'msg'=>'Unauthorized, check your credentials.',
				'success'=>false
			), 400);
		}
		$user = User::where('email', $request->get('email'))->first();
		if($user){		   
		   $auth = Hash::check($request->get('password'), $user->password);
		   if($auth){
		
			  $user->rollApiKey(); //Model Function
			  $user->is_online=1;
			  $user->save();
			  return response()->json(['success'=>true,'msg'=>'Authorization Successful']);
		   }
		}
		//return response()->json(['success'=>false,'msg'=>'Unauthorized, check your credentials.']);
		
		
	}
	public function logout(Request $request){
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
		$user = User::where('email', $request->get('email'))->first();
		if($user){	
		
			  $user->api_token = null;
			  $user->is_online = 0;
			  return response()->json(['success'=>true,'msg'=>'Authorization Successful']);
		   
		}
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
