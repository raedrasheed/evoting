<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Config;
		  
use App\User;
use App\Voted;
use App\Nominee;
use App\Log;
use Auth;
use Carbon\Carbon;
use App\Block;
use App\NomineeList;

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
    public function index(){
		$lists = array();
		$nomineeLists = NomineeList::where('is_active',true)
										->orderBy('name', 'asc')
										->get();
		foreach($nomineeLists as $nomineeList){	
			$nominees = Nominee::where('nominee_list_id',$nomineeList->id)
								 ->where('is_active',true)
								 ->orderBy('name', 'asc')
								 ->get();
			$lists[$nomineeList->id]= $nominees;
		}
		$lastBlock = Block::latest()->first();
		$version = "1.0";
		$previous_block_hash = '';
		if($lastBlock)
			$previous_block_hash = $lastBlock->block_hash;
		else $previous_block_hash = '0';
		$difficulty_target = 3;
		
		$voted = Voted::where('user_id',Auth::user()->id)->first();
			
		$user = User::find(Auth::user()->id);
		if($voted==null){
			$votedFlag = 0;
		}else{
			$user->voted = 1;
			$votedFlag = 1;
		}
	
		$user->save();
		
		$users = User::where('role',2);
		$allVoters = $users->count();
		$blocks = Block::all();
		$totalVotes = $blocks->count();
		if ($allVoters >0)
			$votingPrecentage = $totalVotes / $allVoters * 100;
		else $votingPrecentage = 0;
		
        return view('home',compact('lists','nomineeLists','allVoters','totalVotes','votingPrecentage','votedFlag','version','previous_block_hash', 'difficulty_target'));
    }
    public function votingDemo(){
        $this->addLog("View voting demo");
		
		$lists = array();
		$nomineeLists = NomineeList::where('is_active',true)
										->orderBy('name', 'asc')
										->get();
		foreach($nomineeLists as $nomineeList){	
			$nominees = Nominee::where('nominee_list_id',$nomineeList->id)
								 ->where('is_active',true)
								 ->orderBy('name', 'asc')
								 ->get();
			$lists[$nomineeList->id]= $nominees;
		}
        
		
        return view('votingDemo',compact('lists','nomineeLists'));
    }
	public function voteCards(){
		$this->addLog("View vote cards");
		$lists = array();
		$nomineeLists = NomineeList::where('is_active',true)
									->orderBy('name', 'asc')
									->get();
		foreach($nomineeLists as $nomineeList){	
			$nominees = Nominee::where('nominee_list_id',$nomineeList->id)
								 ->where('is_active',true)
								 ->orderBy('name', 'asc')
								 ->get();
			$lists[$nomineeList->id]= $nominees;
		}
		
		$fine = $this->blockchainValid();
		$blocks = Block::paginate(10);
		$noOfVotes = Block::all()->count();
			
        return view('voteCards',compact('fine','noOfVotes','blocks','lists','nomineeLists'));
    }
	public function results(){
		$this->addLog("View results");
		
		$nomineeLists = NomineeList::where('is_active',true)
									->orderBy('name', 'asc')
									->get();
			
		$users = User::where('role',2);
		$allVoters = $users->count();
		$blocks = Block::all();
		$totalVotes = $blocks->count();
		
		if ($allVoters >0)
			$votingPrecentage = $totalVotes / $allVoters * 100;
		else $votingPrecentage = 0;
		
		$parm = '"choice":"true"';				
		$nomineeVotesCounter = Block::where('vote', 'LIKE', "%$parm%")->
								get()->count();
								
		$blankVotes = $totalVotes - $nomineeVotesCounter;
										
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
		$correctVotes = $totalVotes - $incorrectVotes - $blankVotes;
		
        return view('results',compact('allVoters','totalVotes','correctVotes','incorrectVotes','votingPrecentage','blankVotes','nomineesVotesJSON','nomineeLists'));
    }
	public function settings(){
		$this->addLog("View settings");
				
        return view('settings');
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

			$nomineeVote['id'] = $nominee->id;
			$nomineeVote['name'] = $nominee->name;
			$nomineeVote['photo'] = $nominee->photo;
			$nomineeVote['nominee_list_id'] = $nominee->nominee_list_id;
			$nomineeVote['votes'] = $nomineeVotesCounter;
			
			array_push($nomineesVotes, $nomineeVote);			
		}
		
		usort($nomineesVotes, function($a, $b) {
			return $b['votes'] <=> $a['votes'];
		});
		
		
		$nomineesVotesJSON = json_encode($nomineesVotes);
		
		return $nomineesVotesJSON;

	}
	public function sendSMSForAll(){
		$send_sms_username = "";
		$send_sms_mobile = "";
		$vc_token_rnd = 0;
		$mobile_string = "";
		$result ="";
		
		$users = User::where('voted','0')->
				   where('role','2')->get();
	    
		$vc_token_msg = urlencode (Config::get('settings.remindMessage'));	
		foreach($users as $user){
			//$send_sms_mobile = $user->mobile;	
			if($user->mobile != "")
		    	$mobile_string = $mobile_string . '972'. $user->mobile .',';
		}
		$send_sms_mobile = rtrim($mobile_string, ", ");
		//$result = file_get_contents("http://www.hotsms.ps/sendbulksms.php?user_name=iug&user_pass=mc7MtYX7ZQ78WWrV&sender=IUG&mobile=".$send_sms_mobile."&type=2&text=".$vc_token_msg);
		//$result = file_get_contents("https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=offlinesms2020&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS");

		
		   /* $ch = curl_init();

            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, "https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=offlinesms2020&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            
            // grab URL and pass it to the browser
            curl_exec($ch);
            
            // close cURL resource, and free up system resources
            curl_close($ch);
            sleep(5);*/
		
		//}
		return $result;
		
	}
	public function sendSMSToken($username){
		$user = User::where('username',$username)->first();
		$send_sms_username = "";
		$send_sms_mobile = "";
		$vc_token_rnd = 0;
		$found = 0;
		$result ="";
		
		if($user){
			$send_sms_mobile = $user->mobile;
			$vc_token_msg = urlencode (Config::get('settings.remindMessage'));	
			/*$vc_token_rnd = mt_rand(100000, 999999);
			$vc_token_msg = $vc_token_rnd;//."%0a%0aOfflineSMS%0ahttps://cutt.ly/crV6aWo";
    		$vc_token = password_hash($vc_token_rnd, PASSWORD_BCRYPT);//mt_rand(100000, 999999);
			$user->password = $vc_token;
			$user->save();*/
			//$sms_user = urlencode ('iug-naqaba');	
			
		//	$result = file_get_contents("http://www.hotsms.ps/sendbulksms.php?user_name=iug&user_pass=mc7MtYX7ZQ78WWrV&sender=IUG&mobile=".$send_sms_mobile."&type=2&text=".$vc_token_msg);
			//$result = file_get_contents("https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=offlinesms2020&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS");

		/*
		    $ch = curl_init();

            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, "https://www.nsms.ps/api.php?comm=sendsms&user=raed.rasheed&pass=offlinesms2020&to=".$send_sms_mobile."&message=".$vc_token_msg."&sender=OfflineSMS");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            
            // grab URL and pass it to the browser
            curl_exec($ch);
            
            // close cURL resource, and free up system resources
            curl_close($ch);*/
		
		}
		
		return $result;
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
