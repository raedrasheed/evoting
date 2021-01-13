@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>{{ __('Blockchain Explorer') }}</b>
						<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
							{{ __('Blockchain Status') }}: 
							
								@if ( $fine )<span style="color:#38c172;font-weight:600;text-decoration:none;"> {{ __('Fine') }}</span>
								@else <span style="color:#e74c3c;font-weight:600;text-decoration:none;"> {{ __('Error') }},</span>
									<a href="{{ route('refineBlockchain') }}"><span style="color:#38c172;font-weight:600;text-decoration:none;"> [ {{ __('Refine') }} ]</a>
								@endif
							
						</div>
					</div>
					<div class="card-body" style="text-align:left;">
						<?php $prevBlock=0; ?>
						@foreach ($blocks as $block)
							<p>
							<?php 
								if(!$prevBlock){
									$block_header = json_decode($block->block_header, true);
									if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
										echo "<span style='color:#e74c3c;'>";
									}else{
										echo "<span style='color:#38c172;'>";
									}										
								}else{
									$block_header = json_decode($block->block_header, true);
									if(hash('sha256',$block->vote) != $block_header["vote_hash"]){
										echo "<span style='color:#e74c3c;'>";
									}elseif(hash('sha256',$prevBlock->block_header) != $block_header["previous_block_hash"]){
										echo "<span style='color:#e74c3c;'>";
									}else{
										echo "<span style='color:#38c172;'>";
									}
								}
								$prevBlock = $block;
							?>
							<b>Block[{{ $block->id }}]:</b> {{ $block }}
							</span>
							</p>
						@endforeach		
					</div>
					
					<p>
						    {{ $blocks->links() }}
						</p>
				</div>
			</div>
		</div>	
	</div>
	
@endif
@endsection
