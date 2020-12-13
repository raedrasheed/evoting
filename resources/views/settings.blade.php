@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						Settings						
					</div>
					<div class="card-body">
						<form method="POST" action="{{ route('editSettings') }}">
                        @csrf
							 <div class="form-group row">
								<label for="systemName" class="col-md-4 col-form-label text-md-right">{{ __('System Name') }}</label>

								<div class="col-md-6">
									<input id="systemName" type="text" class="form-control @error('systemName') is-invalid @enderror" name="systemName" value="{{ old('systemName') }}" required autocomplete="systemName" autofocus>

									@error('systemName')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="votingTimeStart" class="col-md-4 col-form-label text-md-right">{{ __('Voting Time Start') }}</label>

								<div class="col-md-6">
									<input id="votingTimeStart" type="text" class="form-control @error('votingTimeStart') is-invalid @enderror" name="votingTimeStart" value="{{ old('votingTimeStart') }}" required autocomplete="votingTimeStart" autofocus>

									@error('votingTimeStart')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="votingTimeFinish" class="col-md-4 col-form-label text-md-right">{{ __('Voting Time Finish') }}</label>

								<div class="col-md-6">
									<input id="votingTimeFinish" type="text" class="form-control @error('votingTimeFinish') is-invalid @enderror" name="votingTimeFinish" value="{{ old('votingTimeFinish') }}" required autocomplete="votingTimeFinish" autofocus>

									@error('votingTimeFinish')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="numberOfNodes" class="col-md-4 col-form-label text-md-right">{{ __('Number Of Nodes') }}</label>

								<div class="col-md-6">
									<input id="numberOfNodes" type="text" class="form-control @error('numberOfNodes') is-invalid @enderror" name="numberOfNodes" value="{{ old('numberOfNodes') }}" required autocomplete="numberOfNodes" autofocus>

									@error('numberOfNodes')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="numberOfNomineeTypes" class="col-md-4 col-form-label text-md-right">{{ __('Number Of Nominee Types') }}</label>

								<div class="col-md-6">
									<input id="numberOfNomineeTypes" type="text" class="form-control @error('numberOfNomineeTypes') is-invalid @enderror" name="numberOfNomineeTypes" value="{{ old('numberOfNomineeTypes') }}" required autocomplete="numberOfNomineeTypes" autofocus>

									@error('numberOfNomineeTypes')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="form-group row">
								<label for="showResults" class="col-md-4 col-form-label text-md-right">{{ __('Show Results') }}</label>

								<div class="col-md-6">
									<input id="showResults" type="text" class="form-control @error('showResults') is-invalid @enderror" name="showResults" value="{{ old('showResults') }}" required autocomplete="showResults" autofocus>

									@error('showResults')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							
							<div class="form-group row mb-0">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary btn-block">
										{{ __('Save') }}
									</button>
								</div>
							</div>
						</form>	
					</div>
				
				</div>
			</div>
		</div>	
	</div>
@endif
@endsection
