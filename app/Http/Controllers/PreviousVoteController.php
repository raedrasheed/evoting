<?php

namespace App\Http\Controllers;

use App\PreviousVote;
use Illuminate\Http\Request;

use Config;

use App\Log;
use Auth;
use Carbon\Carbon;
use App\Nominee;
use App\Block;
use App\NomineeList;
use App\User;
use App\PreviousVoteList;




class PreviousVoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$previousVotes = PreviousVote::orderBy('start_date')->paginate(20);
        return view('archiveVotes',compact('previousVotes'));
    }
	
	public function previousVotes()
    {
		$previousVotes = PreviousVote::orderBy('start_date')->paginate(20);
        return view('previousVotes',compact('previousVotes'));
    }
	
	public function resultsPreviousVote($id)
    {
        if($id){
			$previousVote = PreviousVote::find($id);
			
			$allVoters = $previousVote->all_voters;
			$totalVotes = $previousVote->total_votes;
			$correctVotes = $previousVote->correct_votes;
			$incorrectVotes = $previousVote->incorrect_votes;
			$votingPrecentage = $previousVote->voting_precentage;
			$blankVotes = $previousVote->blank_votes;
			$nomineesVotesJSON = $previousVote->nominees_votes_JSON;
			
			$previousVoteLists = PreviousVoteList::where('previous_vote_id',$id)
									->pluck('nominee_list_id')->toArray();
			
			$nomineeLists = NomineeList::whereIn('id',$previousVoteLists)
										->orderBy('name', 'asc')
										->get();
			
			return view('resultsPreviousVote',compact('allVoters'
										,'totalVotes'
										,'correctVotes'
										,'incorrectVotes'
										,'votingPrecentage'
										,'blankVotes'
										,'nomineesVotesJSON'
										,'nomineeLists'));   
		}else{	
			return view('previousVotes');
		}		
		
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        if($id){
			$previousVote = PreviousVote::find($id);			
			return view('addEditArchiveVote',compact('previousVote'));
		}else{	
			return view('addEditArchiveVote');
		}
	
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->addLog("Archive vote: ".$request->title);
		
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
		
		$previousVote = new PreviousVote();
		$previousVote->title = $request->title;
		$previousVote->start_date = Config::get('settings.votingStartTime');
		$previousVote->end_date = Config::get('settings.votingEndTime');
		$previousVote->all_voters = $allVoters;
		$previousVote->total_votes = $totalVotes;
		$previousVote->correct_votes = $correctVotes;
		$previousVote->incorrect_votes = $incorrectVotes;
		$previousVote->voting_precentage = $votingPrecentage;
		$previousVote->blank_votes = $blankVotes;
		$previousVote->nominees_votes_JSON = $nomineesVotesJSON;
		
		$previousVote->save();
		
		$lastPreviousVote = PreviousVote::orderBy('id', 'desc')
							->first();
		
		foreach($nomineeLists as $nomineeList){
			$previousVoteList = new PreviousVoteList();
			$previousVoteList->previous_vote_id = $lastPreviousVote->id;
			$previousVoteList->nominee_list_id = $nomineeList->id;
			$previousVoteList->name = $nomineeList->name;
			$previousVoteList->photo = $nomineeList->photo;
			$previousVoteList->description = $nomineeList->description;
			$previousVoteList->save();
		}
		
		$previousVotes = PreviousVote::orderBy('start_date')->paginate(20);
        return view('archiveVotes',compact('previousVotes'));
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PreviousVote  $previousVote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id){
			$previousVote = PreviousVote::find($id);
			
			$allVoters = $previousVote->all_voters;
			$totalVotes = $previousVote->total_votes;
			$correctVotes = $previousVote->correct_votes;
			$incorrectVotes = $previousVote->incorrect_votes;
			$votingPrecentage = $previousVote->voting_precentage;
			$blankVotes = $previousVote->blank_votes;
			$nomineesVotesJSON = $previousVote->nominees_votes_JSON;
			
			$previousVoteLists = PreviousVoteList::where('previous_vote_id',$id)
									->pluck('nominee_list_id')->toArray();
			
			$nomineeLists = NomineeList::whereIn('id',$previousVoteLists)
										->orderBy('name', 'asc')
										->get();
			
			return view('results',compact('allVoters'
										,'totalVotes'
										,'correctVotes'
										,'incorrectVotes'
										,'votingPrecentage'
										,'blankVotes'
										,'nomineesVotesJSON'
										,'nomineeLists'));   
		}else{	
			return view('archiveVotes');
		}		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreviousVote  $previousVote
     * @return \Illuminate\Http\Response
     */
    public function edit(PreviousVote $previousVote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreviousVote  $previousVote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreviousVote $previousVote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreviousVote  $previousVote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null){
		if($id){
			$previousVote = PreviousVote::find($id);
			$this->addLog("Delete previous vote: ".$previousVote->title);
			$previousVote->delete();
			PreviousVoteList::where('previous_vote_id',$id)
								->delete();
		}
		$previousVotes = PreviousVote::orderBy('title')->paginate(20);		
		return redirect()->route('archiveVotes')->with( ['previousVotes' => $previousVotes] );
		
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
