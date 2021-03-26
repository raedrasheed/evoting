<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Block;
use Carbon\Carbon;
use App\User;
use App\Voted;
use Config;
use App\Log;
use App\PoolOfVote;

use Auth;
use App\Node001;
use App\Node002;
use App\Node003;
use App\Node004;
use App\Node005;
use App\Node006;
use App\Node007;
use App\Node008;
use App\Node009;
use App\Node010;

/**
 * Class BlockController
 * @author Raed Rasheed
 * The Block Controller manage blockchain
**/

class BlockController extends Controller
{
     /**
     * addVote Method: 	Add the vote into voting pool 
	 *					and set user to voted user
     * @param $request
     * @return String
    **/
	public function addVote(Request $request){
		/* Get user id from the request */
		$id = $request->input('id');
		/* Search the user from DB */
		$user = User::find($id);
		/* Check if user already voted */
		$vodted = Voted::where('user_id',$id)->first();
		/* Get current timestamp */
        $now = Carbon::now();
		
		/* Returen messages */
		$resultOk = array('message' => 'Vote saved successfully!');
		$resultError = array('message' => 'Sorry, Vote not saved!');
		
		/* If user not voted before 							 */
		/*		   and strat voting time less than current time  */
		/*		   and end voting time greater than current time */
		
		if($vodted=="" && Carbon::parse(config('settings.votingStartTime'))->lt($now) && Carbon::parse(config('settings.votingEndTime'))->gt($now) ){
		
			/* To prevent user from voting twise  */
			/* we create a Blokchain for user     */
			/* voting processing to check if user */
			/* has been voted before or not       */
			
			/* Create new voted block */
			$newVoted = new Voted();
			$voteds = Voted::first();
			/* Check if this user is the first user voted*/
			if($voteds != ""){
				
				/* If user is not the first voter  */
				/* get the last voted hash and put */ 
				/* it in the new voted block       */
				
				$lastVoted = Voted::latest()->first();				
				$newVoted->previous_hash = $lastVoted->hash;
			}else{
				
				/* If user is the first voter put */
				/* ZERO in the new voted block    */
				
				$newVoted->previous_hash = "0";						
			}
			
			/* Save the user data into the voted  */
			/* Blockchain						  */
			/* user id,							  */
			/* user name, 						  */
			/* the has of current block, and 	  */
			/* the difficulty target			  */
			/* As we save the previous block hash */
			
			$newVoted->user_id = Auth::user()->id;
			$newVoted->user_name = Auth::user()->username;
			$stringToHash = $newVoted->user_id . $newVoted->user_name . Carbon::now()->timestamp . $newVoted->previous_hash;
			$newVoted->hash = hash('sha256',$stringToHash);
			$newVoted->difficulty_target = config('settings.__difficulty_target');
			$newVoted->save();
			
			/* Set the voted flag in the user table to 1 = (true) */
			$user->voted = 1;
			$user->save();
			
			/* Save the current event in the Log table */
			$this->addLog("Voting...");
			
			/* Insert the vote into the vote Pool */
			$PoolOfVote = new PoolOfVote();
			$PoolOfVote->vote = $request->input('voteJSON');
			$PoolOfVote->save();
			
			return "Vote saved successfully!";				
		}else{
			return "Sorry, Vote not saved!";
		}
	}
	/**
     * buildBlockchain Method: 	Get votes from voting pool then 
	 *							start to mine them to insting 
	 *							the votes into blockchain
     * @param None
     * @return String
    **/
	public function buildBlockchain(){
		$poolOfVote = PoolOfVote::first();
		$VoteCounter = 0;
		
		$this->addLog("Mining blocks");
		if($poolOfVote){
			/* Get the first vote in the vote Pool			*/
			while($poolOfVote = PoolOfVote::first()){
				/* Get the vote JSON */
				$this->addBlock($poolOfVote->vote);					
				$poolOfVote->delete();
				if($VoteCounter > 2) break;
				$VoteCounter++;
				sleep(config('settings.__sleepTime'));
			}
			return "Block(s) created successfully";
		}else{
			return "No votes in voting pool";
		}
	}
	/**
     * addBlock Method:	Add new block to the blockchain
	 *
     * @param __vote (JSON format of the vote detials)
     * @return integer
    **/
	public function addBlock($__vote){		
		
		
		/* Do that while there are votes in the Pool	*/
		if($__vote){			
			
			/* Create new block for the main voting Blockchain */
			$lastBlock = Block::latest()->first();
			
			/* Save the voting data into the main */
			/* Blockchain:						  */
			/* current version, 				  */
			/* previous block hash,		 	      */
			/* timestamp,  						  */
			/* difficulty target,			      */
			/* nonce, 					          */
			/* current block hash		          */
			
			$version = Config::get('settings.__version__');
			
			/* If this is not the first block get */
			/* the previous block hash else set   */
			/* the previous block to ZERO  		  */
			if($lastBlock)
				$previous_block_hash = $lastBlock->block_hash;
			else $previous_block_hash = '0';
			
			/* Get user voting (data) hash */
			$vote_hash = hash('sha256',$__vote);
			
			/* Get current timestamp */			
			$timestamp = Carbon::now()->timestamp;	
			
			/* Get the default difficulty target */			
			$difficulty_target = Config::get('settings.__difficulty_target');
			
			/* Get the the nonce */			
			$nonce = $this->blockMining($version, 
										$previous_block_hash,
										$vote_hash,
										$timestamp,
										$difficulty_target);	
								
			/* Set the current block header data as JSON format */			
			$block_header =    '{"version" : "'.$version.'",'.
								'"previous_block_hash": "'.$previous_block_hash.'",'.
								'"vote_hash": "'.$vote_hash.'",'.
								'"timestamp": "'.$timestamp.'",'.
								'"difficulty_target": "'.$difficulty_target.'",'.	
								'"nonce": "'.$nonce.'"'.
								'}';
								
			/* Get current block hash */
			$block_hash = hash('sha256',$block_header);
			
			/* Get memory usage before create the block */
			$memory_befor = memory_get_usage();
			
			$block = new Block();		
			$block->block_header = $block_header;
			$block->vote = $__vote;
			$block->block_hash = $block_hash;	
			
			/* Set block size by subtract berfore memory */
			/* usage from the current memory usage       */
			$blockSize = memory_get_usage() - $memory_befor;
			$block->block_size = $blockSize;	
			
			$block->save();
			
			/* Simulate the distributed Blockchain by   */
			/* copy the new block into distributed DBs  */
			/* there are 10 nodes not included the main */

			$node001 = new Node001();		
			$node001->block_size = $blockSize;
			$node001->block_header = $block_header;
			$node001->vote = $__vote;
			$node001->block_hash = $block_hash;
			$node001->save();
			
			$node002 = new Node002();		
			$node002->block_size = $blockSize;
			$node002->block_header = $block_header;
			$node002->vote = $__vote;
			$node002->block_hash = $block_hash;
			$node002->save();
			
			$node003 = new Node003();		
			$node003->block_size = $blockSize;
			$node003->block_header = $block_header;
			$node003->vote = $__vote;
			$node003->block_hash = $block_hash;
			$node003->save();
			
			$node004 = new Node004();		
			$node004->block_size = $blockSize;
			$node004->block_header = $block_header;
			$node004->vote = $__vote;
			$node004->block_hash = $block_hash;
			$node004->save();
			
			$node005 = new Node005();		
			$node005->block_size = $blockSize;
			$node005->block_header = $block_header;
			$node005->vote = $__vote;
			$node005->block_hash = $block_hash;
			$node005->save();
			
			$node006 = new Node006();		
			$node006->block_size = $blockSize;
			$node006->block_header = $block_header;
			$node006->vote = $__vote;
			$node006->block_hash = $block_hash;
			$node006->save();
			
			$node007 = new Node007();		
			$node007->block_size = $blockSize;
			$node007->block_header = $block_header;
			$node007->vote = $__vote;
			$node007->block_hash = $block_hash;
			$node007->save();
			
			$node008 = new Node008();		
			$node008->block_size = $blockSize;
			$node008->block_header = $block_header;
			$node008->vote = $__vote;
			$node008->block_hash = $block_hash;
			$node008->save();
			
			$node009 = new Node009();		
			$node009->block_size = $blockSize;
			$node009->block_header = $block_header;
			$node009->vote = $__vote;
			$node009->block_hash = $block_hash;
			$node009->save();
			
			$node010 = new Node010();		
			$node010->block_size = $blockSize;
			$node010->block_header = $block_header;
			$node010->vote = $__vote;
			$node010->block_hash = $block_hash;
			$node010->save();
			return 1;
		}else{
			return 0;
		}			
    }
	/**
     * blockMining Method: Search for the new block Hash
	 *
     * @param version, previous_block_hash, vote_hash, timestamp, difficulty_target
     * @return integer (the nonce)
    **/
	public function blockMining($version, 
								$previous_block_hash,
								$vote_hash,
								$timestamp,
								$difficulty_target){
		/* Set the difficulty string */
		$stratZero = '';
		for($cnt=0; $cnt < $difficulty_target; $cnt++)
			$stratZero = $stratZero.'0';
		/* Start to check the nonce */
		$nonce = 0;
		/* Loop while not reach the right nonce   */
		/* which can be add to get the right hash */
		while(1){						
			$nonce++;
			$block_header = '{"version" : "'.$version.'",'.
							'"previous_block_hash": "'.$previous_block_hash.'",'.
							'"vote_hash": "'.$vote_hash.'",'.
							'"timestamp": "'.$timestamp.'",'.
							'"difficulty_target": "'.$difficulty_target.'",'.	
							'"nonce": "'.$nonce.'"'.
							'}';
				if(starts_with(hash('sha256',$block_header), $stratZero))
				/* Break the loop when find the right nonce*/
					break;
		}
		/* return the current block nonce */
		return $nonce;
	}
	/**
     * blockchainExplorer Page: Show the blockchain as it is in the DB
	 *
     * @param None
     * @return the blocks of the blockchain to the view blockchainExplorer
    **/
	public function blockchainExplorer(){
		/* Explore the Blockchain */
		
		/* Save the current event in the Log table */
		$this->addLog("View Blockchain Explorer");
		
		/* Check the integrity of Blockchain  */
		$fine = $this->blockchainValid();

		/* Retrive the blocks from Blockchain  */
		$blocks = Block::paginate(15);
			
		/* Call the view to explore blocks from Blockchain  */
        return view('blockchainExplorer',compact('fine','blocks'));
    }
	/**
     * refineBlockchain Page:	Retrive the (50% + 1) blockchain
	 *							from the distributed DB
     * @param None
     * @return the blocks of the blockchain to the view blockchainExplorer
    **/
	public function refineBlockchain(){
		/* Check the Blockchain integrity by   */
		/* checking the hash of distributed    */
		/* nodes and choose the (50%+1) of all */
		/* nodes then redistribute the (50%+1) */
		/* Blockchain to others nodes		   */

		$blocks = Block::all();
		$node001s = Node001::all();
		$node002s = Node002::all();
		$node003s = Node003::all();
		$node004s = Node004::all();
		$node005s = Node005::all();
		$node006s = Node006::all();
		$node007s = Node007::all();
		$node008s = Node008::all();
		$node009s = Node009::all();
		$node010s = Node010::all();

		/* Array of all node hashes */
		$nodesHash = array();
		/* Get number of nodes */
		$numberOfNodes = config('settings.__number_of_nodes');;
		
		$validHash = '';
		
		/* Get nodes hashes and insert them into hash array */
		array_push($nodesHash, hash('sha256',$blocks));
		array_push($nodesHash, hash('sha256',$node001s));
		array_push($nodesHash, hash('sha256',$node002s));
		array_push($nodesHash, hash('sha256',$node003s));
		array_push($nodesHash, hash('sha256',$node004s));
		array_push($nodesHash, hash('sha256',$node005s));
		array_push($nodesHash, hash('sha256',$node006s));
		array_push($nodesHash, hash('sha256',$node007s));
		array_push($nodesHash, hash('sha256',$node008s));
		array_push($nodesHash, hash('sha256',$node009s));
		array_push($nodesHash, hash('sha256',$node010s));
		
		/* Calculate how many each hash repeated in the array */
		$hashCounters = array_count_values($nodesHash);

		/* Get the most repeated hash (valid hash) */
		foreach($hashCounters as $key => $hashCounter){
			if($hashCounter >= intval($numberOfNodes / 2 + 1) ){
				$validHash = $key;
				break;
			}
		}
		
		/* Get the index of the node with valid hash */
		$fineNodeHashIndex = array_search($validHash, $nodesHash);		
		
		/* Replicate all nodes with the node of valid hash */
		switch($fineNodeHashIndex){
			case 0:	if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Block::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Block::all()->toArray());
					}
					break;
			case 1:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node001::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node001::all()->toArray());
					}
					break;
			case 2:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node002::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node002::all()->toArray());
					}
					break;	
			case 3:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node003::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node003::all()->toArray());
					}
					break;	
			case 4:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node004::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node004::all()->toArray());
					}
					break;
			case 5:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node005::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node005::all()->toArray());
					}
					break;
			case 6:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node006::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node006::all()->toArray());
					}
					break;
			case 7:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node007::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node007::all()->toArray());
					}
					break;
			case 8:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node009s)){
						Node009::truncate();
						Node009::insert(Node008::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node008::all()->toArray());
					}
					break;
			case 9:	if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node009::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node010::truncate();
						Node010::insert(Node009::all()->toArray());
					}
					break;
			case 10:if($validHash != hash('sha256',$blocks)){
						Block::truncate();
						Block::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node001s)){
						Node001::truncate();
						Node001::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node002s)){
						Node002::truncate();
						Node002::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node003s)){
						Node003::truncate();
						Node003::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node004s)){
						Node004::truncate();
						Node004::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node005s)){
						Node005::truncate();
						Node005::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node006s)){
						Node006::truncate();
						Node006::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node007s)){
						Node007::truncate();
						Node007::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node008s)){
						Node008::truncate();
						Node008::insert(Node010::all()->toArray());
					}
					if($validHash != hash('sha256',$node010s)){
						Node009::truncate();
						Node009::insert(Node010::all()->toArray());
					}
					break;
			
		}
		
		/* Check the integrity of Blockchain  */
		$fine = $this->blockchainValid();

		/* Retrive the blocks from Blockchain  */
		$blocks = Block::paginate(15);
		
		/* Call the view to explore blocks from Blockchain  */
        return view('blockchainExplorer',compact('fine','blocks'));
		
	}
	/**
     * blockchainValid Method: Check the vote and blok validity
	 *
     * @param None
     * @return integer
    **/
	public function blockchainValid(){
		/* Check the integrity of Blockchain  */
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
	/**
     * addLog Method: Save event to log table
	 *
     * @param action
     * @return void
    **/
	public function addLog($action){
		$log = new Log();
		if($action != 'Mining blocks'){
			$log->user_id = Auth::user()->id;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$MAC = exec('getmac');
			$MAC = strtok($MAC, ' '); 
			$log->mac = $MAC;
		}else{
			$log->user_id = 1;
			$log->ip = 'Server';			
			$log->mac = 'Server';
		}
		$log->action = $action;
		$log->time = Carbon::now();
		
		$log->save();
		
	}
	
}
