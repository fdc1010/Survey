<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
	public function mobilelogin(Request $request)
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
	 public function mobilelogout(Request $request){
		info("logged Out!");
		return response()->json(['success'=>true,'msg'=>'ok!']);
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
