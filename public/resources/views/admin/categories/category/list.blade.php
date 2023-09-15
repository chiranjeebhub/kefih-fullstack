@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')



<div class="">
	<div class="allbutntbl">
		<a href="{{ route('add_category') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add  Category</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-md-4 hidden-xs"></div>
					<div class="col-md-3 col-xs-12 text-center">
						<!--<label>Select Status</label>-->
						<select class="form-control status" name="status">
							<option value="all"  <?php echo ($status=='all')?'selected':'';?>>Select Status</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5 col-xs-12">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string"  placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton"  >Search</button>
						</div>
					</div>
				</div>
				
				
			</div>
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>
		
	
		
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_cat')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						  <th><input type="checkbox" class="check_all"></th>
							<th>Id</th>
							<th>Name</th>
							<th>Parent</th>
							<th>Compare</th>
							<!--<th>Image</th>-->
							<!--<th>Banner</th>-->
							<th>View Attributes</th>
							<th>Shown in Nav</th>
							<th>URL</th>
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Categories as $Category)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="cat_id[]" value="{{$Category->id}}"></td>
								<td>{{$Category->id}}</td>
								<td>{{$Category->name}}</td>
								
									<td>
            <?php 
            $names=App\Category::getParentCategoriesById($Category->parent_id,array());
            
             $cats=array();
            $catsss=array();
            $categories = App\Category::
                   with('AllparentCats')
                ->where('isdeleted',0)
                ->where('id',$Category->id)
            ->first();
             if($categories){
             array_push($catsss,array('id'=>$categories->id,'name'=>$categories->name));
           $cats=App\Category::list_categories($categories->AllparentCats);
          }

   $cats1=App\Category::array_flatten($cats,array());

   foreach($cats1 as $key=>$c){
      if( ($key % 2)==0){
          array_push($catsss,array('id'=>$c,'name'=>$cats1[$key+1]));
      }
   }
   
   unset ($catsss[count($catsss)-1]);
        $final_cats=array_reverse($catsss);
        $last_index= count($final_cats)-1;
            $i=0;
            $span="";
         foreach($final_cats as $final){
             if($i!=$last_index){
                   $span.="".$final['name'].' => ';
             } else{
                  $span.="".$final['name'].'';
             }
             $i++;
         }
            ?>
			{{$span}}
									   </td>
								<td>{{($Category->cat_compare==0)?' ':'Enabled'}}</td>
								<!--<td>-->
								
								<!--{!! App\Helpers\CustomFormHelper::support_image('uploads/category/logo',$Category->logo); !!}-->
								<!--</td>-->
									
								<!--<td>{!! App\Helpers\CustomFormHelper::support_image('uploads/category/banner',$Category->banner_image); !!}</td>-->
								
								<td>
								<a href="javascript:void(0)" class="btn btn-primary showcategorySizes" cat_id="{{$Category->id}}">Size</a> |
							
								<a href="javascript:void(0)" class="btn btn-primary showcategoryColors" cat_id="{{$Category->id}}">Color</a>
								</td>
								
								<td>
								    @if($Category->cat_shows_in_nav==0)
								    <span>No</span>
								    @else
								     <span>Yes</span>
								    @endif()
								   
								</td>
								<td>
								  {{URL::to('/cat/'.preg_replace('/\s+/', '', $Category->name).'/'.base64_encode($Category->id))}}
								</td>
								
							<td>
							<a href="{{route('cat_sts',[base64_encode($Category->id),base64_encode($Category->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($Category->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
									<a href="{{route('edit_category', base64_encode($Category->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>
							<?php if($Category->id!=263 && $Category->id!=215 && $Category->id!=216){ ?>	
								&nbsp;|&nbsp;
							<a href="{{route('delete_category', base64_encode($Category->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							<?php } ?>
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				 </form>
				{{ $Categories->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
				
				<div id="attributesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content attributesResponseData">
     
     
    </div>

  </div>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>
@endsection
