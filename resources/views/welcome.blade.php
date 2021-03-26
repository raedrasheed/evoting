@extends('layouts.app')

@section('content')   
@php ($defualtPhoto = 'imgs/photos/photo.jpg')
		<div class="container h-100">		
    		<div class="row align-items-center h-100">
    			<div class="col-md-4 mx-auto">
    				<div class="card">    					
    					<div class="card-body">
							<form method="POST" action="{{ route('login') }}">
								@csrf

								<div class="form-group row">
									<div class="col-md-12">
										<input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required placeholder="{{ __('Username') }}">

										@if ($errors->has('username'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('username') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group row">
									<div class="col-md-12">
										<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

										@error('password')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
									</div>
								</div>						
								<div class="form-group row mb-0">
									<div class="col-md-12">
										<button type="submit" class="btn btn-primary btn-block">
											{{ __('Login') }}
										</button>                                
									</div>
								</div>
								<br/>
								<div class="form-group row mb-0">
									<div class="col-md-12">
										<div class="form-group">									
											<a href="{{url('/redirect')}}" class="btn btn-primary btn-block"><img src="{{ asset('imgs/facebook.png') }}" width="16px"> {{ __('Login with Facebook') }}</a>
										</div>
									</div>
								</div>
								<div class="form-group row mb-0">
									<div class="col-md-12">
										<div class="form-group">									
											@if (Route::has('password.request'))
												<p align="center">
												<a class="btn btn-link" href="{{ route('password.request') }}">
													{{ __('Forgot Your Password?') }}
												</a>
												</p>
											@endif
										</div>
									</div>
								</div>
										
							</form>
								<hr/>
								@if (Route::has('register'))
									<div class="form-group row mb-0">
										<div class="col-md-12">
											<div class="form-group">									
												<a class="btn btn-success btn-block" href="{{ route('register') }}">{{ __('Register') }}</a>
											</div>
										</div>
									</div>									
								@endif
						</div>
					</div>
				</div>	
				<div class="col-md-8 mx-auto">
    				<div class="card">
    					<div class="card-header">
    					<b>{{ __('Results Statistics') }}</b>			
    					</div>
    					<div class="card-body">
						<div class="form-group row mb-0">
							<div class="col-md-12">
								<p>
								{{ __('We are conducting an opinion poll on the popularity of the electoral lists that will participate in the palestinian elections.') }}c
								</p>
								<p>
								{{ __('Feel free to contact us') }} <a href="mailto:info@govote.live">info@govote.live</a>
								</p>
							</div>
						</div>	
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
				
				<div class="col-md-12 mx-auto">
					<div class="links" style="text-align:center">
						<br />
						<br />
						<br />
						<br />
						<a  style="color:#fff;">{{ __('This System Based on Blockchain Technology') }}</a><br/>
						<a>{{ __('Copyright ©2021 GoVote.Live. All rights reserved') }}</a><br/>
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
