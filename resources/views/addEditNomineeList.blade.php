@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Add/Edit Nominee List') }}<bb></div>
					<div class="card-body">
						<form method="POST" action="{{ route('saveNomineeList') }}" enctype="multipart/form-data">
                        @csrf
							<input type="hidden" id="id" name="id" value="@if(isset($nomineeList)){{ $nomineeList->id }}@else{{ __('0') }}@endif"/>
							
							<div class="form-group row">
								<label for="name" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Name') }}</label>

								<div class="col-md-8">
									<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="@if(isset($nomineeList)){{ $nomineeList->name }}@else{{ old('name') }}@endif" required autocomplete="name" autofocus>

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
									<label for="photo"> 
										<img id="photoImg"  src="@if(isset($nomineeList)){{ asset($nomineeList->photo) }}@else{{ old('photo') }}@endif" style="width:100px;height:100px; border: solid 2px #000;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';" />
									</label>
									<input type="file" style="display:none;" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" name="photo" value="@if(isset($nomineeList)){{ $nomineeList->photo }}@else{{ old('photo') }}@endif" onChange="loadImage(this);">
									@error('photo')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>	
							<div class="form-group row">
								<label for="selected_count" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Count') }}</label>
								<div class="col-md-8">
									<input id="selected_count" type="number" class="form-control @error('selected_count') is-invalid @enderror" name="selected_count" min="1" value="@if(isset($nomineeList)){{ $nomineeList->selected_count }}@else{{ old('selected_count') }}@endif" required autocomplete="selected_count" autofocus>

									@error('selected_count')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="is_active" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Active') }}</label>

								<div class="col-md-8">
									<input id="is_active" type="checkbox" class="form-control @error('is_active') is-invalid @enderror" name="is_active" @if(isset($nomineeList))@if($nomineeList->is_active) {{ _('checked') }} @endif @endif autocomplete="is_active" autofocus>

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
									<input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="@if(isset($nomineeList)){{ $nomineeList->description }}@else{{ old('description') }}@endif" required autocomplete="description" autofocus>

									@error('description')
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
									<a class="btn btn-secondary btn-block" href="{{ route('nomineeLists') }}">
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
