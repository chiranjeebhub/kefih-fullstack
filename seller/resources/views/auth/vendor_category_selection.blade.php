@extends('admin.layouts.app')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<style>
    .main-footer{
        position: fixed;
        bottom: 0;
    }
    .content{ background: #f9f4f4; }
    .box-body{ background: #f9f4f4; }
</style>

<section>
    <div class="container">
        <div class="row">
            <div class="offset-md-3 col-md-6 col-xs-12">
                        <h2 class="text-center">Select your Primary Selling Category</h2>
                                        
                <div class="selectSellingCategories">
                    <div class="register_form">
                      <form role="form" class="lg-frm" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
					  @csrf
					  
								<div class=" ">
										<div class="simpleListContainer clearfix">
										<ul id="simple_list">
										{!! App\Helpers\CommonHelper::getChildsTreeView(1,array()); !!}
										</ul>
										</div>
				            </div>
							
							<div class="col-md-12 col-xs-12">
                                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}	
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
	<script type="text/javascript" src="{{ asset('public/js/jsLists.min.js') }}"></script>
<script type="text/javascript">
	JSLists.applyToList('simple_list', 'ALL');
	/*$('.catTreeSeletcted').on('change', function() {
   $('.catTreeSeletcted').not(this).prop('checked', false);
});*/
</script>	
</section>


@endsection
