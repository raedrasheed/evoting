@extends('layouts.app')

@section('content')   
			@php ($defualtPhoto = 'imgs/photos/photo.jpg')
		<div class="container">		
    		<div class="row justify-content-center">
    			<div class="col-md-12">
    				<div class="card">
    					<div class="card-header">
    					<b>{{ __('Voting History') }}</b>			
    					</div>
    					<div class="card-body">
							<div class="form-group row">						
								<div class="col-md-4">
									<div class="card">
										<div class="card-header">
										</div>
										<div class="card-body">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-header">
										</div>
										<div class="card-body">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-header">
										</div>
										<div class="card-body">
										</div>
									</div>
								</div>
							</div>
    					</div>
    				</div>	
    			</div>
    		</div>
		</div>
			
				
@endsection
