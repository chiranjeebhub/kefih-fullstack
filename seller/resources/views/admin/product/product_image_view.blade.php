@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<section class="product-nav-details">
<form action="{{ route('multideleteimages') }}" method="post">
@csrf
    <div class="container">
        <div class="box-body">
                <!-- Tab panes -->
            <div class="row">
            <div class="col-sm-12 text-right">
				<!--<a href="#" class="btn btn-danger btn-sm">Delete</a>-->
        <input type="hidden" name="userid" value="{{$userid}}" >
        <input type="hidden" name="folderid" value="{{$folderid}}" >
        <input id="submit" name="submit" type="submit" class="btn btn-danger btn-sm" value="Delete" 
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }" />
			</div>
			</div>
           <div class="table-responsive mt15">
            <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th width="80" class="text-center"><input type="checkbox" id="chk_all" name="chk_all" class="check_all"/></th>
        <th>Image</th>
      </tr>
    </thead>
    <tbody>
<?php 
$peoducturl=Config::get('constants.Url.public_url');
?>
      @foreach($productimages as $productimage)
        @if(!empty($productimage))
          @if($productimage !== '.' && $productimage !== '..')
            <tr>
              <td class="text-center"><input type="checkbox" type="checkbox"  class="chkbox checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="{{$productimage}}"></td>
              <td><img src="{{ $peoducturl.'/uploads/products/'.$userid.'/'.$folderid.'/'.$productimage }}" class="img-thumbnail" alt="{{$productimage}}" width="70" height="70"></td>
            </tr>
         @endif
        @endif
      @endforeach
       <!--<tr>
        <td class="text-center"><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="175"></td>
        <td><img src="https://b2cdomain.in/kefih/uploads/slider/1667992083-503.jpg" class="img-thumbnail" alt="1667992083-503.jpg" width="70" height="70"></td>
      </tr>
         <tr>
        <td class="text-center"><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="175"></td>
        <td><img src="https://b2cdomain.in/kefih/uploads/slider/1667992083-503.jpg" class="img-thumbnail" alt="1667992083-503.jpg" width="70" height="70"></td>
      </tr>
         <tr>
        <td class="text-center"><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="175"></td>
        <td><img src="https://b2cdomain.in/kefih/uploads/slider/1667992083-503.jpg" class="img-thumbnail" alt="1667992083-503.jpg" width="70" height="70"></td>
      </tr>
         <tr>
        <td class="text-center"><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="175"></td>
        <td><img src="https://b2cdomain.in/kefih/uploads/slider/1667992083-503.jpg" class="img-thumbnail" alt="1667992083-503.jpg" width="70" height="70"></td>
      </tr>-->
    </tbody>
  </table>
            </div>
        </div>
    </div>
</form>
</section>
<script type="text/javascript">
$(document).ready(function(){
    $('#chk_all').click(function(){
        if(this.checked)
            $(".chkbox").prop("checked", true);
        else
            $(".chkbox").prop("checked", false);
    });
});

$(document).ready(function(){
    $('#delete_form').submit(function(e){
        if(!confirm("Confirm Delete?")){
            e.preventDefault();
        }
    });
});
</script>
@endsection