@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					<b>{{ __('Logs') }}</b>
						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
							<a href="{{ route('clearLogs') }}"><img class="m-icon" src="{{ asset('imgs/delete.png') }}" title="Clear All Logs" /></a>
						</div>
					</div>
					<div class="card-body">
						<table class="data-table">	
							<thead>
								<tr>
									<th>{{ __('ID') }}</th>
									<th>{{ __('User') }}</th>
									<th>{{ __('Action') }}</th>
									<th>{{ __('Time') }}</th>
									<th>{{ __('IP') }}</th>
																		
									<th>{{ __('Delete') }}</th>								
								</tr>
							</thead>
							<tbody>
								@foreach ($logs as $log)
									<tr class="raw-shadow">
										<td>{{ $log->id }}</td>
										<td>{{ $log->user->name }}</td>
										<td>{{ $log->action }}</td>
										<td>{{ $log->time }}</td>
										<td>{{ $log->ip }}</td>
																			
										<th><a href="{{ route('deleteLog', [ 'id'=> $log->id ]) }}"><img class="m-icon" src="{{ asset('imgs/delete.png') }}" title="{{ __('Delete Log') }}" /></a></th>								
									</tr>
								@endforeach	
							</tbody>
						</table>
						<p>
							{!! $logs->links() !!}
						</p>
					</div>
				</div>
			</div>
		</div>	
	</div>
@endif
@endsection