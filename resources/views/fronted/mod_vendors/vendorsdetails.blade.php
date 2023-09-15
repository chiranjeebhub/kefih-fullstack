@extends('fronted.layouts.app_new')
@section('pageTitle','Brands Listing')
@section('metaTitle','Brands Listing')
@section('metaDescription','Brands Listing')
@section('metaKeywords','Brands Listing')
@section('content')   
<style>
    span.sellername {
    color: #d01f27;
    font-size: 16px;
}

</style>

<section class="wrap partnerpage">
    <div class="container-fluid">
		 <div class="title">
        <h4>Brands on Kefih</h4> 
        </div>
        <div class="row">
            @foreach($vendorsdetails as $vendorsdetail)
            <div class="col-3 col-sm-3 col-md-2 col-lg-2">
                <div class="partner-logo">
                     @if(!empty($vendorsdetail->logo))
                    <a href="{{route('brandproducts',[base64_encode($vendorsdetail->id)])}}"><img src="{{ asset('/uploads/vendor/company_logo/'.$vendorsdetail->logo) }}"></a>
                    @else
                    <a href="{{route('brandproducts',[base64_encode($vendorsdetail->id)])}}"><img src="{{ asset('public/fronted/images/brands2.png') }}"></a>
                    @endif
                </div>
                <h5>{{$vendorsdetail->name }}</h5>
            </div>
          @endforeach
            <!--<div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/brands2.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/puma.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/brands1.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/brands2.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/puma.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/brands1.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="images/brands2.png"/></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/puma.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands1.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands2.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/puma.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands1.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands2.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/puma.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands1.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands2.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/puma.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands1.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands2.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/puma.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands1.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/brands2.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <img src="images/puma.png"/>
                </div>
                <h5>Brand Name</h5>
            </div>
        </div>
		<div class="btn-width ">
            <a href="#" class="btn btn-outline-dark btn-block mt-4">SHOW MORE</a>
            </div>-->
	</div>
	</section>
    @endsection