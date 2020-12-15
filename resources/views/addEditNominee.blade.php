@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Add/Edit Nominee') }}<bb></div>
					<div class="card-body">
						<form method="POST" action="{{ route('saveNominee') }}" enctype="multipart/form-data">
                        @csrf
							<input type="hidden" id="id" name="id" value="@if(isset($nominee)){{ $nominee->id }}@else{{ __('0') }}@endif"/>
							
							<div class="form-group row">
								<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

								<div class="col-md-8">
									<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="@if(isset($nominee)){{ $nominee->name }}@else{{ old('name') }}@endif" required autocomplete="name" autofocus>

									@error('name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							 <div class="form-group row">
								<label for="photo" class="col-md-4 col-form-label text-md-right">{{ __('Photo') }}</label>

								<div class="col-md-8">
									<label for="photo"> 
										<img id="photoImg"  src="@if(isset($nominee)){{ asset($nominee->photo) }}@else{{ old('photo') }}@endif" style="width:100px;height:100px; border: solid 2px #000;" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';" />
									</label>
									<input type="file" style="display:none;" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" name="photo" value="@if(isset($nominee)){{ $nominee->photo }}@else{{ old('photo') }}@endif" onChange="loadImage(this);">
									@error('photo')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Nomination Type') }}</label>

								<div class="col-md-8">
									<select id="type" class="js-example-basic-single form-control @error('type') is-invalid @enderror" name="type" value="@if(isset($nominee)){{ $nominee->type }}@else{{ old('type') }}@endif" required autocomplete="type" autofocus>
										<option value="0">{{ __('Chose Option') }}...</option>
										<option value="1" @if(isset($nominee)) @if($nominee->type == 1) {{ _('selected') }} @endif @else {{ old('type') }}@endif >{{ __('Presidential') }}</option>
										<option value="2" @if(isset($nominee)) @if($nominee->type == 2) {{ _('selected') }} @endif @else {{ old('type') }}@endif >{{ __('Academic') }}</option>
										<option value="3" @if(isset($nominee)) @if($nominee->type == 3) {{ _('selected') }} @endif @else {{ old('type') }}@endif >{{ __('Administrative') }}</option>
									</select>
									@error('type')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

								<div class="col-md-8">
									<input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="@if(isset($nominee)){{ $nominee->description }}@else{{ old('description') }}@endif" required autocomplete="description" autofocus>

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
									<a class="btn btn-secondary btn-block" href="{{ route('nominees') }}">
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
