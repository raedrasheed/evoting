<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Config;
		  
use App\User;
use App\Log;
use Auth;
use Carbon\Carbon;



class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   	public function users(){
		$this->addLog("View all users");				
		$users = User::orderBy('name')->paginate(15);		
		return view ('users',compact('users'));         
    }
    public function usersAll(){
		$this->addLog("View all users");
				
		$users = User::orderBy('name')->get();		
		return view ('usersAll',compact('users'));         
    }
	public function blockchainExplorer(){
		$this->addLog("View Blockchain Explorer");
		
		$fine = $this->blockchainValid();
		$blocks = Block::paginate(15);
			
        return view('blockchainExplorer',compact('fine','blocks'));
    }
	public function addEditUser($id = null){
		if($id){
			$user = User::find($id);
			return view('addEditUser',compact('user'));
		}
		else
			return view('addEditUser');
    }
	public function saveUser(Request $request){
		$this->validate($request, [
			'outer_id' => 'required',
			'name' => 'required',			
			'username' => 'required',
			'password' => 'required',
			'role' => 'required',
			'mobile' => 'required'			
		]);
		$photo = '';
		
		if($request->input('id')){
			$user = User::find($request->input('id'));
			$photo = $user->photo;
			
			if($user->password == $request->input('password'))
				$user->password = $request->input('password');				
			else
				$user->password = Hash::make($request->input('password'));
			
			$this->addLog("Edit user: ".$user->name);
			
		}
		else{
			$user = new User();
			$user->password = Hash::make($request->input('password'));
			$this->addLog("Add new user: ".$request->input('name'));
			 
		}
		
		if($request->hasFile('photo')){
			if ($request->file('photo')->isValid()) {				
				$image_name = date('mdYHis') . uniqid() . $request->file('photo')->getClientOriginalName();
				$path = 'imgs/photos';
				$request->file('photo')->move($path,$image_name);
				$photo = 'imgs/photos/'.$image_name;
			}
		}
		
		$user->outer_id = $request->input('outer_id');
		$user->name = $request->input('name');		
		$user->photo = $photo;
		$user->username = $request->input('username');
		$user->role = $request->input('role');
		$user->is_active = ($request->input('is_active'))?true:false;
		$user->description = $request->input('description');
		$user->mobile = $request->input('mobile');
		
		$user->save();
		
        $users = User::orderBy('name')->paginate(12);
		return redirect()->route('users')->with( ['users' => $users] );
    }
	public function deleteUser($id = null){
		if($id){
			$user = User::find($id);
			$this->addLog("Delete user: ".$user->name);
			$user->delete();
		}
		$users = User::orderBy('name')->paginate(12);		
		return redirect()->route('users')->with( ['users' => $users] );
		
    }
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
