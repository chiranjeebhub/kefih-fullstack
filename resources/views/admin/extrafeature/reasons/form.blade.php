@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<form role="form" class="form-element multi_delete_form" action="{{$page_details['Action_route']}}" method="post">
  @csrf
                <div class="pad">
                <!--<h4 class="skin-purple-light">General</h4>-->
                <div class="fields">
                	<div class="row">
                		<div class="col-sm-3 col-md-3">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['offer_name']); !!}  
                		</div>
                	<div class="col-sm-3 col-md-3">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['for_category_or_brand']); !!}
                		</div>
                		<div class="col-sm-3 pad">
                		    Category Selection
									<div class="simpleListContainer clearfix">
											<div class="row">
												<ul id="simple_list">
												{!! App\Helpers\CommonHelper::getChildsTreeView(1,$page_details['cats']); !!}
												</ul>
											</div>
										</div>
										
										</div>
                		
                	</div>
                         
                </div>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                </div>
</form>
			
				
		<script type="text/javascript" src="{{ asset('public/js/jsLists.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[type="file"]').imageuploadify();
    });
	JSLists.applyToList('simple_list', 'ALL');
</script>		 
@endsection
