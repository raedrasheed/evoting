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