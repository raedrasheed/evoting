@extends('layouts.app')

@section('content')
@php ($now = Carbon\Carbon::now())

@php ($defualtPhoto = 'imgs/photos/photo.jpg')
@php ($maintenance = (int) config('settings.maintenance'))
@if (Auth::user()->role == 1)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ trans('app.title') }}</b></div>
					<div class="card-body">
						 <h4>@lang('Welcome') {{ Auth::user()->name }}</h4>
						 <div class="links">
							<p>
    							<a>{{ __('Voting Time') }}</a>:---<br/>
    							<a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
    							<a>{{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
    							<a>{{ __('Now') }}: {{ $now }}</a>
							</p>
						</div>
						<div class="links">
							<p>
    						    {{ __('Voting Demo [this demo cannot allow you to vote it is for viewing only]') }} <a href=" {{ route('votingDemo') }}">< {{ __('Press here') }} ></a>
							</p>
						</div>
						 <p>{{ __('Thanks') }} - {{ __('GoVote Live Team') }}</p>
                    
					</div>
				</div>
				<div class="card">
					<div class="card-header">
					<b>{{ __('Statistics') }}</b>			
					</div>
					<div class="card-body">
    					<div class="form-group row">						
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $allVoters }}" data-percent-count="{{ $allVoters }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('All Users') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $totalVotes }}" data-percent-count="{{ $totalVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Total Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $votingPrecentage }}" data-percent-count="{{ $votingPrecentage }}">
    							<div class="stat-percent-circle">0						 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Voting Precentage') }}
    							</div>							
    						</div>
    					</div>
    				</div>
    			</div>
			</div>
		</div>	
	</div>
@elseif ($votedFlag == 1  && !$maintenance)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Notes') }}</b></div>
					<div class="card-body">
					    <h4>@lang('Welcome') {{ Auth::user()->name }}</h4>
						{{ __('You have finish your vote') }}
						 <div class="links">
							<p>
    							<a>{{ __('Voting Time') }}</a>:<br/>
    							<a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
    							<a>{{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
    							<a>{{ __('Now') }}: {{ $now }}</a>
							</p>
						</div>
						<div class="links">
							<p>
    						    {{ __('Voting Demo [this demo cannot allow you to vote it is for viewing only]') }} <a href=" {{ route('votingDemo') }}">< {{ __('Press here') }} ></a>
							</p>
						</div>
						 <p>{{ __('Thanks') }} - {{ __('GoVote Live Team') }}</p>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
					<b>{{ __('Statistics') }}</b>			
					</div>
					<div class="card-body">
    					<div class="form-group row">						
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $allVoters }}" data-percent-count="{{ $allVoters }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('All Users') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $totalVotes }}" data-percent-count="{{ $totalVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Total Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $votingPrecentage }}" data-percent-count="{{ $votingPrecentage }}">
    							<div class="stat-percent-circle">0						 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Voting Precentage') }}
    							</div>							
    						</div>
    					</div>
    				</div>
    			</div>
			</div>
		</div>	
	</div>
@elseif (Carbon\Carbon::parse(config('settings.votingStartTime'))->gt($now)  && !$maintenance)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Notes') }}</b></div>
					<div class="card-body">
						<h4>@lang('Welcome') {{ Auth::user()->name }}</h4>
						{{ __('Voting not opened yet') }}
						 <div class="links">
							<p>
    							<a>{{ __('Voting Time') }}</a>:<br/>
    							<a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
    							<a>{{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
    							<a>{{ __('Now') }}: {{ $now }}</a>
							</p>
						</div>
						<div class="links">
							<p>
    						    {{ __('Voting Demo [this demo cannot allow you to vote it is for viewing only]') }} <a href=" {{ route('votingDemo') }}">< {{ __('Press here') }} ></a>
							</p>
						</div>
						 <p>{{ __('Thanks') }} - {{ __('GoVote Live Team') }}</p>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
					<b>{{ __('Statistics') }}</b>			
					</div>
					<div class="card-body">
    					<div class="form-group row">						
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $allVoters }}" data-percent-count="{{ $allVoters }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('All Users') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $totalVotes }}" data-percent-count="{{ $totalVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Total Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $votingPrecentage }}" data-percent-count="{{ $votingPrecentage }}">
    							<div class="stat-percent-circle">0						 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Voting Precentage') }}
    							</div>							
    						</div>
    					</div>
    				</div>
    			</div>
			</div>
		</div>	
	</div>
@elseif (Carbon\Carbon::parse(config('settings.votingEndTime'))->lt($now)  && !$maintenance)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><b>{{ __('Notes') }}</b></div>
					<div class="card-body">
						<h4>@lang('Welcome') {{ Auth::user()->name }}</h4>
						{{ __('Voting ended') }}
						 <div class="links">
							<p>
    							<a>{{ __('Voting Time') }}</a>:<br/>
    							<a>{{ __('From') }} {{ config('settings.votingStartTime') }}</a><br/>
    							<a>{{ __('To') }} {{ config('settings.votingEndTime') }}</a><br/>
    							<a>{{ __('Now') }}: {{ $now }}</a>
							</p>
						</div>
						<div class="links">
							<p>
    						    {{ __('Voting Demo [this demo cannot allow you to vote it is for viewing only]') }} <a href=" {{ route('votingDemo') }}">< {{ __('Press here') }} ></a>
							</p>
						</div>
						 <p>{{ __('Thanks') }} - {{ __('GoVote Live Team') }}</p>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
					<b>{{ __('Statistics') }}</b>			
					</div>
					<div class="card-body">
    					<div class="form-group row">						
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $allVoters }}" data-percent-count="{{ $allVoters }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('All Users') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $totalVotes }}" data-percent-count="{{ $totalVotes }}">
    							<div class="stat-count-circle">0							 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Total Votes') }}
    							</div>							
    						</div>
    						<div class="stat-circle stat-main col-md-4" data-count="{{ $votingPrecentage }}" data-percent-count="{{ $votingPrecentage }}">
    							<div class="stat-percent-circle">0						 
    							</div>
    							<div class="stat-main-container">
    							{{ __('Voting Precentage') }}
    							</div>							
    						</div>
    					</div>
    				</div>
    			</div>
			</div>
		</div>	
	</div>
@elseif (Auth::user()->voted == 0  && !$maintenance /* || Auth::user()->id == 290*/)
	<div class="container">		
		<div class="row justify-content-center">
			<div class="col-md-4 top-padding-15">
				<div class="card">
					<div class="card-header">{{ __('Presidential Nominees Second Round') }}</div>
					<div class="card-body">
						@foreach ($presidentialNominees as $presidentialNominee)
							<div class="raw raw-shadow top-bottom-padding-10">
								<label>
									<input id="{{ $presidentialNominee->id }}" name="{{ $presidentialNominee->id }}" type="checkbox" class="option-input checkbox presidential-nominees" onchange="validate(this,1)" />							
								</label>
								<img class="nominee-photo" src="{{ asset($presidentialNominee->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/><br/>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span id="span{{ $presidentialNominee->id }}" >{{ $presidentialNominee->name }}</span>						
							</div>
						@endforeach	
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
            <div class="col-md-4 top-padding-15">
                <button onclick="JSalert()" class="btn btn-success btn-block" style="{{(App::isLocale('ar') || App::isLocale('he') ? 'left' : 'right')}}:30px;">
				{{ __('Vote') }}..
				</button>
            </div>
		</div>
	</div>
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
		}else if(type == 2){
			$(".academic-members-nominees").each(function () {
				if($(this).prop('checked'))
					cnt++;
			  });
			 if(cnt > 4){
				 thisObj.checked=false;
				swal("{{ __('Vote Error') }}", "{{ __('You can not voting more than 4 nominees') }}", "error");
				
			 }			
		}else if(type == 3){
			$(".administrative-members-nominees").each(function () {
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
		var verificationText = "<div class='row justify-content-center'><table width='70%' style='font-size:10px;'>";
		var pres ="";
		var cnt = 1;
		$(".option-input").each(function () {
				if($(this).prop("checked")){
					if({{ $presidentialNominees->count() }} >= cnt){
						pres =" [{{ __('Presidential') }}] ";
					}
					var spanID = "#span"+$(this).prop("id");
					verificationText = verificationText +"<tr><td>" + $(spanID).text() + "</td><td>" + pres + "</td></tr>";				
				}
				cnt++;
				
			});
		verificationText = verificationText +"</tr></table></div>"
				
		swal({
		html:true,
		title: "{{ __('Your vote will be saved!') }}",   
		text: verificationText,
		type: "",     
		showCancelButton: true,   
		confirmButtonColor: "#38c172",   
		confirmButtonText: "{{ __('Yes') }}",   
		cancelButtonText: "{{ __('No') }}",   
		closeOnConfirm: false,   
		closeOnCancel: false }, 
		function(isConfirm){   
			if (isConfirm){   
				
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
					data: {_token: CSRF_TOKEN, voteJSON:voteJSON, voteJSONsha256:sha256(voteJSON), id:{{ Auth::user()->id }} },
					dataType: 'JSON',
					/* remind that 'data' is the response of the AjaxController */
					success: function (data) { 
						alert('JSON Ok..');
					}
				}); 
				//swal("{{ __('Vote saved!') }}", "{{ __('Your account will locked permanently!') }}", "success");
				//document.getElementById('logout-form').submit();
				//location.replace("https://election.iugaza.edu.ps/home");
		        swal({   title: "{{ __('Vote saved!') }}",   
        		text: "{{ __('Your account will locked permanently!') }}",   
        		type: "success",   
        		showCancelButton: false,   
        		confirmButtonColor: "#38c172",   
        		confirmButtonText: "{{ __('Yes') }}",   
        		//cancelButtonText: "{{ __('I am not sure!') }}",   
        		closeOnConfirm: false,   
        		closeOnCancel: false }, 
        		function(isConfirm){   
        			if (isConfirm){
        		     location.replace("{{ route('home') }}");    
        		    }
        		});
			
			 
			
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
			
			/*while(1){		*/				
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
		/*		if(block_hash.startsWith(stratZero, 0))
					break;
			}*/
			
			
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
			
			ascii += '\x80' // Append ??' bit (plus zero padding)
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
@else
	<div class="container">		
	<div class="row justify-content-center">
	<div class="col-md-8">
    <div class="card">
		<div class="card-header"><b>{{ trans('app.title') }}</b></div>
        <div class="card-body justify-content-center">
			<div class="row justify-content-center">
                    <img src="imgs/iug_logo.png" width="40%">
            </div>
            <div class="row justify-content-center">
                <P>
                    <br/><br/>
                    <a>
                      ???????? ???????????????? ????????????????????
                    </a>
                </P>
            </div>
            <div class="row justify-content-center">
                <P>
                    <a>
                       ???????????? ?????? ?????????????? ?????????? ?????? ??????????
                    </a>
                </P>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endif
@endsection
    	<script>
    		setTimeout(function start() {
    		  $(".stat-bar").each(function (i) {
    			var $bar = $(this);
    			$(this).append('<span class="stat-count" style="{{(App::isLocale("ar") ? "left" : "right")}}:0.25em"></span>');
    			$(this).append('<span class="stat-count-pers" style="{{(App::isLocale("ar") ? "left" : "right")}}:-3.2em"></span>');
    			setTimeout(function () {
    			  $bar.css("width", $bar.attr("data-percent"));
    			}, i * 100);
    		  });
    
    		  $(".stat-count").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-bar").attr("data-percent-count")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text(Math.ceil(now));
    				  }
    				}
    			  );
    		  });
    		  
    		  $(".stat-count-pers").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-bar").attr("data-percent")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text((Math.round(now*10)/10) + "%");
    				  }
    				}
    			  );
    		  });
    		  
    		  $(".stat-count-circle").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-circle").attr("data-count")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text(Math.ceil(now));
    				  }
    				}
    			  );
    		  });
    		  
    		   $(".stat-percent-circle").each(function () {
    			$(this)
    			  .prop("Counter", 0)
    			  .animate(
    				{
    				  Counter: $(this).parent(".stat-circle").attr("data-percent-count")
    				},
    				{
    				  duration: 2000,
    				  easing: "swing",
    				  step: function (now) {
    					$(this).text((Math.round(now*10)/10) + "%");
    				  }
    				}
    			  );
    		  });
    		  
    		}, 500);
    
    
    	</script>
