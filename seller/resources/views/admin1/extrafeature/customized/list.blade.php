@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="">
        <div class="allbutntbl">
        <a href="{{ route('addcustomizedProduct') }}"
        class="btn btn-success"
        
        ><i class="fa fa-plus" aria-hidden="true"
        
        ></i> Add Customized Product</a>
        </div>
                <div class="col-sm-12 mt15">
                <ul class="list-inline disable-btn-ul">
                <li>
                <button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
                </li>
                </ul>
                </div>
        </div>
<div class="col-sm-12">
    
<form role="form" class="form-element multi_delete_form"  action="{{ route('multiDeleteCustomization') }}" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            	  <th><input type="checkbox" class="check_all"></th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($customized_products as $product)

						<tr>
						     <td><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="{{$product->id}}"></td>
						  	<td>{{$product->name}}</td>
						  	<td>	
				  	{!! App\Helpers\CustomFormHelper::support_image('uploads/products',$product->image); !!}
				  
						  	</td>
						
							<td>
            <a href="{{route('edit_customized', base64_encode($product->id))}}"
            onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
            >
                	<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
            </a>&nbsp;|&nbsp;
            <a href="{{route('delete_customized', base64_encode($product->id))}}"
            onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
            >
            <i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							    </td>

							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				  	{{ $customized_products->links() }}
				</div>

						  
 </form>
</div>
	@include('admin.includes.Common_search_and_delete') 
@endsection

