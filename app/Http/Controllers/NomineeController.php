<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use Auth;
use Carbon\Carbon;
use App\Nominee;
use App\NomineeList;
/**
 * Class NomineeController
 * @author Raed Rasheed
 * The Nominee Controller to manage Nominees
 * @package Controllers\EA
 */

class NomineeController extends Controller
{
	 /**
	 * List && Edit candidate
	 * @param $request
	 * @param $response
	 * @return mixed
	 */
	public function index(){
		$this->addLog("View all nominees");		
		$nominees = Nominee::orderBy('nominee_list_id', 'asc')
							->orderBy('name', 'asc')
							->get();		
		return view ('nominees',compact('nominees'));    
    }
	public function addEditNominee($id = null){		
		if($id){
			$nominee = Nominee::find($id);
			$nomineeLists = NomineeList::orderBy('name', 'asc')
										->get();
			return view('addEditNominee',compact('nominee','nomineeLists'));
		}else{			
			$nomineeLists = NomineeList::orderBy('name', 'asc')
										->get();
			return view('addEditNominee',compact('nomineeLists'));
		}
    }	
	public function saveNominee(Request $request){
		$this->validate($request, [
			'name' => 'required',
			'nominee_list_id' => 'required',				
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
		$nominee->nominee_list_id = $request->input('nominee_list_id');
		$nominee->is_active = ($request->input('is_active'))?true:false;
		$nominee->description = $request->input('description');
		
		$nominee->save();
		
        $nominees = Nominee::orderBy('nominee_list_id', 'asc')
							->orderBy('name', 'asc')
							->get();		
		return redirect()->route('nominees')->with( ['nominees' => $nominees] );
    }
	public function deleteNominee($id = null){
		if($id){
			$nominee = Nominee::find($id);
			
			$this->addLog("Delete nominee: ".$nominee->name);
						
			$nominee->delete();
		}		
		$nominees = Nominee::orderBy('nominee_list_id', 'asc')
							->orderBy('name', 'asc')
							->get();	
		return redirect()->route('nominees')->with( ['nominees' => $nominees] );
		
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
