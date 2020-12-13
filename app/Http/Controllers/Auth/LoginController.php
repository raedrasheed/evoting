<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;
use App\Log;

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
	
	public function username()
	{
		return 'username';
	}
	
	function authenticated(Request $request, $user)
	{
		//dd($user);
		$log = new Log();
		$log->user_id = $user->id;
		$log->action = "Login";
		$log->time = Carbon::now();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$MAC = exec('getmac');
		$MAC = strtok($MAC, ' '); 
		$log->mac = $MAC;
		
		$log->save();
		
		return redirect('home');
	}
	
	public function logout(Request $request)
    {
		$log = new Log();
		$log->user_id = $request->user()->id;
		$log->action = "Logout";
		$log->time = Carbon::now();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$MAC = exec('getmac');
		$MAC = strtok($MAC, ' '); 
		$log->mac = $MAC;

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();
		
		$log->save();

        return redirect('/');
    }
}
