@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1 || Auth::user()->role == 2)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					<b>{{ __('Voting Cards') }}: {{ $noOfVotes }} {{ __('Cards') }}</b>
						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
							{{ __('Blockchain Status') }}: 
							
								@if ( $fine )<span style="color:#38c172;font-weight:600;text-decoration:none;"> {{ __('Fine') }}</span>
								@else <span style="color:#e74c3c;font-weight:600;text-decoration:none;"> {{ __('Error') }}</span>
								@endif
							
						</div>
					</div>
					<div class="card-body">						
						
						<?php $prevBlock=0; ?>
						@foreach ($blocks as $block)
							
							<?php 
								if(!$prevBlock){
									$block_header = json_decode($block->block_header, true);
									if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
										echo "<div class='card' style='background-color:rgb(255 172 163);'>";										
									}else{
										echo "<div class='card' style='background-color:#EEE;'>";
									}										
								}else{
									$block_header = json_decode($block->block_header, true);
									if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
										echo "<div class='card' style='background-color:rgb(255 172 163);'>";
									}elseif(hash('sha256',$prevBlock->block_header) != $block_header["previous_block_hash"]){
										echo "<div class='card' style='background-color:rgb(255 172 163);'>";
									}else{
										echo "<div class='card' style='background-color:#EEE;'>";
									}										
								}
								$prevBlock = $block;
							?>
							
							<div class="card-header" style="background-color:#666;color:#FFF"><h5><b>{{ __('Card #') }}:[{{ $block->id }}]<b></h5></div>
							<div class="card-body">
							
								<table style="width:100%;border:none;">
									<tr>
										<td style="vertical-align: top;border:none;">
									
										<table style="width:100%;border:none;">
											<tr>
												<th colspan=2>
												{{ __('Presidential Nominees') }}
												</th>
											</tr>
											
												
												@foreach ($presidentialNominees as $presidentialNominee)
													@php ( $nomineeID = '' )
													@php ( $choice = '' )
													@php ( $choiceElement = 'imgs/unchecked.png' )
													@foreach (json_decode($block->vote, true) as $voteCard)
														@foreach ($voteCard as $vCard)									
															@if (number_format($vCard['nomineeID']) == $presidentialNominee->id )									
																@php ( $nomineeID = $vCard['nomineeID'] )
																@php ( $choice = $vCard['choice'] )
																@break
															@endif
														@endforeach
													@endforeach
													@if ($choice == 'true') 
														@php ( $choiceElement = 'imgs/checked.png' )
													@endif		
														<tr class="raw top-bottom-padding-10">
															<td style="vertical-align: top;border:none;width:30px;"><img src="{{ asset($choiceElement) }}" style="width:25px;" /></td>							
															<!--<img class="nominee-photo" src="{{ asset($presidentialNominee->photo) }}"/>-->
															<td style="vertical-align: top;border:none;">{{ $presidentialNominee->name }}</td>
														</tr>
													
												@endforeach	
							
											
										</table>
										</td>
										<td style="vertical-align: top;border:none;">
										<table style="width:100%;border:none;">
											<tr>
												<th colspan=2>
												{{ __('Academic Members Nominees') }}
												</th>
											</tr>
											
												
												@foreach ($academicMemberNominees as $academicMemberNominee)
													@php ( $nomineeID = '' )
													@php ( $choice = '' )
													@php ( $choiceElement = 'imgs/unchecked.png' )
													@foreach (json_decode($block->vote, true) as $voteCard)
														@foreach ($voteCard as $vCard)									
															@if (number_format($vCard['nomineeID']) == $academicMemberNominee->id )									
																@php ( $nomineeID = $vCard['nomineeID'] )
																@php ( $choice = $vCard['choice'] )
																@break
															@endif
														@endforeach
													@endforeach
													@if ($choice == 'true') 
														@php ( $choiceElement = 'imgs/checked.png' )
													@endif		
														<tr class="raw top-bottom-padding-10">
															<td style="vertical-align: top;border:none;width:30px;"><img src="{{ asset($choiceElement) }}" style="width:25px;" /></td>							
															<!--<img class="nominee-photo" src="{{ asset($academicMemberNominee->photo) }}"/>-->
															<td style="vertical-align: top;border:none;">{{ $academicMemberNominee->name }}</td>
														</tr>
													
												@endforeach	
							
											
										</table>
										</td>
										<td style="vertical-align: top;border:none;">
										<table style="width:100%;border:none;">
											<tr>
												<th colspan=2>
												{{ __('Administrative Members Nominees') }}
												</th>
											</tr>
											
												
												@foreach ($administrativeMemberNominees as $administrativeMemberNominee)
													@php ( $nomineeID = '' )
													@php ( $choice = '' )
													@php ( $choiceElement = 'imgs/unchecked.png' )
													@foreach (json_decode($block->vote, true) as $voteCard)
														@foreach ($voteCard as $vCard)									
															@if (number_format($vCard['nomineeID']) == $administrativeMemberNominee->id )									
																@php ( $nomineeID = $vCard['nomineeID'] )
																@php ( $choice = $vCard['choice'] )
																@break
															@endif
														@endforeach
													@endforeach
													@if ($choice == 'true') 
														@php ( $choiceElement = 'imgs/checked.png' )
													@endif		
														<tr class="raw top-bottom-padding-10">
															<td style="vertical-align: top;border:none;width:30px;"><img src="{{ asset($choiceElement) }}" style="width:25px;" /></td>							
															<!--<img class="nominee-photo" src="{{ asset($administrativeMemberNominee->photo) }}"/>-->
															<td style="vertical-align: top;border:none;">{{ $administrativeMemberNominee->name }}</td>
														</tr>
													
												@endforeach	
							
											
										</table>
										</td>
									</tr>						
								</table>
							</div>
							</div>
							
							
						@endforeach		
	
					</div>
				</div>
			</div>
		</div>	
	</div>
	
@endif
@endsection
