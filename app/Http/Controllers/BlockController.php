<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Block;
use Carbon\Carbon;
use App\User;
use App\Log;
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

class BlockController extends Controller
{
    //
	public function addBlock(Request $request)
    {
		$id = $request->input('id');
		
		$user = User::find($id);
		if($user->voted != 1){
			$user->voted = 1;
			$user->save();
			
			$log = new Log();
			$log->user_id = Auth::user()->id;
			$log->action = "Voting";
			$log->time = Carbon::now();
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$MAC = exec('getmac');
			$MAC = strtok($MAC, ' '); 
			$log->mac = $MAC;
			$log->save();
			
			$lastBlock = Block::latest()->first();
			
			$version = "1.0";
			if($lastBlock)
				$previous_block_hash = $lastBlock->block_hash;
			else $previous_block_hash = '0';
			$vote_hash = hash('sha256',$request->input('voteJSON'));
			$timestamp = Carbon::now()->timestamp;		
			$difficulty_target = 3;
			
			$nonce = $this->blockMining($version, 
										$previous_block_hash,
										$vote_hash,
										$timestamp,
										$difficulty_target);
			
			/*$version = $request->input('version');
			$previous_block_hash = $request->input('previous_block_hash');
			$vote_hash = $request->input('vote_hash');
			$timestamp = $request->input('timestamp');
			$difficulty_target = $request->input('difficulty_target');
			$nonce = $request->input('nonce');*/
			
			//$block_header = $request->input('block_headerJSON');
			//$block_hash = $request->input('block_hash');
			
			$block_header =    '{"version" : "'.$version.'",'.
								'"previous_block_hash": "'.$previous_block_hash.'",'.
								'"vote_hash": "'.$vote_hash.'",'.
								'"timestamp": "'.$timestamp.'",'.
								'"difficulty_target": "'.$difficulty_target.'",'.	
								'"nonce": "'.$nonce.'"'.
								'}';
								
			$block_hash = hash('sha256',$block_header);
			
			$block = new Block();		
			$block->block_size = 4;
			$block->block_header = $block_header;
			$block->vote = $request->input('voteJSON');
			$block->block_hash = $block_hash;
			$block->save();
			
			$node001 = new Node001();		
			$node001->block_size = 4;
			$node001->block_header = $block_header;
			$node001->vote = $request->input('voteJSON');
			$node001->block_hash = $block_hash;
			$node001->save();
			
			$node002 = new Node002();		
			$node002->block_size = 4;
			$node002->block_header = $block_header;
			$node002->vote = $request->input('voteJSON');
			$node002->block_hash = $block_hash;
			$node002->save();
			
			$node003 = new Node003();		
			$node003->block_size = 4;
			$node003->block_header = $block_header;
			$node003->vote = $request->input('voteJSON');
			$node003->block_hash = $block_hash;
			$node003->save();
			
			$node004 = new Node004();		
			$node004->block_size = 4;
			$node004->block_header = $block_header;
			$node004->vote = $request->input('voteJSON');
			$node004->block_hash = $block_hash;
			$node004->save();
			
			$node005 = new Node005();		
			$node005->block_size = 4;
			$node005->block_header = $block_header;
			$node005->vote = $request->input('voteJSON');
			$node005->block_hash = $block_hash;
			$node005->save();
			
			$node006 = new Node006();		
			$node006->block_size = 4;
			$node006->block_header = $block_header;
			$node006->vote = $request->input('voteJSON');
			$node006->block_hash = $block_hash;
			$node006->save();
			
			$node007 = new Node007();		
			$node007->block_size = 4;
			$node007->block_header = $block_header;
			$node007->vote = $request->input('voteJSON');
			$node007->block_hash = $block_hash;
			$node007->save();
			
			$node008 = new Node008();		
			$node008->block_size = 4;
			$node008->block_header = $block_header;
			$node008->vote = $request->input('voteJSON');
			$node008->block_hash = $block_hash;
			$node008->save();
			
			$node009 = new Node009();		
			$node009->block_size = 4;
			$node009->block_header = $block_header;
			$node009->vote = $request->input('voteJSON');
			$node009->block_hash = $block_hash;
			$node009->save();
			
			$node010 = new Node010();		
			$node010->block_size = 4;
			$node010->block_header = $block_header;
			$node010->vote = $request->input('voteJSON');
			$node010->block_hash = $block_hash;
			$node010->save();
			
			return "Block created successflly.";
		}else{
			return "Can't create block for same voter.";
		}
		
    }
	
	private function blockMining($version, 
								$previous_block_hash,
								$vote_hash,
								$timestamp,
								$difficulty_target)
    {
		
		$stratZero = '';
		for($cnt=0; $cnt < $difficulty_target; $cnt++)
			$stratZero = $stratZero.'0';
		$nonce = 0;
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
					break;
		}
		
		return $nonce;
	}
	
	public function refineBlockchain(){
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
		
		$nodesHash = array();
		$numberOfNodes = 11;
		$validHash = '';
		
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
		
		$hashCounters = array_count_values($nodesHash);
		
		foreach($hashCounters as $key => $hashCounter){
			if($hashCounter >= intval($numberOfNodes / 2 + 1) ){
				$validHash = $key;
				break;
			}
		}
		$fineNodeHashIndex = array_search($validHash, $nodesHash);		
		
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
		
		$fine = $this->blockchainValid();
		$blocks = Block::paginate(10);
			
        return view('blockchainExplorer',compact('fine','blocks'));
		
		
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
	
	
}
