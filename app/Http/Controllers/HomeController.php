<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
		  
use App\User;
use App\Nominee;
use App\Log;
use Auth;
use Carbon\Carbon;
use App\Block;

class HomeController extends Controller
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
    public function index()
    {
		$presidentialNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','1');
		$academicMemberNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','2');
		$administrativeMemberNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','3');
										
		$lastBlock = Block::latest()->first();
		$version = "1.0";
		$previous_block_hash = '';
		if($lastBlock)
			$previous_block_hash = $lastBlock->block_hash; // Need to check and recalculate
		else $previous_block_hash = '0';
		$difficulty_target = 3;
		
        return view('home',compact('presidentialNominees','academicMemberNominees','administrativeMemberNominees','version','previous_block_hash', 'difficulty_target'));
    }
	
	public function nominees()
    {
		$this->addLog("View all nominees");
		/*$log = new Log();
		$log->user_id = Auth::user()->id;
		$log->action = "View all nominees";
		$log->time = Carbon::now();
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$MAC = exec('getmac');
		$MAC = strtok($MAC, ' '); 
		$log->mac = $MAC;		
		$log->save();*/
		
		$nominees = Nominee::orderBy('type', 'asc')->
							orderBy('name')->					
							get();		
		return view ('nominees',compact('nominees'));    
    }
	public function users()
    {
		$this->addLog("View all users");
				
		$users = User::orderBy('name')->paginate(10);		
		return view ('users',compact('users'));         
    }
	public function blockchainExplorer()
    {
		$this->addLog("View Blockchain Explorer");
		
		$fine = $this->blockchainValid();
		$blocks = Block::paginate(10);
			
        return view('blockchainExplorer',compact('fine','blocks'));
    }
	
	public function voteCards()
    {
		$this->addLog("View vote cards");
		
		$presidentialNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','1');
		$academicMemberNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','2');
		$administrativeMemberNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','3');
		
		$fine = $this->blockchainValid();
		$blocks = Block::paginate(10);
		$noOfVotes = Block::all()->count();
			
        return view('voteCards',compact('fine','noOfVotes','blocks','presidentialNominees','academicMemberNominees','administrativeMemberNominees'));
    }
	public function results()
    {
		$this->addLog("View results");
			
		$users = User::where('role',2);
		$allVoters = $users->count();
		$blocks = Block::all();
		$totalVotes = $blocks->count();
		$votingPrecentage = $totalVotes / $allVoters * 100;
		
		$presidentialNominees = Nominee::orderBy('name', 'asc')->
										get()->
										where('type','1');
		$memberNominees = Nominee::orderBy('name', 'asc')->
										get()->
										whereIn('type', [2, 3]);
										
		$nomineesVotesJSON = $this->getVotingResults();
		
		$incorrectVotes = 0;
		
		$prevBlock = 0;
		$error = false;
		foreach ($blocks as $block) {
			if(!$prevBlock){
				$block_header = json_decode($block->block_header, true);
				if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
					$error = true;
					$incorrectVotes++;
				}				
			}else{
				$block_header = json_decode($block->block_header, true);
				if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
					$error = true;
					$incorrectVotes++;
				}elseif(hash('sha256',$prevBlock->block_header) != $block_header["previous_block_hash"]){
					$error = true;
					$incorrectVotes++;
				}				
			}
			$prevBlock = $block;
		}
		$correctVotes = $totalVotes - $incorrectVotes;
		
        return view('results',compact('allVoters','totalVotes','correctVotes','incorrectVotes','votingPrecentage','nomineesVotesJSON'));
    }
	public function settings()
    {
		$this->addLog("View settings");
				
        return view('settings');
    }
	
	public function addEditNominee($id = null)
    {		
		if($id){
			$nominee = Nominee::find($id);		
			return view('addEditNominee',compact('nominee'));
		}else{			
			return view('addEditNominee');
		}
    }
	public function saveNominee(Request $request)
    {
		$this->validate($request, [
			'name' => 'required',
			'type' => 'required',				
		]);
		$photo = '';
		
		if($request->input('id')){
			
			$nominee = Nominee::find($request->input('id'));
			$photo = $nominee->photo;
			
			$this->addLog("Edit nominee: ".$request->input('name'));
						
		}
		else{
			$this->addLog("Add new nominee: ".$request->input('name'));
						
			$nominee = new Nominee();
		}
		
		if($request->hasFile('photo')){
			if ($request->file('photo')->isValid()) {				
				$image_name = date('mdYHis') . uniqid() . $request->file('photo')->getClientOriginalName();
				$path = 'imgs/photos';
				$request->file('photo')->move($path,$image_name);
				$photo = 'imgs/photos/'.$image_name;
			}else{
				//$photo = 'imgs/photos/photo.jpg';
			}
		}else{
			//$photo = 'imgs/photos/photo.jpg';
		}
		$nominee->name = $request->input('name');		
		$nominee->photo = $photo;		
		$nominee->type = $request->input('type');
		$nominee->description = $request->input('description');
		
		$nominee->save();
		
        $nominees = Nominee::all();		
		return redirect()->route('nominees')->with( ['nominees' => $nominees] );
    }
	public function deleteNominee($id = null)
    {
		if($id){
			$nominee = Nominee::find($id);
			
			$this->addLog("Delete nominee: ".$nominee->name);
						
			$nominee->delete();
		}		
		$nominees = Nominee::all();		
		return redirect()->route('nominees')->with( ['nominees' => $nominees] );
		
    }
	
	
	
	public function addEditUser($id = null)
    {
		if($id){
			$user = User::find($id);
			return view('addEditUser',compact('user'));
		}
		else
			return view('addEditUser');
    }
	public function saveUser(Request $request)
    {
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
		$user->description = $request->input('description');
		$user->mobile = $request->input('mobile');
		
		$user->save();
		
        $users = User::paginate(10);	
		return redirect()->route('users')->with( ['users' => $users] );
    }
	public function deleteUser($id = null)
    {
		if($id){
			$user = User::find($id);
			$this->addLog("Delete user: ".$user->name);
			$user->delete();
		}
		$users = User::paginate(10);		
		return redirect()->route('users')->with( ['users' => $users] );
		
    }
	
	public function logs($id = null)
    {
		if($id){			
			$logs = Log::where('user_id',$id)->paginate(10);			
		}else{
			$logs = Log::paginate(10);		
		}        
		return view ('logs',compact('logs'));     
    }
	
	public function deleteLog($id = null)
    {
		if($id){
			$log = Log::find($id);
			$this->addLog("Delete log action: ".$log->action);
			$log->delete();
		}
		$logs = Log::paginate(10);		
		return redirect()->route('logs', '0')->with( ['logs' => $logs] );
		
    }
	
	public function clearLogs()
    {
		
		Log::truncate();
		$this->addLog("Clear all logs");
		$logs = Log::all();		
		return redirect()->route('logs', '0')->with( ['logs' => $logs] );
		
    }
	
	private function addLog($action)
    {
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
	
	public function blockchainValid(){
		
		$blocks = Block::all();
		$prevBlock = 0;
		$error = false;
		foreach ($blocks as $block) {
			if(!$prevBlock){
				$block_header = json_decode($block->block_header, true);
				if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
					$error = true;
					break;
				}				
			}else{
				$block_header = json_decode($block->block_header, true);
				if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
					$error = true;
					break;
				}elseif(hash('sha256',$prevBlock->block_header) != $block_header["previous_block_hash"]){
					$error = true;
					break;
				}				
			}
			$prevBlock = $block;
		}
		
		return !$error;
	}
	
	public function getVotingResults(){
		
		$noOfVotes  = 0;		
		$nominees = Nominee::all();
		$nomineesVotes = array();
		$nomineeVotesCounter = 0;
		
		foreach ($nominees as $nominee){
			$idParm = '"nomineeID":"'.$nominee->id.'"';
			$choiceParm = '"choice":"true"';
			$parm = $idParm.' , '.$choiceParm;
			$nomineeVotes = Block::where('vote', 'LIKE', "%$parm%")->
									get();
									
			$nomineeVotesCounter = Block::where('vote', 'LIKE', "%$parm%")->
									get()->count();
			
			/*$prevBlock = 0;
			$error = false;
			$prevBlock = 0;
			foreach ($nomineeVotes as $nomineeVote){
				if($nomineeVote->id <= 1){
					$prevBlock = 0;
				}else{
					$prevBlock = Block::find($nomineeVote->id - 1);
				}
				
				if(!$prevBlock){
					$block_header = json_decode($nomineeVote->block_header, true);
					if(hash('sha256',$nomineeVote->vote) != $block_header["vote_hash"]){
						$error = true;
						$nomineeVotesCounter--;
						//break;
					}				
				}else{
					$block_header = json_decode($nomineeVote->block_header, true);
					if(hash('sha256',$nomineeVote->vote) != $block_header["vote_hash"]){
						$error = true;
						$nomineeVotesCounter--;
						//break;
					}elseif(hash('sha256',$prevBlock->block_header) != $block_header["previous_block_hash"]){
						$error = true;
						$nomineeVotesCounter--;
						//break;
					}				
				}
			}*/
			//$prevBlock = $nomineeVotesCounter;
			

		
			//$noOfVotes = $nomineeVotesCounter->count;
			
			//$nomineeVote = array($nominee->id,$nomineeVotesCounter);
			$nomineeVote['id'] = $nominee->id;
			$nomineeVote['name'] = $nominee->name;
			$nomineeVote['photo'] = $nominee->photo;
			$nomineeVote['type'] = $nominee->type;
			$nomineeVote['votes'] = $nomineeVotesCounter;
			
			array_push($nomineesVotes, $nomineeVote);			
		}
		
		usort($nomineesVotes, function($a, $b) {
			return $b['votes'] <=> $a['votes'];
		});
		
		
		$nomineesVotesJSON = json_encode($nomineesVotes);
		
		return $nomineesVotesJSON;

	}
	
}
