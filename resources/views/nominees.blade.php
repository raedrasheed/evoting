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
							<a href="{{ route('addEditNominee', [ 'id'=> 0 ]) }}"><img class="m-icon" src="imgs/add.png" title="{{ __('Add New Nominee') }}" /></a>
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
									<th>{{ __('Nominee List') }}</th>
									<th>{{ __('Active') }}</th>
									<th>{{ __('Description') }}</th>
									<th>{{ __('Actions') }}</th>
								</tr>
							</thead>
							<tbody>
								@php ($cnt=1)
								@foreach ($nominees as $nominee)
									<tr style="@if(!$nominee->is_active) {{ __('color:rgb(200 200 200)') }} @endif">
										<td>{{ $cnt }}</td>
										<td><img class="nominee-photo" src="{{ asset($nominee->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/></td>
										<td>{{ $nominee->name }}</td>
										<td>{{ __($nominee->nomineeList->name) }}</td>
										<td>@if($nominee->is_active) {{ __('Active') }} @else {{ __('Inactive') }}@endif</td>
										<td>{{ $nominee->description }}</td>
										<td style="text-align: center;"><a href="{{ route('addEditNominee', [ 'id'=> $nominee->id ]) }}"><img class="m-icon" src="imgs/edit.png" title="{{ __('Edit Nominee') }}" /></a> 
										<a href="{{ route('deleteNominee', [ 'id'=> $nominee->id ]) }}"><img class="m-icon" src="imgs/delete.png" title="{{ __('Delete Nominee') }}"/></a></td>								
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
