@extends('admin.layouts.app_new')
@section('pageTitle', 'Home')
@section('boxTitle','Dashboard')
@section('content')

    <!-- Main content -->
    <section class="content">
	  <div class="row">
        <div class="col-xl-3 col-md-6 col-12 ">
            <a  href="{{ route('products') }}">
          	<div class="box bg-primary">
              <div class="box-body">
                <div class="flexbox">
                  <h5>Total Products</h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Helpers\CommonHelper::productCount(); !!}</div>
                
                </div>
              </div>

              <div class="card-body bg-gray-light py-12">
              
                <!--<span class="text-dark">{!! App\Helpers\CommonHelper::productCount(); !!}</span>-->
              </div>

              <div class="progress progress-xxs mt-0 mb-0">
                <div class="progress-bar bg-info" role="progressbar" style="width: 65%; height: 3px;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            </a>
        </div>
        <!-- /.col -->
        <div class="col-xl-3 col-md-6 col-12 ">
             <a href="{{ route('vendor_orders',base64_encode(0)) }}">
          	<div class="box bg-warning">
              <div class="box-body">
                <div class="flexbox">
                  <h5>Total Orders</h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Helpers\CommonHelper::orderCount(); !!}</div>
                  
                </div>
              </div>

              <div class="box-body bg-gray-light py-12">
               
                <!--<span class="text-dark">{!! App\Helpers\CommonHelper::orderCount(); !!}</span>-->
              </div>

              <div class="progress progress-xxs mt-0 mb-0">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 72%; height: 3px;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            </a>
        </div>
        <!-- /.col -->

        
        <!-- /.col -->
		
		<div class="col-xl-3 col-md-6 col-12">
		    <a href="{{ route('brands') }}">
          	<div class="box bg-danger">
          	    
              <div class="box-body">
                <div class="flexbox">
                  <h5>Total Brands</h5>
                 
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Helpers\CommonHelper::brandCount(); !!}</div>
                
                </div>
              </div>

              <div class="box-body bg-gray-light py-12">
                <!--<span class="text-muted mr-1">Completed:</span>-->
                <!--<span class="text-dark">{!! App\Helpers\CommonHelper::brandCount(); !!}</span>-->
              </div>

              <div class="progress progress-xxs mt-0 mb-0">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 55%; height: 3px;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            </a>
        </div>
        
        
		<div class="col-xl-3 col-md-6 col-12">
			<a href="{{ route('customers') }}">
			<div class="box bg-primary">
			  <div class="box-body">
				<div class="flexbox">
				  <h5>Total Customers</h5>

				</div>

				<div class="text-center my-2">
				  <div class="font-size-60 text-white">{!! App\Helpers\CommonHelper::customerCount(0); !!}</div>

				</div>
			  </div>

			  <div class="box-body bg-gray-light py-12">
				<!--<span class="text-muted mr-1">Completed:</span>-->
				<!--<span class="text-dark">{!! App\Helpers\CommonHelper::customerCount(0); !!}</span>-->
			  </div>

			  <div class="progress progress-xxs mt-0 mb-0">
				<div class="progress-bar bg-primary" role="progressbar" style="width: 55%; height: 3px;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
			  </div>
			</div>
			 </a>
		</div>
		
		 <div class="col-xl-3 col-md-6 col-12 ">
             <a href="{{ route('reports',base64_encode(0)) }}">
          	<div class="box bg-warning">
              <div class="box-body">
                <div class="flexbox">
                  <h5>Highest Selling product</h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Helpers\CommonHelper::reports_count(0); !!}</div>
                  
                </div>
              </div>

              <div class="box-body bg-gray-light py-12">
               
                <!--<span class="text-dark">{!! App\Helpers\CommonHelper::orderCount(); !!}</span>-->
              </div>

              <div class="progress progress-xxs mt-0 mb-0">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 72%; height: 3px;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            </a>
        </div>
        
         <div class="col-xl-3 col-md-6 col-12 ">
             <a href="{{ route('reports',base64_encode(1)) }}">
          	<div class="box bg-success">
              <div class="box-body">
                <div class="flexbox">
                  <h5>Location wise selling product</h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Helpers\CommonHelper::reports_count(1); !!}</div>
                  
                </div>
              </div>

              <div class="box-body bg-gray-light py-12">
               
                <!--<span class="text-dark">{!! App\Helpers\CommonHelper::orderCount(); !!}</span>-->
              </div>

              <div class="progress progress-xxs mt-0 mb-0">
                <div class="progress-bar bg-success" role="progressbar" style="width: 72%; height: 3px;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            </a>
        </div>
        
        
   
        <!-- /.col -->
		  
      </div>	
	
      <!-- /.row -->	      
	</section>
    <!-- /.content -->
@endsection
