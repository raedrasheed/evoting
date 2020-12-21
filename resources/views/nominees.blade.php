@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
@php ($defualtPhoto = 'imgs/photos/photo.jpg')
@php ($searchIcon = 'imgs/searchicon.png')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b>{{ __('Nominees') }}</b>
						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
							<a href="{{ route('addEditNominee', [ 'id'=> 0 ]) }}"><img class="m-icon" src="imgs/add.png" title="Add New Nominee" /></a>
						</div>
					</div>
					<div class="card-body data-table-div">
						<input class="myInput" type="text" id="myInput" onkeyup="myFunction()" placeholder="{{ __('Search..') }}" title="{{ __('Search..') }}">
						<table id="myTable">	
							<thead>
								<tr class="header">									
									<th>#</th>
									<th>{{ __('Photo') }}</th>
									<th>{{ __('Name') }}</th>
									<th>{{ __('Nomination Type') }}</th>
									<th>{{ __('Description') }}</th>
									<th>{{ __('Edit') }}</th>
									<th>{{ __('Delete') }}</th>								
								</tr>
							</thead>
							<tbody>
								@php ($cnt=1)
								@foreach ($nominees as $nominee)
									<tr>
										<td>{{ $cnt }}</td>
										<td><img class="nominee-photo" src="{{ asset($nominee->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/></td>
										<td>{{ $nominee->name }}</td>
										<td>@if ($nominee->type == 1) {{ __('Presidential') }} @elseif ($nominee->type == 2) {{ __('Academic') }} @elseif ($nominee->type == 3) {{ __('Administrative') }} @endif</td>
										<td>{{ $nominee->description }}</td>
										<td><a href="{{ route('addEditNominee', [ 'id'=> $nominee->id ]) }}"><img class="m-icon" src="imgs/edit.png" title="{{ __('Edit Nominee') }}" /></a></td>
										<td><a href="{{ route('deleteNominee', [ 'id'=> $nominee->id ]) }}"><img class="m-icon" src="imgs/delete.png" title="{{ __('Delete Nominee') }}"/></a></td>								
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
