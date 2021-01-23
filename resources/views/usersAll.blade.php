@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b>{{ __('Users') }} ({{ count($users) }})</b>
						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
							<a href="{{ route('addEditUser', [ 'id'=> 0 ]) }}" ><img class="m-icon" src="imgs/add.png" title="Add New User"/></a>
						</div>
					</div>
					<div class="card-body data-table-div">
						<input class="myInput" type="text" id="myInput" onkeyup="myFunction()" placeholder="{{ __('Search..') }}" title="{{ __('Search..') }}">
						<table id="myTable">
							<thead>
								<tr class="header">
									<th>#</th>
									<th>{{ __('Outer ID') }}</th>
									<th>{{ __('Name') }}</th>
									<th>{{ __('Username') }}</th>
									<th>{{ __('User Role') }}</th>
									<th>{{ __('Voted') }}</th>
									<th>{{ __('Active') }}</th>
									<th>{{ __('Description') }}</th>
									<th>{{ __('Mobile') }}</th>
									<th>{{ __('Actions') }}</th>
								</tr>
							</thead>
							<tbody>			
								@php ($cnt=1)
								@foreach ($users as $user)
									<tr style="@if(!$user->is_active) {{ __('color:rgb(200 200 200)') }} @endif">
										<td>{{ $cnt }}</th>
										<td>{{ $user->outer_id }}</td>
										<td>{{ $user->name }}</td>
										<td>{{ $user->username }}</td>
										<td>@if ($user->role == 1) {{ __('Administrator') }} @elseif ($user->role == 2) {{ __('Regular') }} @endif</td>
										<td>@if ($user->voted == 1) {{ __('Yes') }} @else {{ __('No') }} @endif</td>
										<td>@if($user->is_active) {{ __('Active') }} @else {{ __('Inactive') }}@endif</td>
										<td>{{ $user->description }}</td>
										<td>{{ $user->mobile }}</td>
										<td style="text-align: center;"><a href="{{ route('sendSMSToken', [ 'username'=> $user->username ]) }}"><img class="m-icon" src="{{ asset('imgs/sendmsg.png') }}" title="{{ __('Send SMS Massage') }}"/></a> 
										<a href="{{ route('logs', [ 'id'=> $user->id ]) }}"><img class="m-icon" src="{{ asset('imgs/log.png') }}" title="{{ __('Show User Logs') }}"/></a> 
										<a href="{{ route('addEditUser', [ 'id'=> $user->id ]) }}"><img class="m-icon" src="{{ asset('imgs/edit.png') }}" title="{{ __('Edit User') }}"/></a>
										<a href="{{ route('deleteUser', [ 'id'=> $user->id ]) }}"><img class="m-icon" src="imgs/delete.png" title="{{ __('Delete User') }}"/></a></td>								
									</tr>
									@php ($cnt++)
								@endforeach								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>	
	</div>
@endif
@endsection
