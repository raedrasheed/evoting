@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	@php ($defualtPhoto = 'imgs/photos/photo.jpg')
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><b>{{ __('Votes') }}</b>
						<div class="{{(App::isLocale('ar') || App::isLocale('he') ? 'to-left' : 'to-right')}}">
							<a href="{{ route('addEditArchiveVote', [ 'id'=> 0 ]) }}" ><img class="m-icon" src="imgs/add.png" title="{{ __('Add New User') }}"/></a>
						</div>
					</div>
					<div class="card-body data-table-div">
						<input class="myInput" type="text" id="myInput" onkeyup="myFunction()" placeholder="{{ __('Search..') }}" title="{{ __('Search..') }}">
						<table id="myTable">
							<thead>
								<tr class="header">
									<th>#</th>
									<th>{{ __('Title') }}</th>
									<th>{{ __('start_date') }}</th>
									<th>{{ __('end_date') }}</th>
									<th>{{ __('all_voters') }}</th>
									<th>{{ __('total_votes') }}</th>
									<th>{{ __('correct_votes') }}</th>
									<th>{{ __('incorrect_votes') }}</th>
									<th>{{ __('voting_precentage') }}</th>
									<th>{{ __('blank_votes') }}</th>
									<th>{{ __('Actions') }}</th>
								</tr>
							</thead>
							<tbody>			
								@php ($cnt=1)
								@foreach ($previousVotes as $previousVote)
									<tr>
										<td>{{ $cnt }}</th>
										<td>{{ $previousVote->title }}</td>
										<td>{{ $previousVote->start_date }}</td>
										<td>{{ $previousVote->end_date }}</td>
										<td>{{ $previousVote->all_voters }}</td>
										<td>{{ $previousVote->total_votes }}</td>
										<td>{{ $previousVote->correct_votes }}</td>
										<td>{{ $previousVote->incorrect_votes }}</td>
										<td>{{ $previousVote->voting_precentage }}</td>
										<td>{{ $previousVote->blank_votes }}</td>
										<td style="text-align: center;">
										<a href="{{ route('addEditArchiveVote', [ 'id'=> $previousVote->id ]) }}"><img class="m-icon" src="{{ asset('imgs/edit.png') }}" title="{{ __('Edit Archive') }}"/></a>
										<a href="{{ route('resultArchiveVote', [ 'id'=> $previousVote->id ]) }}"><img class="m-icon" src="imgs/statistics.png" title="{{ __('Statistics') }}"/></a>							
										<a href="{{ route('deleteArchiveVote', [ 'id'=> $previousVote->id ]) }}"><img class="m-icon" src="imgs/delete.png" title="{{ __('Delete Archive') }}"/></a>	</td>							
									
									</tr>
									@php ($cnt++)
								@endforeach								
							</tbody>
						</table>
						<p>
						    {{ $previousVotes->links() }}
						</p>
					</div>
				</div>
			</div>
		</div>	
	</div>
@endif
@endsection
