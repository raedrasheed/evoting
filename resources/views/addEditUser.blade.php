@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Add/Edit User') }}</b></div>
					<div class="card-body">
						<form method="POST" action="{{ route('saveUser') }}" enctype="multipart/form-data">
                        @csrf
							<input type="hidden" id="id" name="id" value="@if(isset($user)){{ $user->id }}@else{{ __('0') }}@endif"/>
							<div class="form-group row">
								<label for="outer_id" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Outer ID') }}</label>

								<div class="col-md-8">
									<input id="outer_id" type="text" class="form-control @error('outer_id') is-invalid @enderror" name="outer_id" value="@if(isset($user)){{ $user->outer_id }}@else{{ old('outer_id') }}@endif" required autocomplete="name" autofocus>

									@error('outer_id')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="name" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Name') }}</label>

								<div class="col-md-8">
									<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="@if(isset($user)){{ $user->name }}@else{{ old('name') }}@endif" required autocomplete="name" autofocus>

									@error('name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							 <div class="form-group row">
								<label for="photo" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Photo') }}</label>

								<div class="col-md-8">
									<label for="photo"><img id="photoImg" src="@if(isset($user)){{ asset($user->photo) }}@else{{ old('photo') }}@endif" style="width:100px;height:100px; border: solid 2px #000;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';" /></label>
									<input type="file" style="display:none;" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" name="photo" value="@if(isset($user)){{ $user->photo }}@else{{ old('photo') }}@endif" onChange="loadImage(this);">
									@error('photo')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="username" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Username') }}</label>

								<div class="col-md-8">
									<input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="@if(isset($user)){{ $user->username }}@else{{ old('username') }}@endif" required autocomplete="username" autofocus>

									@error('username')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="email" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('E-Mail') }}</label>

								<div class="col-md-8">
									<input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="@if(isset($user)){{ $user->email }}@else{{ old('email') }}@endif" required autocomplete="email" autofocus>

									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="password" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Password') }}</label>

								<div class="col-md-8">
									<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="@if(isset($user)){{ $user->password }}@else{{ old('password') }}@endif" required autocomplete="password" autofocus>

									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="role" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('User Role') }}</label>

								<div class="col-md-8">
									<select id="role" class="js-example-basic-single form-control @error('role') is-invalid @enderror" name="role" value="@if(isset($user)){{ $user->role }}@else{{ old('role') }}@endif" required autocomplete="role" autofocus>
										<option value="0">{{ __('Chose Option') }}...</option>
										<option value="1" @if(isset($user)) @if($user->role == 1) {{ _('selected') }} @endif @else {{ old('role') }}@endif >{{ __('Administrator') }}</option>
										<option value="2" @if(isset($user)) @if($user->role == 2) {{ _('selected') }} @endif @else {{ old('role') }}@endif >{{ __('Regular') }}</option>
									</select>
									@error('role')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="is_active" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Active') }}</label>
								<div class="col-md-8">
									<input id="is_active" type="checkbox" class="form-control @error('is_active') is-invalid @enderror" name="is_active" @if(isset($user))@if($user->is_active) {{ _('checked') }} @endif @endif  autocomplete="is_active" autofocus>
									@error('is_active')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="description" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Description') }}</label>

								<div class="col-md-8">
									<input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="@if(isset($user)){{ $user->description }}@else{{ old('description') }}@endif" required autocomplete="description" autofocus>

									@error('description')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="mobile" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Mobile') }}</label>

								<div class="col-md-8">
									<input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="@if(isset($user)){{ $user->mobile }}@else{{ old('mobile') }}@endif" required autocomplete="description" autofocus>

									@error('mobile')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							
							<div class="form-group row mb-0">
								<div class="col-md-6">
									<button type="submit" class="btn btn-primary btn-block">
										{{ __('Save') }}
									</button>
								</div>
								<div class="col-md-6">
									<a class="btn btn-secondary btn-block" href="{{ route('users') }}">
										{{ __('Cancel') }}
									</a>
								</div>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>	
	</div>
	<script type="text/javascript">
		function loadImage(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#photoImg')
						.attr('src', e.target.result)
						.width(96)
						.height(96);
				};

				reader.readAsDataURL(input.files[0]);
			}
		}
		
	</script>
@endif
@endsection
