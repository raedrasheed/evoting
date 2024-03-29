<?php

namespace App\Http\Controllers;
use Socialite;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Log;
use Carbon\Carbon;


class SocialAuthController extends Controller
{
	public function redirect()
	{
	 return Socialite::driver('facebook')->redirect();
	}

	public function callback()
	{
		try {
			$user = Socialite::driver('facebook')->user();
			$userFound = User::where('facebook_id',$user->getId())->first();
			if($userFound){
				Auth::loginUsingId($userFound->id);
				$this->addLog("Facebook login");
			}else{				
				//$user = Socialite::driver('facebook')->user();
				//$create['name'] = $user->getName();
				//$create['email'] = $user->getEmail();
				//$create['facebook_id'] = $user->getId();

				$userModel = new User();
				//$createdUser = $userModel->addNew($create);
				$userModel->username = $user->getId();
				$userModel->password = bcrypt('password');
				$userModel->name = $user->getName();
				$userModel->email = $user->getEmail();
				$userModel->facebook_id = $user->getId();
				$userModel->role = 2;
				$userModel->save();
				$userModel = User::where('facebook_id',$user->getId())->first();
				Auth::loginUsingId($userModel->id);
				$this->addLog("Facebook registration");
				
			}
			
			return redirect()->route('home');

		} catch (Exception $e) {
			return redirect('redirect');
		}
	}
	/**
     * addLog Method: Save event to log table
	 *
     * @param action
     * @return void
    **/
	public function addLog($action){
		$log = new Log();
		$log->user_id = Auth::user()->id;
		$log->action = $action;
		$log->time = Carbon::now();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$MAC = exec('getmac');
		$MAC = strtok($MAC, ' '); 
		$log->mac = $MAC;
		$log->save();
		
	}
}
