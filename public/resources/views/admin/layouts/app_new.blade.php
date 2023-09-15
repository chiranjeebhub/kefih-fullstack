<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>@yield('pageTitle')</title> 
		<link rel="icon" href="{{ asset('public/images/favicon.png') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
	
	<!-- Bootstrap extend-->
	<link rel="stylesheet" href="{{ asset('public/css/bootstrap-extend.css') }}">
	
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

	<link rel="stylesheet" href="{{ asset('public/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
	<link rel="stylesheet" href="{{ asset('public/css/rateit.css') }}"> 
	<!-- theme style -->
	<link rel="stylesheet" href="{{ asset('public/css/master_style.css') }}">
	
	<link rel="stylesheet" href="{{ asset('public/css/master_style_new.css') }}">
	<!-- horizontal menu style -->
	<link rel="stylesheet" href="{{ asset('public/css/horizontal_menu_style.css') }}">
	
	<!-- Fab Admin skins -->
	<link rel="stylesheet" href=" {{ asset('public/css/skins/_all-skins.css') }}">
	
	<!-- Morris charts -->
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/morris.js/morris.css') }}">
	<link rel="stylesheet" href="{{ asset('public/css/vrp.css') }}">
		   	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
	<style>
	@import url({{ asset('public/assets/vendor_components/font-awesome/css/font-awesome.css') }});
	@import url({{ asset('public/assets/vendor_components/Ionicons/css/ionicons.css') }});
	@import url({{ asset('public/assets/vendor_components/themify-icons/themify-icons.css') }});
	@import url({{ asset('public/assets/vendor_components/linea-icons/linea.css') }});
	@import url({{ asset('public/assets/vendor_components/glyphicons/glyphicon.css') }});
	@import url({{ asset('public/assets/vendor_components/flag-icon/css/flag-icon.css') }});
	@import url({{ asset('public/assets/vendor_components/material-design-iconic-font/css/materialdesignicons.css') }});
	@import url({{ asset('public/assets/vendor_components/simple-line-icons-master/css/simple-line-icons.css') }});
	</style>
  	
	
</head>
<?php //$get = DB::table('coupons')->first(); ?>

<body class="hold-transition skin-purple-light layout-top-nav">
<div class="wrapper">
    <style>
        .searchButton {
    position: absolute;
    right: 0;
    top: 0;
    padding: 9px 15px;
    border: none;
    border-radius: 0px;
    background: #cf1f28 !important;
    opacity: 1 !important;
}
   .searchButton2 {
    position: absolute;
    right: 0;
    top: 0;
    padding: 9px 15px;
    border: none;
    border-radius: 0px;
    background: #cf1f28 !important;
    opacity: 1 !important;
}
   .searchButton1 {
    position: absolute;
    right: 0;
    top: 0;
    padding: 9px 15px;
    border: none;
    border-radius: 0px;
    background: #cf1f28 !important;
    opacity: 1 !important;
}
    </style>
	@include('admin.includes.header')
	@include('admin.includes.nav')
	@include('admin.includes.script') 		
	<div class="content-wrapper">
	
	    <!-- Main content -->
      <section class="content">
     
       <div class="row">
        <!-- left column -->
        <div class="col-12">
          <!-- general form elements -->
          <div class="box">
            <div class="box-header with-border">
             
              <h4 class="box-title">@yield('boxTitle')</h4>
              	<br>
              
              		@yield('backButtonFromPage')
              
             	 <?php if(@$page_details['backurl']!=''):?>
	 
	<span class="btn btn-dark btn-gradient btn-sm pull-right ml_20" ><a class="clr_white" href="{{$page_details['backurl']}}" style="color: #fff;">Go Back</a></span>  
	
					
	<?php endif;?>
            </div>
			  @include('admin.includes.session_message') 
					
            <!-- /.box-header -->
         
            <!-- .box-body -->
              <div class="box-body">
                 
			  @include('admin.includes.form_error') 
					<!-- form start -->
				
				 @if(@Session::has('success'))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
     @endif
     
     @if(@Session::has('danger'))
    <div class="alert alert-danger"> {{ Session::get('danger') }}</div>
     @endif
					
					@yield('content')
				
					<!-- /form  end -->
              </div>
			  
              <!-- /.box-body -->
					</div>
          <!-- /.box -->


        </div>
        <!--/.col (left) -->
        <!-- right column -->
       
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
      
    </section>
    <!-- /.content -->
	
	</div>
	@include('admin.includes.footer') 
	
	</div>
<script>


var front_data_url = document.location.origin+'/admin/';
 function assignvendor(deliveryid,orderid){
 	
 	$.ajax({
 		type:"POST",
 		 url:"{{ route('assign_deliveryboy') }}",
 		//url:front_data_url.concat('deliveryboy').concat('/').concat('assign'),
 		data:{deliveryid:deliveryid,orderid:orderid},
 		 success: function(data)
          {  
          alert('Delivery Boy Assign Successfully'); 
         // location.reload();
         }
 	});
 }    
	 $(document).on('change','#selectState', function () {
                 $('#selectcity option:not(:first)').remove();

                 //var state_id=$(this).val();

                 var state_id = $(this).find(':selected').data('id');

                 

            if(state_id!=''){

               

                $.ajax({

                   type:'POST',

    			   async:true,

                    url:"{{ route('filterCityOnState') }}",

                   data:{ "state_id":state_id},

                   success:function(data) {

                           

                          var response = JSON.parse(data);

                          console.log(response);

                          

                          if((response.size)>0){

                            $('#selectcity').append(response.city); 

                            $('#selectcity').addClass('custom-select');

                            //$('#selectcity').addClass('selectpicker');custom-select 

                            $('#selectcity').attr('data-live-search', 'true');

                             $('#selectcity').selectize('refresh');

                              

                          }

                         

                   }

                });

            }

            

            

            }) 
            $(document).on('change','#selectcity', function () {
                 $('#selectarea option:not(:first)').remove();

                 //var state_id=$(this).val();

                 var city_id = $(this).find(':selected').data('id');

                 

            if(city_id!=''){

               

                $.ajax({

                   type:'POST',

    			   async:true,

                    url:"{{ route('filterareaOnCity') }}",

                   data:{ "city_id":city_id},

                   success:function(data) {

                           

                          var response = JSON.parse(data);

                          console.log(response);

                          

                          if((response.size)>0){

                            $('#selectarea').append(response.city); 

                            $('#selectarea').addClass('custom-select');

                            //$('#selectcity').addClass('selectpicker');custom-select 

                            $('#selectarea').attr('data-live-search', 'true');

                             $('#selectarea').selectize('refresh');

                              

                          }

                         

                   }

                });

            }

            

            

            }) 
            
            
            
            
</script>
</body>
</html>
