@extends('layouts.app')

@section('content')
@php ($now = Carbon\Carbon::now())
@php ($now->addHours(2))
@php ($defualtPhoto = 'imgs/photos/photo.jpg')
@php ($maintenance = (int) config('settings.maintenance'))
@if(!$maintenance)
	<div class="container">		
		<div class="row justify-content-center">
			@foreach ($lists as $key=>$list)
				@if($list->count()>0)
					<div class="col-md-{{config('settings.listsInRawWidth')}} top-padding-15">
						<div class="card">
							<div class="card-header">
								@foreach ($nomineeLists as $nomineeList)
									@if($nomineeList->id == $key)
										{{ __($nomineeList->name) }}								
										<div class="{{(App::isLocale('ar') ? 'to-left' : 'to-right')}}">
											<img class="nominee-photo" src="{{ asset($nomineeList->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/>
										</div>
										@break
									@endif
								@endforeach
							</div>
							<div class="card-body">
								@foreach ($list as $nominee)
									<div class="raw raw-shadow top-bottom-padding-10">
										<label>
											<input id="{{ $nominee->id }}" name="{{ $nominee->id }}" type="checkbox" class="option-input checkbox {{ $nomineeList->name }}-nominees" onchange="validate(this,{{ $nominee->nominee_list_id }})" />							
										</label>
										<img class="nominee-photo" src="{{ asset($nominee->photo) }}" onerror="this.onerror=null;this.src='{{ asset($defualtPhoto) }}';"/><br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<span id="span{{ $nominee->id }}" >{{ $nominee->name }}</span>						
									</div>
								@endforeach	
							</div>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	</div>
	<script type="text/javascript">
	function validate(thisObj,listID){
		var cnt=0;			
		//alert(thisObj.checked);
		@foreach ($nomineeLists as $nomineeList)
			if(listID == {{ $nomineeList->id }}){
				$(".{{ $nomineeList->name }}-nominees").each(function () {
					if($(this).prop('checked'))
						cnt++;
				  });
				 if(cnt > {{ $nomineeList->selected_count }}){				
					thisObj.checked=false;
					if({{ $nomineeList->selected_count }} > 1 && {{ $nomineeList->selected_count }} <= 10)
						swal("{{ __('Vote Error') }}", "{{ __('You can not voting for more than') }} {{ $nomineeList->selected_count }} {{ __('nominees') }}", "error");
					else
						swal("{{ __('Vote Error') }}", "{{ __('You can not voting for more than') }} {{ $nomineeList->selected_count }} {{ __('nominee(s)') }}", "error");
				 }
			}
		@endforeach
	}
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
                      نظام الانتخاب الإلكتروني
                    </a>
                </P>
            </div>
            <div class="row justify-content-center">
                <P>
                    <a>
                       الموقع تحت الصيانة سنعود بعد دقائق
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