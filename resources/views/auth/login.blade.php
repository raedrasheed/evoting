@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
							<div class="col-md-12">
								<input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required placeholder="{{ __('User Name') }}">

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
								@if (Route::has('password.request'))
									<p align="center">
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
									</p>
                                @endif
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
    </div>
</div>
@endsection
