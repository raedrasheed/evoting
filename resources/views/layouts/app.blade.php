<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7MLP61V0H3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-7MLP61V0H3');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" type="image/png" href="{{ asset('imgs/govotelive_logo_T.png') }}"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('app.title') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
	<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	
	<script src="{{ asset('js/sweetalert-dev.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('js/select2.min.js') }}"></script>
	
	
</head>
<body dir="{{(App::isLocale('ar') ? 'rtl' : 'ltr')}}" style="text-align:{{(App::isLocale('ar') ? 'right' : 'left')}}">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img style="width:50px;" src="{{ asset('imgs/govotelive_logo_T.png') }}"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
					<ul class="nav navbar-nav">
					</ul>
					
					<ul class="navbar-nav">
							<!-- Authentication Links -->
							 @php $locale = session()->get('locale'); @endphp

								<li class="nav-item dropdown">
									<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
										 @switch($locale)
											@case('ar')
											<img src="{{asset('imgs/ar.png')}}" width="20px" height="20x"> عربي
											@break											
											@default
											<img src="{{asset('imgs/us.png')}}" width="20px" height="20x"> English
										@endswitch
										<span class="caret"></span>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
										<a class="dropdown-item" href="{{ url('lang/en') }}"><img src="{{asset('imgs/us.png')}}" width="20px" height="20x"> English</a>
										<a class="dropdown-item" href="{{ url('lang/ar') }}"><img src="{{asset('imgs/ar.png')}}" width="20px" height="20x"> عربي</a>
										</div>
								</li>
					</ul>
					
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav {{(App::isLocale('ar') ? 'ml-auto-right' : 'ml-auto')}}">
                        <!-- Authentication Links -->
						  @guest
								<li class="nav-item">
									<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
								</li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @php ($defualtPhoto = 'imgs/photos/photo.jpg') @endphp
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img style="width:30px;border-radius: 20px;border:solid 1px #ccc;" src="{{ asset(Auth::user()->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/>
                                    {{ Auth::user()->name }}<span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="{{ route('home') }}">
                                       {{ __('Voting Page') }}
                                    </a>
									<a class="dropdown-item" href="{{ route('myProfile') }}">
                                       {{ __('My Profile') }}
                                    </a>
									@if (Auth::user()->role == 1)
									<a class="dropdown-item" href="{{ route('nomineeLists') }}">
                                       {{ __('Nominees Lists') }}
                                    </a>
									<a class="dropdown-item" href="{{ route('nominees') }}">
                                       {{ __('Nominees') }}
                                    </a>
									<a class="dropdown-item" href="{{ route('users') }}">
                                       {{ __('Users') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('usersAll') }}">
                                       {{ __('All Users') }}
                                    </a>
									 <a class="dropdown-item" href="{{ route('buildBlockchain') }}">
                                       {{ __('Mining Unmined Blocks') }} ({{ App\PoolOfVote::all()->count() }})
                                    </a>
									<a class="dropdown-item" href="{{ route('sendSMSForAll') }}">
                                       {{ __('Send SMS to non-voting users') }}
                                    </a>
									<a class="dropdown-item" href="{{ route('logs', [ 'id'=> 0 ]) }}">
                                        {{ __('Logs') }}
                                    </a>
									<a class="dropdown-item" href="{{ route('blockchainExplorer') }}">
                                        {{ __('Blockchain Explorer') }}
                                    </a>
									@endif
									@if (Auth::user()->role == 1 || (config('settings.viewVotingCards') &&
										Carbon\Carbon::parse(config('settings.votingStartTime'))->lt(Carbon\Carbon::now())))
									<a class="dropdown-item" href="{{ route('voteCards') }}">
                                        {{ __('Vote Cards') }}
                                    </a>
									@endif
									@if (Auth::user()->role == 1 || (config('settings.viewResults') &&
										Carbon\Carbon::parse(config('settings.votingStartTime'))->lt(Carbon\Carbon::now())))
									<a class="dropdown-item" href="{{ route('results') }}">
                                        {{ __('Results') }}
                                    </a>
									@endif									
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                
					
				</div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
	<script>
		function myFunction() {
		  var input, filter, table, tr, td, i, txtValue;
		  input = document.getElementById("myInput");
		  filter = input.value.toUpperCase();
		  table = document.getElementById("myTable");
		  tr = table.getElementsByTagName("tr");
		  
		  for (i = 0; i < tr.length; i++) {
			tds = tr[i].getElementsByTagName("td");
			//alert(tds.length);
			for (j = 0; j < tds.length; j++) {
				td = tds[j];
				if (td) {
				  txtValue = td.textContent || td.innerText;
				  if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
					break;
				  } else {
					tr[i].style.display = "none";					
				  }
				}  
			}				
		  }
		}
	</script>
</body>
</html>
