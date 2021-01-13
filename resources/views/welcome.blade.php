<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        	<link rel="icon" type="image/png" href="imgs/govotelive_logo_T.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ trans('app.title') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
			@font-face {
			  font-family: siteFont;
			  src: url({{ asset("css/fonts/DroidKufi-Regular.ttf") }});
			}
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: siteFont, sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 42px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 16px;
                font-weight: 600;
                /*letter-spacing: .1rem;*/
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">    
            <div class="content">
				 <div class="title m-b-md">
                    <img src="{{ asset('imgs/govotelive_logo_T.png') }}" width="60%">
                </div>
                <!--<div class="title m-b-md">
                    {{ trans('app.title') }}
                </div>
                -->
				@if (Route::has('login'))
					<div class="">
						@auth
							<a class="btn btn-primary btn-block" style="font-size:24px;margin:20px;width:auto;" href="{{ url('/home') }}">{{ __('Home') }}</a>
						@else
							<a class="btn btn-primary btn-block" style="font-size:24px;margin:20px;width:auto;" href="{{ route('login') }}"> {{ __('Login') }} </a>
							@if (Route::has('register'))
								<a href="{{ route('register') }}">{{ __('Register') }}</a>
							@endif
						@endauth
					</div>
				@endif
				@php ($now = Carbon\Carbon::now())
				@php ($now->addHours(2))
                <!--<div class="links">
                    <a>{{ __('Election committee') }} - {{ __('Islamic University - Gaza') }}</a>
                </div>-->
				<div class="links">
                    <a>{{ __('Voting Time') }}</a>
                </div>			
				<div class="links">
                    <a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
                    <a> {{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
					<a>{{ __('Now') }}: {{ $now }}</a>
				</div>
					<div class="links">
					    <p><br/></p>
                    <a  style="color:#F00;">{{ __('This System Based on Blockchain Technology') }}</a><br/>
                </div>
            </div>
        </div>
    </body>
</html>
