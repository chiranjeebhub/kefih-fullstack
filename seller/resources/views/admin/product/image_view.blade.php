@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php 
				$parameters = Request::segment(3);
			
				$parameters_level = base64_decode($parameters);
		
				
				?>
<style>
.hideElement{
	display:block;
}.pointer{
	cursor:pointer;
}
span.remove_atr {
    margin-top: 32px;
    position: absolute;
}
table#productTable,table#up_sell_productTable,table#cross_sell_productTable {
    width: 100% !important;
}
</
    .folder-img{display: inline-block;}
    .folder-img a{display: inline-block;}
    .folder-img button.close {
    position: absolute;
    background-color: #e0e0e0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    opacity: 9;
    text-shadow: none;
    right: 20px;
    float: none;
        font-weight: 400;
}
    .folder-img a img{    width: 55px;
    height:55px;
    object-fit: contain;}
    .folder-img h6{color: #333;
    font-size: 13px;
    font-weight: 500;}
    .folder-img a h6{}
</style>
<section class="product-nav-details">
    <div class="container">
        <div class="box-body">
                <!-- Tab panes -->
            <div class="row">
        
                <!-- <div class="col-md-6">
					<div class="form-group">
                        <label for="exampleInputEmail1">Vendor</label>
                        <select class="form-control" name="vendor_field" id="vendor_field">
                        <option value="">Select </option>
                        </select>
                    </div>	
                </div>
               <div class="col-md-6">
                    <div class="form-group" style="display:none;" id="showserach">
                    <label for="exampleInputEmail1">Vendor</label>
                        <input type="text" placeholder="label" value="" id="serachvalue" name="serach">
                        <input type="button" name="serachvalue" value="Serach" id="serachfolder" data-id="" >
                    </div>
                </div>-->
            </div>	
						<div class="form-group">
                         <label for="exampleInputEmail1">Images</label>
                            <div class="row" id="showfoldername">
                                
                                @foreach($pagefolder as $productimage)
        @if(!empty($productimage))
          @if($productimage !== '.' && $productimage !== '..')
          <div class="col-6 col-xs-6 col-sm-3 col-md-2">
					<div class="folder-img">
					<button type="button" class="close" data-dismiss="alert" data-id="{{$productimage}}">&times;</button>
						<a href="{{route('viewspageimages',[$vendorid,$productimage])}}">
					<img data-id="{{$productimage}}" class="viewsimages" src="{{asset('public/images/folderpic.png')}}"/>
							
					<h6>{{$productimage}}</h6>
							</a>
					</div>
					</div>
         @endif
        @endif
      @endforeach                   
                            <!--<div class="col-6 col-xs-6 col-sm-3 col-md-2">
                                <div class="folder-img">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <a href="#">
                                <img src="https://www.b2cdomain.in/kefih/public/images/folderpic.png"/>
                                        
                            <h6>Folder 1</h6>
                                        </a>
                            </div>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-3 col-md-2">
                                <div class="folder-img">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <a href="#">
                                <img src="https://www.b2cdomain.in/kefih/public/images/folderpic.png"/>
                                    <h6>Folder 2</h6>
                                    </a>
                            </div>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-3 col-md-2">
                                <div class="folder-img">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <a href="#">
                                <img src="https://www.b2cdomain.in/kefih/public/images/folderpic.png"/>
                            <h6>Folder 3</h6>
                                    </a>
                            </div>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-3 col-md-2">
                                <div class="folder-img">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <a href="#">
                                <img src="https://www.b2cdomain.in/kefih/public/images/folderpic.png"/>
                            <h6>Folder 4</h6>
                                    </a>
                            </div>
                                </div>-->
                            </div>
                            
                    </div>
            
        </div>
    </div>
</section>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       $('#vendor_field').change(function(){
        var val = $(this).val();
       $('#showserach').show();
                    $.ajax({
                    url: "{{ route('getfoldername') }}",
                    dataType: 'html',
                    data: { vendorid : val },
                    success: function(data) {
            
                    $('#showfoldername').html( data );
                    }
                    });
                   
        });
        $(document).on('click','#serachfolder',function(e) {
            var vendorid =$("#vendor_field option:selected").val();
            var val = $("#serachvalue").val();
            $.ajax({
            url: "{{ route('serachfoldername') }}",
            dataType: 'html',
            data: { vendorid : vendorid,serachdata:val},
            success: function(data) {
                var datalenth=data.length;
                if(datalenth >'0')
                {
                   $('#showfoldername').html( data );
                }else{
                    $('#showfoldername').html('No data found');
                }
                
            }
            });
        });
        
        $(document).on('click','.close',function(e) {
        var val = $(this).data("id");
        var vendorid =$("#vendor_field option:selected").val();
        swal({
				title: "Are you Sure?",
				text: "If you proceed you will delete this product images. Are you sure you want to proceed?",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Proceed to Delete",
				closeOnConfirm: false
			},
			function(isConfirm){
				if (isConfirm) {
                    $.ajax({
                    url: "{{ route('deletefolder') }}",
                    dataType: 'html',
                    data: { foldername : val ,vendorid:vendorid},
                    success: function(data) {
                        swal({title: "Success", text: "product images deleted successfully.", type: "success"},
                        function(){
                            location.reload();
									 
							});
                    //$('#showfoldername').html( data );
                    }
                    });
                    }
                });
        });

    });
</script>
@endsection
