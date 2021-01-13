@extends('layouts.app')

@section('content')
@php ($defualtPhoto = 'imgs/photos/photo.jpg')
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
    </script>	
@endsection
