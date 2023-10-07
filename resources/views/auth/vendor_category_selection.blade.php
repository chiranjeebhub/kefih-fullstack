@extends('admin.layouts.app')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')



<section>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-5 col-sm-6 box-shadow">
                        <h2>Select your Primary Selling Category</h2>
                        <hr>                
                <div class="selectSellingCategories">
                    <div class="register_form">
                      <form role="form" class="lg-frm" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
					  @csrf
					  
								<div class="pad">
										<div class="simpleListContainer clearfix">
										<ul id="simple_list">
										{!! App\Helpers\CommonHelper::getChildsTreeView(1,array()); !!}
										</ul>
										</div>
										</div>
							
							
                              {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}	
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
	<script type="text/javascript" src="{{ asset('js/jsLists.min.js') }}"></script>
<script type="text/javascript">
	JSLists.applyToList('simple_list', 'ALL');
</script>	
</section>


@endsection
