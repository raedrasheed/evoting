<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use Auth;
use Carbon\Carbon;
use App\NomineeList;


class NomineeListController extends Controller
{
    //
	public function nomineeLists(){
		$this->addLog("View all nominees lists");
		
		$nomineeLists = NomineeList::orderBy('name')->					
										get();		
		return view ('nomineeLists',compact('nomineeLists'));    
    }
	public function addEditNomineeList($id = null){		
		if($id){
			$nomineeList = NomineeList::find($id);
			return view('addEditNomineeList',compact('nomineeList'));
		}else{			
			return view('addEditNomineeList');
		}
    }
	public function saveNomineeList(Request $request){
		$this->validate($request, [
			'name' => 'required',					
		]);
		$photo = '';
		
		if($request->input('id')){
			
			$nomineeList = NomineeList::find($request->input('id'));
			$photo = $nomineeList->photo;
			
			$this->addLog("Edit nominee list: ".$request->input('name'));
						
		}
		else{
			$this->addLog("Add new nominee list: ".$request->input('name'));
						
			$nomineeList = new NomineeList();
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
		$nomineeList->name = $request->input('name');		
		$nomineeList->photo = $photo;		
		$nomineeList->selected_count = $request->input('selected_count');		
		$nomineeList->is_active = ($request->input('is_active'))?true:false;
		$nomineeList->description = $request->input('description');
		
		$nomineeList->save();
		
        $nomineeLists = NomineeList::orderBy('name')->					
										get();		
		return redirect()->route('nomineeLists')->with( ['nomineeLists' => $nomineeLists] );
    }
	public function deleteNomineeList($id = null){
		if($id){
			$nomineeList = NomineeList::find($id);
			
			$this->addLog("Delete nominee list: ".$nomineeList->name);
						
			$nomineeList->delete();
		}		
		$nomineeLists = NomineeList::orderBy('name')->					
									get();	
		return redirect()->route('nomineeLists')->with( ['nomineeLists' => $nomineeLists] );
		
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
