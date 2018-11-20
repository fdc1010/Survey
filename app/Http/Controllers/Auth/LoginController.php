<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function login(Request $request)
    {
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			return redirect()->intended('/admin');
				
		}
		else{
				$errors = [$this->username() => trans('auth.failed')];
				return redirect()->back()
					->withInput($request->only($this->username(), 'remember'))
					->withErrors($errors);
			}
		
		

    }

    public function username()
    {
        return 'email';
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
