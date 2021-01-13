@extends('layouts.app')

@section('content')
@php ($now = Carbon\Carbon::now())
@php ($now->addHours(2))
@php ( $choiceElement = 'imgs/unchecked.png' )
@php ($defualtPhoto = 'imgs/photos/photo.jpg')

@if (Carbon\Carbon::parse(config('settings.votingEndTime'))->lt($now))
    @if (Auth::user()->role == 1 || (Auth::user()->role == 2 && config('settings.viewResults')))
    	<div class="container">		
    		<div class="row justify-content-center">
    			<div class="col-md-12">
    				<div class="card">
    					<div class="card-header">
    					<b>{{ __('Voting Cards') }}: {{ $noOfVotes }} {{ __('Cards') }}</b>
    						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
    							{{ __('Blockchain Status') }}: 
    							
    								@if ( $fine )<span style="color:#38c172;font-weight:600;text-decoration:none;"> {{ __('Fine') }}</span>
    								@else <span style="color:#e74c3c;font-weight:600;text-decoration:none;"> {{ __('Error') }}</span>
    								@endif
    							
    						</div>
    					</div>
						<div class="card-body">	
						<?php $prevBlock=0; ?>
						@foreach ($blocks as $block)
						<div class="row justify-content-center">
						<div class="col-md-12">
						
						<?php 
							if(!$prevBlock){
								$block_header = json_decode($block->block_header, true);
								if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
									echo "<div class='card' style='background-color:rgb(255 172 163);'>";										
								}else{
									echo "<div class='card' style='background-color:#EEE;'>";
								}										
							}else{
								$block_header = json_decode($block->block_header, true);
								if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
									echo "<div class='card' style='background-color:rgb(255 172 163);'>";
								}elseif(hash('sha256',$prevBlock->block_header) != $block_header["previous_block_hash"]){
									echo "<div class='card' style='background-color:rgb(255 172 163);'>";
								}else{
									echo "<div class='card' style='background-color:#EEE;'>";
								}										
							}
							$prevBlock = $block;
						?>
						
						
    						<div class="card-header" style="background-color:#666;color:#FFF">
								<h5>
									<b>{{ __('Card #') }}:[{{ $block->id }}]</b>
								</h5>
							</div>
						<div class="card-body">	
						
							<div class="row justify-content-center">
								@foreach ($lists as $key=>$list)
									@if($list->count()>0)
										<div class="col-md-{{config('settings.listsInRawWidthInVoteCards')}} top-padding-15">
											<div class="card"  style="background:none;">
												<div class="card-header">
													@foreach ($nomineeLists as $nomineeList)
														@if($nomineeList->id == $key)
															<b>{{ __($nomineeList->name) }}	</b>
															@if(config('settings.viewListImageInVoteCards'))
															<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
																<img class="nominee-photo" src="{{ asset($nomineeList->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/>
															</div>
															@endif
															@break
														@endif
													@endforeach
												</div>
												<div class="card-body"  style="background:none;">
													@foreach ($list as $Nominee)
														
														@php ( $nomineeID = '' )
    													@php ( $choice = '' )
    													@php ( $choiceElement = 'imgs/unchecked.png' )
    													@foreach (json_decode($block->vote, true) as $voteCard)
    														@foreach ($voteCard as $vCard)									
    															@if (number_format($vCard['nomineeID']) == $Nominee->id )									
    																@php ( $nomineeID = $vCard['nomineeID'] )
    																@php ( $choice = $vCard['choice'] )
    																@break
    															@endif
    														@endforeach
    													@endforeach
    													@if ($choice == 'true') 
    														@php ( $choiceElement = 'imgs/checked.png' )
    													@endif		
														
														<div class="raw" style="padding-top:5px;">
															<img src="{{ asset($choiceElement) }}" style="width:25px;" />
															<span id="span{{ $Nominee->id }}" >{{ $Nominee->name }}</span>						
														</div>
													@endforeach	
												</div>
											</div>
										</div>
									@endif
								@endforeach
							</div>
		
						</div>
						</div>
						</div>
						</div>
						@endforeach
						<p>
						   {{ $blocks->links() }}
						</p>
						</div>
    				</div>
    			</div>
    		</div>	
    	</div>
    @else
		<div class="container">		
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">{{ __('Notes') }}</div>
						<div class="card-body">
							<h4>@lang('Welcome') {{ Auth::user()->name }}</h4>
							{{ __('You not allowed') }}
							 <div class="links">
								<p>
									<a>{{ __('Voting Time') }}</a>:<br/>
									<a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
									<a>{{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
									<a>{{ __('Now') }}: {{ $now }}</a>
								</p>
							</div>
							 <p>{{ __('Election committee') }} - {{ __('Islamic University - Gaza') }}</p>
						</div>
					</div>
				</div>
			</div>	
		</div>	
    @endif
@else
    <div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Notes') }}</div>
					<div class="card-body">
					    <h4>@lang('Welcome') {{ Auth::user()->name }}</h4>
						{{ __('Vote not finished') }}
						 <div class="links">
							<p>
    							<a>{{ __('Voting Time') }}</a>:<br/>
    							<a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
    							<a>{{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
    							<a>{{ __('Now') }}: {{ $now }}</a>
							</p>
						</div>
						 <p>{{ __('Election committee') }} - {{ __('Islamic University - Gaza') }}</p>
					</div>
				</div>
			</div>
		</div>	
	</div>
@endif
@endsection
