<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;
use App\Log;
use App\Voted;
use App\User;
use Auth;
use Hash;

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
	
	public function login(Request $request)
    {
     
        $username = $request->username;
        $password = $request->password;
     
      $user = User::where('username', $username)->first();
      if(!$user){
            return redirect()->back()->withInput($request->only('first_name', 'last_name'))->withErrors([
            'username' => 'We could not find you in our database, if you think this is a mistake kindly contact the site administrators',
                ]);
      }else{
          if($user->password){
             if (Hash::check($password, $user->password)) {
                    Auth::login($user);
                    	$log = new Log();
                		$log->user_id = $user->id;
                		$log->action = "Login";
                		$log->time = Carbon::now();
                		$log->ip = $_SERVER['REMOTE_ADDR'];
                		$MAC = exec('getmac');
                		$MAC = strtok($MAC, ' '); 
                		$log->mac = $MAC;
                		
                		$log->save();
                    return redirect('/home');
            }
          }
        }
           return redirect()->back()->withInput($request->only('first_name', 'last_name'))->withErrors([
            'username' => 'We could not find you in our database, if you think this is a mistake kindly contact the site administrators',
                ]);
      
      
      /*  LDAP Connection */
       /* $adServer = "10.0.0.31";
        $ldap = ldap_connect($adServer);
        $username = $request->username;
        $password = $request->password;
    
        $ldaprdn = 'IUG' . "\\" . $username;
    
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
    
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
    
        if ($bind) {
            $filter="(sAMAccountName=$username)";
            $result = ldap_search($ldap,"dc=iugaza,dc=edu",$filter);
            //ldap_sort($ldap,$result,"sn");
            $info = ldap_get_entries($ldap, $result);
           
            @ldap_close($ldap);
            
            //$user = User::where('username', $username)->first();
            
            Auth::login($user);
            $log = new Log();
    		$log->user_id = $user->id;
    		$log->action = "Login";
    		$log->time = Carbon::now();
    		$log->ip = $_SERVER['REMOTE_ADDR'];
    		$MAC = exec('getmac');
    		$MAC = strtok($MAC, ' '); 
    		$log->mac = $MAC;
    		
    		$log->save();
            return redirect('/home');
        } else {
             return redirect()->back()->withInput($request->only('first_name', 'last_name'))->withErrors([
            'username' => 'We could not find you in our database, if you think this is a mistake kindly contact the site administrators',
                ]);
        }*/
      /*******************/
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
		
		$vodted = Voted::where('user_id',$user->id)->first();
		$canVote = 0;
		
		$user = User::find($user->id);
		if($vodted==null){
			$user->voted = 0;
		}else{
			$user->voted = 1;
		}
		$user->save();
		
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
