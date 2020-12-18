@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b>{{ __('Users') }}</b>
						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
							<a href="{{ route('addEditUser', [ 'id'=> 0 ]) }}" ><img class="m-icon" src="imgs/add.png" title="Add New User"/></a>
						</div>
					</div>
					<div class="card-body data-table-div">
						<table class="data-table">	
							<thead>
								<tr>
									<th>{{ __('Photo') }}</th>
									<th>{{ __('Outer ID') }}</th>
									<th>{{ __('Name') }}</th>
									<th>{{ __('Username') }}</th>
									<th>{{ __('User Role') }}</th>
									<th>{{ __('Voted') }}</th>
									<th>{{ __('Description') }}</th>
									<th>{{ __('Mobile') }}</th>
									<th>{{ __('Logs') }}</th>
									<th>{{ __('Edit') }}</th>	
								</tr>
							</thead>
							<tbody>							
								@foreach ($users as $user)
									<tr class="raw-shadow">
										<th><img class="nominee-photo" src="{{ asset($user->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/></th>
										<td>{{ $user->outer_id }}</td>
										<td>{{ $user->name }}</td>
										<td>{{ $user->username }}</td>
										<td>@if ($user->role == 1) {{ __('Administrator') }} @elseif ($user->role == 2) {{ __('Regular') }} @endif</th>
										<th>@if ($user->voted == 1) {{ __('Yes') }} @else {{ __('No') }} @endif</td>
										<td>{{ $user->description }}</td>
										<td>{{ $user->mobile }}</td>
										<th><a href="{{ route('logs', [ 'id'=> $user->id ]) }}"><img class="m-icon" src="{{ asset('imgs/log.png') }}" title="{{ __('Show User Logs') }}"/></a></th>
										<th><a href="{{ route('addEditUser', [ 'id'=> $user->id ]) }}"><img class="m-icon" src="{{ asset('imgs/edit.png') }}" title="{{ __('Edit User') }}"/></a></th>
									</tr>
								@endforeach								
							</tbody>
						</table>
						<p>
							{!! $users->links() !!}
						</p>
					</div>
				</div>
			</div>
		</div>	
	</div>
@endif
@endsection
