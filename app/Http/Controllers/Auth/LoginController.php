<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
		else {
			if(Auth::check()){
				Auth::logout();
				$errors = [$this->username() => trans('auth.suspend')];
				return redirect('/login')
					->withInput($request->only($this->username(), 'remember'))
					->withErrors($errors);
			}
		}
			/*else if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'suspend' => 0, 'is_online'=> 1])) {
				if(Auth::check()){
					Auth::logout();
					$errors = [$this->username() => trans('auth.alreadylogin')];
					return redirect('/login')
						->withInput($request->only($this->username(), 'remember'))
						->withErrors($errors);
				}

			}*/
			else{
				$errors = [$this->username() => trans('auth.failed')];
				return redirect()->back()
					->withInput($request->only($this->username(), 'remember'))
					->withErrors($errors);
			}
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
