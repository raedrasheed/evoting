@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1 || Auth::user()->role == 2)
	@php ($defualtPhoto = 'imgs/photo.jpg')
	<div class="container">		
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
							{{ __('All Voters') }}
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
					</div>
					</div>
				</div>	
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b>{{ __('Presidential Vote Results') }}</b></div>
					<div class="card-body">
						<div class="stat-holder">	
						
							@php ($votes = 0)
							@php ($votesPercentage = 0)
							@foreach (json_decode($nomineesVotesJSON, true) as $nomineeVotes)
								@if($nomineeVotes['type'] == 1)
									@php ($votes = $nomineeVotes['votes'])
									@if($totalVotes == 0)
										@php ($votesPercentage = 0)
									@else
										@php ($votesPercentage = $nomineeVotes['votes'] / $totalVotes * 100)
									@endif
									<div class="stat-bar cf" data-percent="{{ $votesPercentage }}%" data-percent-count="{{ $votes }}" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:8em;">
										<span class="stat-label" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:-11em;">	
											 <img src="{{ asset($nomineeVotes['photo']) }}" alt="Avatar" style="width:45px; height:45px;border: solid 2px #3d3d3d;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';">
											 {{ $nomineeVotes['name'] }}
										</span>
									</div>	
								@endif
							@endforeach	
						</div>
					</div>
				</div>	
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-md-12">				
				<div class="card">
					<div class="card-header"><b>{{ __('Member Vote Results') }}</b></div>
					<div class="card-body">
						<div class="stat-holder">
							@php ($votes = 0)
							@php ($votesPercentage = 0)
							
							@foreach (json_decode($nomineesVotesJSON, true) as $nomineeVotes)
								@if($nomineeVotes['type'] == 2 || $nomineeVotes['type'] == 3)
									@php ($votes = $nomineeVotes['votes'])
									@if($totalVotes == 0)
										@php ($votesPercentage = 0)
									@else
										@php ($votesPercentage = $nomineeVotes['votes'] / $totalVotes * 100)
									@endif
									<div class="stat-bar cf" data-percent="{{ $votesPercentage }}%" data-percent-count="{{ $votes }}" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:8em;">
										<span class="stat-label" style="{{(App::isLocale('ar') ? 'right' : 'left')}}:-11em;">	
											 <img src="{{ asset($nomineeVotes['photo']) }}" alt="Avatar" style="width:45px;  height:45px;border: solid 2px #3d3d3d;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';">
											 {{ $nomineeVotes['name'] }}
										</span>
									</div>	
								@endif
							@endforeach	
						</div>
					</div>
				</div>
			</div>
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
					$(this).text((Math.round(now*10)/10) + "%");
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
					$(this).text((Math.round(now*10)/10) + "%");
				  }
				}
			  );
		  });
		  
		}, 500);


	</script>
	<script>
	
	</script>
@endif
@endsection
