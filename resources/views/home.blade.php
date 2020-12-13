@extends('layouts.app')

@section('content')
@if (Auth::user()->role == 1)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ trans('app.title') }}</b></div>
					<div class="card-body">
						 <h4>@lang('Welcome')</h4>
						 <p>{{ __('Election Commission') }}</p>
                         <p>{{ __('Islamic University of Gaza') }}</p>
                    
					</div>
				</div>
			</div>
		</div>	
	</div>
@elseif (Auth::user()->voted == 1)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Notes') }}</div>
					<div class="card-body">
						{{ __('You have finish your vote') }}.
					</div>
				</div>
			</div>
		</div>	
	</div>
@elseif (Carbon\Carbon::parse(config('settings.votingStartTime'))->gt(Carbon\Carbon::now()))
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Notes') }}</div>
					<div class="card-body">
						<h4>@lang('Welcome')</h4>
						{{ __('Voting not opened yet') }}
					</div>
				</div>
			</div>
		</div>	
	</div>
@elseif (Carbon\Carbon::parse(config('settings.votingEndTime'))->lt(Carbon\Carbon::now()))
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Notes') }}</div>
					<div class="card-body">
						<h4>@lang('Welcome')</h4>
						{{ __('Voting ended') }}
					</div>
				</div>
			</div>
		</div>	
	</div>
@elseif (Auth::user()->voted == 0)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-4 top-padding-15">
				<div class="card">
					<div class="card-header">{{ __('Presidential Nominees') }}</div>
					<div class="card-body">
						@foreach ($presidentialNominees as $presidentialNominee)
							<div class="raw raw-shadow top-bottom-padding-10">
								<label>
									<input id="{{ $presidentialNominee->id }}" name="{{ $presidentialNominee->id }}" type="checkbox" class="option-input checkbox presidential-nominees" onchange="validate(this,1)" />							
								</label>
								<img class="nominee-photo" src="{{ asset($presidentialNominee->photo) }}"/>
								{{ $presidentialNominee->name }}						
							</div>
						@endforeach	
					</div>
				</div>
			</div>
			<div class="col-md-4 top-padding-15">
				<div class="card">
					<div class="card-header">{{ __('Academic Members Nominees') }}</div>
					<div class="card-body">
						@foreach ($academicMemberNominees as $academicMemberNominee)
							<div class="raw raw-shadow top-bottom-padding-10">
								<label>
									<input id="{{ $academicMemberNominee->id }}" name="{{ $academicMemberNominee->id }}" type="checkbox" class="option-input checkbox members-nominees" onchange="validate(this,2)"/>							
								</label>
								<img class="nominee-photo" src="{{ asset($academicMemberNominee->photo) }}"/>
								{{ $academicMemberNominee->name }}						
							</div>
						@endforeach						
					</div>
				</div>
			</div>
			<div class="col-md-4 top-padding-15">
				<div class="card">
					<div class="card-header">{{ __('Administrative Members Nominees') }}</div>
					<div class="card-body">
						@foreach ($administrativeMemberNominees as $administrativeMemberNominee)
							<div class="raw raw-shadow top-bottom-padding-10">
								<label>
									<input id="{{ $administrativeMemberNominee->id }}" name="{{ $administrativeMemberNominee->id }}" type="checkbox" class="option-input checkbox members-nominees" onchange="validate(this,2)"/>							
								</label>
								<img class="nominee-photo" src="{{ asset($administrativeMemberNominee->photo) }}"/>
								{{ $administrativeMemberNominee->name }}						
							</div>
						@endforeach						
					</div>
				</div>
			</div>
		</div>
	</div>
	<button onclick="JSalert()" class="btn btn-success to-bottom rounded-button-50" style="{{(App::isLocale('ar') ? 'left' : 'right')}}:30px;">
		{{ __('Vote') }}..
	</button>
			
	<script type="text/javascript">
	function validate(thisObj,type){
		var cnt=0;			
		//alert(thisObj.checked);
		if(type == 1){
			$(".presidential-nominees").each(function () {
				if($(this).prop('checked'))
					cnt++;
			  });
			 if(cnt > 1){				
				thisObj.checked=false;
				swal("{{ __('Vote Error') }}", "{{ __('You can not voting more than one nominee') }}", "error");
			 }
		}else{
			$(".members-nominees").each(function () {
				if($(this).prop('checked'))
					cnt++;
			  });
			 if(cnt > 4){
				 thisObj.checked=false;
				swal("{{ __('Vote Error') }}", "{{ __('You can not voting more than 4 nominees') }}", "error");
				
			 }			
		}
	}
	function JSalert(){
				
		swal({   title: "{{ __('Your vote will be saved!') }}",   
		text: "{{ __('If YES, you will logout!') }}",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#38c172",   
		confirmButtonText: "{{ __('Yes, save my vote') }}",   
		cancelButtonText: "{{ __('I am not sure!') }}",   
		closeOnConfirm: false,   
		closeOnCancel: false }, 
		function(isConfirm){   
			if (isConfirm) 
		{   
				
			var nomineeJSON = '';
			var voteJSON_t = '{ "vote" : [';
			$(".option-input").each(function () {
				nomineeJSON = '{ "nomineeID":"' +
								$(this).attr("id") +
								'" , "choice":"' +
								$(this).prop("checked") +'" }';
				
				voteJSON_t = voteJSON_t + nomineeJSON + ',';
			});
			//alert(voteJSON_t);
			voteJSON = voteJSON_t.slice(0, -1);
			//alert(voteJSON);
			voteJSON = voteJSON + ']}'
			//alert(voteJSON);			
		
			var block_headerJSON = '';
			
				block_headerJSON = block_maining(voteJSON);
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					/* the route pointing to the post function */
					url: '{{ route("addBlock") }}',
					type: 'POST',
					/* send the csrf-token and the input to the controller */
					data: {_token: CSRF_TOKEN, voteJSON:voteJSON, block_headerJSON:block_headerJSON, id:{{ Auth::user()->id }} },
					dataType: 'JSON',
					/* remind that 'data' is the response of the AjaxController */
					success: function (data) { 
						//alert('JSON Ok..');
					}
				}); 
				swal("{{ __('Vote saved!') }}", "{{ __('Your account will locked permanently!') }}", "success");
				document.getElementById('logout-form').submit();
		
			 
			
			//var block_hash = sha256(block_header);
				
		} 
		else {     
			swal("{{ __('Vote not saved') }}", "{{ __('You can continue voting') }}", "error");   
		} });
	}
	</script>	
	<script>
		function block_maining(voteJSON){
			var stratZero = '';
			for(var cnt=0; cnt < {{ $difficulty_target }}; cnt++)
				stratZero = stratZero+'0';			
			var vote_hash = sha256(voteJSON);
			//alert(Date.now());
			var timestamp = Date.now();
			var block_hash = '';
			var nonce = 0;				
			
			while(1){						
				nonce++;
				var block_headerJSON = '{"version" : "{{ $version }}",'+
								'"previous_block_hash": "{{ $previous_block_hash }}",'+
								'"vote_hash": "'+ vote_hash +'",'+
								'"timestamp": "'+ timestamp +'",'+
								'"difficulty_target": "{{ $difficulty_target }}",'+
								'"nonce": "'+nonce+'"'+
								'}';
								//alert(block_headerJSON);
							
				block_hash = sha256(block_headerJSON);
				if(block_hash.startsWith(stratZero, 0))
					break;
			}
			
			
			return block_headerJSON;
		
		}
		
		function sha256(ascii) {
			function rightRotate(value, amount) {
				return (value>>>amount) | (value<<(32 - amount));
			};
			
			var mathPow = Math.pow;
			var maxWord = mathPow(2, 32);
			var lengthProperty = 'length'
			var i, j; // Used as a counter across the whole file
			var result = ''

			var words = [];
			var asciiBitLength = ascii[lengthProperty]*8;
			
			//* caching results is optional - remove/add slash from front of this line to toggle
			// Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
			// (we actually calculate the first 64, but extra values are just ignored)
			var hash = sha256.h = sha256.h || [];
			// Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
			var k = sha256.k = sha256.k || [];
			var primeCounter = k[lengthProperty];
			/*/
			var hash = [], k = [];
			var primeCounter = 0;
			//*/

			var isComposite = {};
			for (var candidate = 2; primeCounter < 64; candidate++) {
				if (!isComposite[candidate]) {
					for (i = 0; i < 313; i += candidate) {
						isComposite[i] = candidate;
					}
					hash[primeCounter] = (mathPow(candidate, .5)*maxWord)|0;
					k[primeCounter++] = (mathPow(candidate, 1/3)*maxWord)|0;
				}
			}
			
			ascii += '\x80' // Append Ƈ' bit (plus zero padding)
			while (ascii[lengthProperty]%64 - 56) ascii += '\x00' // More zero padding
			for (i = 0; i < ascii[lengthProperty]; i++) {
				j = ascii.charCodeAt(i);
				if (j>>8) return; // ASCII check: only accept characters in range 0-255
				words[i>>2] |= j << ((3 - i)%4)*8;
			}
			words[words[lengthProperty]] = ((asciiBitLength/maxWord)|0);
			words[words[lengthProperty]] = (asciiBitLength)
			
			// process each chunk
			for (j = 0; j < words[lengthProperty];) {
				var w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
				var oldHash = hash;
				// This is now the undefinedworking hash", often labelled as variables a...g
				// (we have to truncate as well, otherwise extra entries at the end accumulate
				hash = hash.slice(0, 8);
				
				for (i = 0; i < 64; i++) {
					var i2 = i + j;
					// Expand the message into 64 words
					// Used below if 
					var w15 = w[i - 15], w2 = w[i - 2];

					// Iterate
					var a = hash[0], e = hash[4];
					var temp1 = hash[7]
						+ (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
						+ ((e&hash[5])^((~e)&hash[6])) // ch
						+ k[i]
						// Expand the message schedule if needed
						+ (w[i] = (i < 16) ? w[i] : (
								w[i - 16]
								+ (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15>>>3)) // s0
								+ w[i - 7]
								+ (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2>>>10)) // s1
							)|0
						);
					// This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
					var temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) // S0
						+ ((a&hash[1])^(a&hash[2])^(hash[1]&hash[2])); // maj
					
					hash = [(temp1 + temp2)|0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
					hash[4] = (hash[4] + temp1)|0;
				}
				
				for (i = 0; i < 8; i++) {
					hash[i] = (hash[i] + oldHash[i])|0;
				}
			}
			
			for (i = 0; i < 8; i++) {
				for (j = 3; j + 1; j--) {
					var b = (hash[i]>>(j*8))&255;
					result += ((b < 16) ? 0 : '') + b.toString(16);
				}
			}
			return result;
		};
	</script>
@endif
@endsection
