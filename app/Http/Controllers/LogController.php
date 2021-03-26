<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use Auth;
use Carbon\Carbon;

class LogController extends Controller
{
    //
	public function logs($id = null){
		if($id){			
			//$logs = Log::where('user_id',$id)->orderBy('created_at', 'desc')->get();
			$logs = Log::select('*')
                        ->join('users', 'users.id', '=', 'logs.user_id')
                        ->where('logs.user_id',$id)
                        ->orderBy('logs.created_at', 'desc')
                        ->paginate(15);
		}else{
			//$logs = Log::orderBy('created_at', 'desc')->get();	
				$logs = Log::select('*','logs.id as logID')
                        ->join('users', 'users.id', '=', 'logs.user_id')
                        ->orderBy('logs.created_at', 'desc')
                        ->paginate(15);
		}        
		return view ('logs',compact('logs'));     
    }
	public function deleteLog($id = null){
		if($id){
			$log = Log::find($id);
			$this->addLog("Delete log action: ".$log->action);
			//$log->delete();
		}
		$logs = Log::all();		
		return redirect()->route('logs', '0')->with( ['logs' => $logs] );
		
    }
	public function clearLogs(){
		
		//Log::truncate();
		$this->addLog("Clear all logs");
		$logs = Log::all();		
		return redirect()->route('logs', '0')->with( ['logs' => $logs] );
		
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
