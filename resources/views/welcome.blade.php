@extends('layouts.app')

@section('content')   
@php ($defualtPhoto = 'imgs/photos/photo.jpg')
		<div class="container ">		
    		<div class="row justify-content-center">
    			<div class="col-md-12">
    				<div class="card">
    					<div class="card-header">
    					<b>{{ __('Welcome') }}</b>			
    					</div>
    					<div class="card-body">
							<div class="row justify-content-center">
								<p>
									 {{ __('You are free to vote on GoVote.Live for your best choice.') }} 
									 {{ __('Our eVoting system based on Blockchain technology so, no one can change you choice.') }}
									 {{ __('Current voting for football best team, player, goalkeeper, and coach.') }}
									 {{ __('Feel free to contact us') }} <a href="mailto:s@govote.live">info@govote.live</a>.
								</p>
							</div>
							<div class="row">
									@php ($now = Carbon\Carbon::now())
									<!--<div class="links">
										<a>{{ __('Thanks') }} - {{ __('GoVote Live Team') }}</a>
									</div>-->
								<p>
									<Strong><a>{{ __('Voting Time') }}</a></Strong><br/>
									<a><Strong>{{ __('From') }}:</Strong> {{ config('settings.votingStartTime') }} GTM</a><br/>
									<a><Strong>{{ __('To') }}:</Strong> {{ config('settings.votingEndTime') }} GTM</a><br/>
									<a><Strong>{{ __('Now') }}:</Strong> {{ $now }} GTM</a><br/>		
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row justify-content-center">
    			<div class="col-md-12">
    				<div class="card">
    					<div class="card-header">
    					<b>{{ __('Results Statistics') }}</b>			
    					</div>
    					<div class="card-body">
    					<div class="form-group row">						
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $allVoters }}" data-percent-count="{{ $allVoters }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('All Users') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $totalVotes }}" data-percent-count="{{ $totalVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Total Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $votingPrecentage }}" data-percent-count="{{ $votingPrecentage }}">
    							<div class="stat-percent-circle">0						 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Voting Precentage') }}
    							</div>							
    						</div>						
    					<div class="form-group row">
    					</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $correctVotes }}" data-percent-count="{{ $correctVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Correct Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $incorrectVotes }}" data-percent-count="{{ $incorrectVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Incorrect Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $blankVotes }}" data-percent-count="{{ $blankVotes }}">
    							<div class="stat-count-circle">0						 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Blank Votes') }}
    							</div>							
    						</div>
    					</div>
    					</div>
    				</div>	
    			</div>
    		</div>
			@if(!config('settings.resultForEachList'))
				<div class="row justify-content-center">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header"><b>{{ __('Vote Results') }}</b></div>
							<div class="card-body">
								<div class="stat-holder">	
									@php ($votes = 0)
									@php ($votesPercentage = 0)
									@foreach (json_decode($nomineesVotesJSON, true) as $nomineeVotes)    								
											@php ($votes = $nomineeVotes['votes'])
											@if($totalVotes == 0)
												@php ($votesPercentage = 0)
											@else
												@php ($votesPercentage = $nomineeVotes['votes'] / $totalVotes * 100 + 5)
											@endif
											<span class="stat-bar-name">{{ $nomineeVotes['name'] }}</span>
											<div class="stat-bar cf" data-percent="{{ $votesPercentage }}%" data-percent-count="{{ $votes }}" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:3em;">
												<span class="stat-label" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:-4em;">	
													 <img src="{{ asset($nomineeVotes['photo']) }}" alt="Avatar" style="width:45px;  height:45px;border: solid 0px #3d3d3d;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';">
												</span>
											</div>
									@endforeach	
								</div>
							</div>
						</div>	
					</div>
				</div>
			@else
				 
				<div class="row justify-content-center">
				@foreach ($nomineeLists as $nomineeList)
					<div class="col-md-6">
						<div class="card">
							<div class="card-header"><b>{{ __($nomineeList->name) }}</b></div>
							<div class="card-body">
								<div class="stat-holder">	
									@php ($votes = 0)
									@php ($votesPercentage = 0)
									@foreach (json_decode($nomineesVotesJSON, true) as $nomineeVotes)    
										@if($nomineeVotes['nominee_list_id'] == $nomineeList->id)
											@php ($votes = $nomineeVotes['votes'])
											@if($totalVotes == 0)
												@php ($votesPercentage = 0)
											@else
												@php ($votesPercentage = $nomineeVotes['votes'] / $totalVotes * 100 + 5)
											@endif
											<span class="stat-bar-name">{{ $nomineeVotes['name'] }}</span>
											<div class="stat-bar cf" data-percent="{{ $votesPercentage }}%" data-percent-count="{{ $votes }}" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:3em;">
												<span class="stat-label" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:-4em;">	
													 <img src="{{ asset($nomineeVotes['photo']) }}" alt="Avatar" style="width:45px;  height:45px;border: solid 0px #3d3d3d;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';">
												</span>
											</div>
										@endif
									@endforeach	
								</div>
							</div>
						</div>	
					</div>
				@endforeach	
				</div>
				
			@endif
			<div class="row justify-content-center">
				<div class="links" style="text-align:center">
					
					<a  style="color:#F00;">{{ __('This System Based on Blockchain Technology') }}</a><br/>
					<a>{{ __('Copyright ©2021 GoVote.Live. All rights reserved') }}</a><br/>
				</div>
			</div>
    	<script>
    		setTimeout(function start() {
    		  $(".stat-bar").each(function (i) {
    			var $bar = $(this);
    			$(this).append('<span class="stat-count" style="{{(App::isLocale("ar") ? "left" : "right")}}:0.25em"></span>');
    			$(this).append('<span class="stat-count-pers" style="{{(App::isLocale("ar") ? "left" : "right")}}:-3.2em"></span>');
    			setTimeout(function () {
    			  $bar.css("width", $bar.attr("data-percent"));
    			}, i * 100);
    		  });
    
    		  $(".stat-count").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-bar").attr("data-percent-count")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text(Math.ceil(now));
    				  }
    				}
    			  );
    		  });
    		  
    		  $(".stat-count-pers").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-bar").attr("data-percent")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
						  if(now > 0) now = now-5;
    					$(this).text((Math.round((now)*10)/10) + "%");
    				  }
    				}
    			  );
    		  });
    		  
    		  $(".stat-count-circle").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-circle").attr("data-count")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text(Math.ceil(now));
    				  }
    				}
    			  );
    		  });
    		  
    		   $(".stat-percent-circle").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-circle").attr("data-percent-count")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text((Math.round((now)*10)/10) + "%");
    				  }
    				}
    			  );
    		  });
    		  
    		}, 500);
    </script>		
@endsection
