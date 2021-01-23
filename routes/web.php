<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
		  
use App\User;
use App\Voted;
use App\Nominee;
use App\Log;
use Carbon\Carbon;
use App\Block;
use App\NomineeList;

Route::get('/', function () {
		
		$nomineeLists = NomineeList::where('is_active',true)
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
										
		//$nomineesVotesJSON = $this->getVotingResults();
		
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
    return view('welcome',compact('allVoters','totalVotes','correctVotes','incorrectVotes','votingPrecentage','blankVotes','nomineesVotesJSON','nomineeLists'));
});

Route::get('/history', function () {
	return view('history');
});

//Auth::routes();
Auth::routes([
	'register' => true, // Registration Routes...
	'reset' => true, 	// Password Reset Routes...
	'verify' => true,	// Email Verification Routes...
]);

Route::get('profile', function () {
    return view('profile');
})->middleware('verified');

Route::get('/results', 'HomeController@results')->name('results')->middleware('verified');
Route::get('/voteCards', 'HomeController@voteCards')->name('voteCards')->middleware('verified');

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
Route::get('/votingDemo', 'HomeController@votingDemo')->name('votingDemo')->middleware('verified');

Route::get('/logs/{id}', 'LogController@logs')->name('logs')->middleware('verified');
Route::get('/deleteLog/{id}', 'LogController@deleteLog')->name('deleteLog')->middleware('verified');
Route::get('/clearLogs', 'LogController@clearLogs')->name('clearLogs')->middleware('verified');

//Route::get('/settings', 'HomeController@settings')->name('settings')->middleware('verified');
//Route::get('/editSettings', 'HomeController@editSettings')->name('editSettings')->middleware('verified');

Route::get('/nominees', 'NomineeController@index')->name('nominees')->middleware('verified');
Route::get('/addEditNominee/{id}', 'NomineeController@addEditNominee')->name('addEditNominee')->middleware('verified');
Route::get('/deleteNominee/{id}', 'NomineeController@deleteNominee')->name('deleteNominee')->middleware('verified');
Route::post('/saveNominee', 'NomineeController@saveNominee')->name('saveNominee')->middleware('verified');

Route::get('/nomineeLists', 'NomineeListController@nomineeLists')->name('nomineeLists')->middleware('verified');
Route::get('/addEditNomineeList/{id}', 'NomineeListController@addEditNomineeList')->name('addEditNomineeList')->middleware('verified');
Route::get('/deleteNomineeList/{id}', 'NomineeListController@deleteNomineeList')->name('deleteNomineeList')->middleware('verified');
Route::post('/saveNomineeList', 'NomineeListController@saveNomineeList')->name('saveNomineeList')->middleware('verified');

Route::get('/users', 'UserController@users')->name('users')->middleware('verified');
Route::get('/usersAll', 'UserController@usersAll')->name('usersAll')->middleware('verified');
Route::get('/addEditUser/{id}', 'UserController@addEditUser')->name('addEditUser')->middleware('verified');
Route::get('/deleteUser/{id}', 'UserController@deleteUser')->name('deleteUser')->middleware('verified');
Route::post('/saveUser', 'UserController@saveUser')->name('saveUser')->middleware('verified');

Route::post('/addVote', 'BlockController@addVote')->name('addVote')->middleware('verified');

Route::get('/buildBlockchain', 'BlockController@buildBlockchain')->name('buildBlockchain')->middleware('verified');
Route::get('/blockchainExplorer', 'BlockController@blockchainExplorer')->name('blockchainExplorer')->middleware('verified');
Route::get('/refineBlockchain', 'BlockController@refineBlockchain')->name('refineBlockchain')->middleware('verified');

Route::get('/lang/{locale}', 'LocalizationController@index')->middleware('verified');

Route::get('/sendSMSForAll', 'HomeController@sendSMSForAll')->name('sendSMSForAll')->middleware('verified');
Route::get('/sendSMSToken/{username}', 'HomeController@sendSMSToken')->name('sendSMSToken')->middleware('verified');
