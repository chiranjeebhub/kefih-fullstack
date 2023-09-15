@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 
<div class="">
                @if($page_details['method']==1)
                <div class="allbutntbl">
                <a href="{{ route('newCustomization') }}"
                class="btn btn-primary"
                >Back to all</a>
                </div>
                @endif()
        </div>
<div class="col-sm-12">
    
<form role="form" class="form-element" action="" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            
                        <th>Customer name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Message</th>
                        <th>Customization Image</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($customized_products as $customized)

						<tr>
						 
                    <td>{{$customized->name}}</td>
                    <td>{{$customized->email}}</td>
                    <td>{{$customized->mobile}}</td>
                    <td>{{$customized->message}}</td>
                   
                    
                     <td>
                         	{!! App\Helpers\CustomFormHelper::support_image('uploads/customerCustomizedimage',$customized->image); !!}
                         	
                         
                    	From => 
                    @if($page_details['method']==1)
                   
                    {!! App\Helpers\CustomFormHelper::support_image('uploads/products',$customized->master_image); !!}
                    @else
                    <a href="{{route('allCustomizationRelated', base64_encode($customized->master_id))}}">
                    {!! App\Helpers\CustomFormHelper::support_image('uploads/products',$customized->master_image); !!}</a>
                    @endif()
                
                
                    
                    	
                    	</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>

						  
 </form>
</div>

@endsection

