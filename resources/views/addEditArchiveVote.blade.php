@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Add/Edit Archive') }}<bb></div>
					<div class="card-body">
						<form method="POST" action="{{ route('saveArchiveVote') }}" enctype="multipart/form-data">
                        @csrf
							<input type="hidden" id="id" name="id" value="@if(isset($previousVote)){{ $previousVote->id }}@else{{ __('0') }}@endif"/>
							
							<div class="form-group row">
								<label for="title" class="col-md-4 col-form-label{{(App::isLocale('ar') || App::isLocale('he') ? ' text-md-left' : ' text-md-right')}}">{{ __('Title') }}</label>

								<div class="col-md-8">
									<input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="@if(isset($previousVote)){{ $previousVote->title }}@else{{ old('title') }}@endif" required autocomplete="title" autofocus>

									@error('title')
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
									<a class="btn btn-secondary btn-block" href="{{ route('archiveVotes') }}">
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
