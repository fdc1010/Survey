<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;
	protected $redirectTo = '/home';
	
	public function login(Request $request){
		info($request);
	}
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
}
