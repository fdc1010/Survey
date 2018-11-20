<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	public function login(Request $request)
	{	
		dd("test");
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
	public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
}
