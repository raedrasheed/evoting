@extends('layouts.app')

@section('content')

	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			@php ($cnt=1)
			@foreach ($previousVotes as $previousVote)	
			<div class="col-md-6">
				<div class="card">
					<div class="card-header"><b>{{ __($previousVote->title) }}<br>[{{ $previousVote->start_date }}] {{__('To')}} [{{ $previousVote->end_date }}]</b>
						<div class="{{(App::isLocale('ar') || App::isLocale('he') ? 'to-left' : 'to-right')}}">
							<a href="{{ route('resultsPreviousVote', [ 'id'=> $previousVote->id ]) }}" ><img class="m-icon" src="imgs/statistics.png" title="{{ __('Statistics') }}"/></a>
						</div>
					</div>
					<div class="card-body data-table-div">
						<div class="form-group row">
							<div class="stat-circle stat-main col-md-4" data-count="{{ $previousVote->all_voters }}" data-percent-count="{{ $previousVote->all_voters }}">
								<div class="stat-count-circle">0							 
								</div>
								<div class="stat-main-container">
								{{ __('All Users') }}
								</div>							
							</div>
							<div class="stat-circle stat-main col-md-4" data-count="{{ $previousVote->total_votes }}" data-percent-count="{{ $previousVote->total_votes }}">
								<div class="stat-count-circle">0							 
								</div>
								<div class="stat-main-container">
									{{ __('Total Votes') }}
								</div>							
							</div>
							<div class="stat-circle stat-main col-md-4" data-count="{{ $previousVote->voting_precentage }}" data-percent-count="{{ $previousVote->voting_precentage }}">
							<div class="stat-percent-circle">0						 
							</div>
							<div class="stat-main-container">
							{{ __('Voting Precentage') }}
							</div>							
						</div>						
    					<div class="form-group row">
    					</div>
							<div class="stat-circle stat-main col-md-4" data-count="{{ $previousVote->correct_votes }}" data-percent-count="{{ $previousVote->correct_votes }}">
								<div class="stat-count-circle">0							 
								</div>
								<div class="stat-main-container">
								{{ __('Correct Votes') }}
								</div>							
							</div>
							<div class="stat-circle stat-main col-md-4" data-count="{{ $previousVote->incorrect_votes }}" data-percent-count="{{ $previousVote->incorrect_votes }}">
								<div class="stat-count-circle">0							 
								</div>
								<div class="stat-main-container">
								{{ __('Incorrect Votes') }}
								</div>							
							</div>
							<div class="stat-circle stat-main col-md-4" data-count="{{ $previousVote->blank_votes }}" data-percent-count="{{ $previousVote->blank_votes }}">
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
				@php ($cnt++)
			@endforeach	
			<div class="col-md-12">
				
					{{ $previousVotes->links() }}
				
			</div>
		</div>	
	</div>
	<script>
		setTimeout(function start() {
		  $(".stat-bar").each(function (i) {
			var $bar = $(this);
			$(this).append('<span class="stat-count" style="{{(App::isLocale("ar") || App::isLocale("he")  ? "left" : "right")}}:0.25em"></span>');
			$(this).append('<span class="stat-count-pers" style="{{(App::isLocale("ar") || App::isLocale("he")  ? "left" : "right")}}:-3.2em"></span>');
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
					$(this).text((Math.round((now-5)*10)/10) + "%");
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
	<script>
	
	</script>

@endsection
