@extends('fronted.layouts.app_new')

<?php
$dt=DB::table('meta_tags')->where('page_id',0)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', ' ')
@section('metaKeywords', ' ')
@section('metadescription', ' ')
<?php } ?>

{{-- @section('slider')
    @include('fronted.mod_slider.home_banner_slider')

    @include('fronted.includes.advertisement')

@endsection --}}
@section('content')


    <section class="wrap wrap-top0">
        <div class="container">
            <div class="slide-heading">
                <div class="title text-center">
                    <h3>TOP MEN'S BRANDS</h3>
                </div>
            </div>

            <div class="owl-carousel owl-carousel3 mb-4">
                <?php
		$categoryidmen=Config::get('constants.category.mancategoryid');

		$womencategoryid=Config::get('constants.category.womencategoryid');
			$vendrosactivex=DB::table('vendors')
			->select('vendors.id','vendor_company_info.name','vendor_company_info.logo')
			->join('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
			->where('vendors.featuremart','1')
			->where('vendors.status','1')
			->where('vendors.isdeleted',0)

			->get();

			foreach ($vendrosactivex as $key => $value) {

				$vendrosactive=DB::table('vendor_categories')->where('vendor_id',$value->id)->first();
				if(!empty($vendrosactive->selected_cats))
				{
					$categoryid=explode(',',$vendrosactive->selected_cats);
					if (in_array($categoryidmen, $categoryid))
 					 { ?>

                <div class="item">
                    <div class="partner-logo">
                        <a href="{{ route('brandproducts', [base64_encode($value->id)]) }}">
                            <img title="{{ $value->name }}"
                                src="{{ asset('/uploads/vendor/company_logo/' . $value->logo) }}" />
                        </a>
                    </div>
                </div>
                <?php }
				}

			}
		?>
            </div>
            <!--<div class="owl-carousel owl-carousel3 mb-4">
                                       <div class="item">
                                        <div class="partner-logo">
                                         <img src="{{ asset('public/fronted/images/brand-logo.png') }}"/>
                                        </div>
                                       </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo0.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo1.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo2.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo0.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo2.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo2.png') }}"/>
                                      </div>
                                      </div>
                                      </div>-->
            <!--<div class="btn-width">
                                                <a href="{{ route('mendmbrandsshownore') }}" class="btn btn-outline-dark btn-block">SHOW MORE</a>
                                                </div>-->
        </div>
    </section>
    <section class="wrap wrap-top0">
        <div class="container">
            <div class="slide-heading">
                <div class="title text-center">
                    <h3>TOP WOMEN'S BRANDS</h3>
                </div>
            </div>
            <div class="owl-carousel owl-carousel3 mb-4">
                <?php

			$vendrosactivey=DB::table('vendors')
			->select('vendors.id','vendor_company_info.name','vendor_company_info.logo')
			->Join('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
			->where('vendors.featuremart','1')
			->where('vendors.status','1')
			->where('vendors.isdeleted',0)
			->groupBy('vendors.id')
			->get();
			foreach ($vendrosactivey as $key => $value) {

				$vendrosactive=DB::table('vendor_categories')->where('vendor_id',$value->id)->first();
				if(!empty($vendrosactive->selected_cats))
				{
					$categoryid=explode(',',$vendrosactive->selected_cats);
					if (in_array($womencategoryid, $categoryid))
 					 { ?>

                <div class="item">
                    <div class="partner-logo">
                        <a href="{{ route('brandproducts', [base64_encode($value->id)]) }}">
                            <img title="{{ $value->name }}"
                                src="{{ asset('/uploads/vendor/company_logo/' . $value->logo) }}" />
                        </a>
                    </div>
                </div>
                <?php }
				}

			}
		?>
            </div>
            <!--<div class="owl-carousel owl-carousel3 mb-4">
                                            <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo0.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo1.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo2.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo0.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo2.png') }}"/>
                                      </div>
                                      </div>
                                      <div class="item">
                                      <div class="partner-logo">
                                      <img src="{{ asset('public/fronted/images/brand-logo2.png') }}"/>
                                      </div>
                                      </div>
                                      </div>-->
            <!--<div class="btn-width">
                                                <a href="{{ route('womensbrandsshownore') }}" class="btn btn-outline-dark btn-block">SHOW MORE</a>
                                                </div>-->
        </div>
    </section>

    <section class="wrap wrap-top0">
        <div class="container">
            <div class="row">
                <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="qalitybox">
                        <div class="iconbox">
                            <img src="{{ asset('public/fronted/images/qality.png') }}">
                        </div>
                        <h2>Quality Product</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="qalitybox">
                        <div class="iconbox">
                            <img src="{{ asset('public/fronted/images/bestprice.png') }}">
                        </div>
                        <h2>Best Price</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                    </div>
                </div>
                <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="qalitybox">
                        <div class="iconbox">
                            <img src="{{ asset('public/fronted/images/support.png') }}">
                        </div>
                        <h2>24/7 Support</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
        $popupAds = App\Advertise::getPopupAds();
    @endphp
    @if (!empty($popupAds))
        <div class="order_cancel main_section banner_for_home_section">
            <div class="modal fade" id="onload">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><img
                                    src="{{ asset('public/fronted/images/remove-white.png') }}" alt=""> </button>
                        </div>
                        <div class="modal-body banner_for_home">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                                    <img src="{{ asset('uploads/advertise/' . $popupAds->image) }}" alt="">
                                    @if (!Auth::guard('customer')->check())
                                        <div class="checkbox profile_form">
                                            <div class="form-group">
                                                <a href="#myModal" data-bs-target="#myModal" data-bs-toggle="modal"
                                                    data-bs-dismiss="modal" type="button" class="btn"> Register</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('fronted.includes.addtocartscript')
@endsection
