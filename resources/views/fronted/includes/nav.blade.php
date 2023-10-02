<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse leftMenu" id="navbarNavDropdown">
    <ul class="navbar-nav menuwithmegam">
        {!! App\Category::getNavLinks() !!}
        <!--<li class="nav-item dropdown position-static">
                <a class="nav-link dropdown-toggle" href="product-listing.php" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">WOMEN’s</a>
                    <div class="dropdown-menu p-4" aria-labelledby="dropdown04">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 right_line">
                               <div class="mega_menu">
                                      <h2>Women Ethnic</h2>
                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <a class="dropdown-item" href="#">Kurti</a>
                                            <a class="dropdown-item" href="#">Bridal Gown</a>
                                            <a class="dropdown-item" href="#">Lehenga</a>
                                            <a class="dropdown-item" href="#">Ethnic Gown Salwar Set Material</a>
                                             <a class="dropdown-item" href="#">Bridal Lehenga</a>
                                             <a class="dropdown-item" href="#">Dhoti</a>
                                            </div>
                                            <div class="col-md-5">
                                            <a class="dropdown-item" href="#">Kaftan Kurti Set</a>
                                            <a class="dropdown-item" href="#">Ethnic Top</a>
                                            <a class="dropdown-item" href="#">Saree</a>
                                            <a class="dropdown-item" href="#">Blouse</a>
                                            <a class="dropdown-item" href="#">Dupatta</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-6 right_line">
                               <div class="mega_menu">
                                      <h2>Women Western</h2>
                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <a class="dropdown-item" href="#">Dress</a>
                                            <a class="dropdown-item" href="#">Co-ord</a>
                                            <a class="dropdown-item" href="#">Top</a>
                                            <a class="dropdown-item" href="#">Women's Hoodie</a>
                                             <a class="dropdown-item" href="#">Women's Shorts</a>
                                             <a class="dropdown-item" href="#">Skirt</a>
                                            <a class="dropdown-item" href="#">Trousers</a>
                                            </div>
                                            <div class="col-md-5">
                                            <a class="dropdown-item" href="#">Women's Jeans</a>
                                            <a class="dropdown-item" href="#">Women's Shirt</a>
                                            <a class="dropdown-item" href="#">Jogger</a>
                                            <a class="dropdown-item" href="#">Women's T-Shirt</a>
                                            <a class="dropdown-item" href="#">Women's Sweatshirt</a>
                                                <a class="dropdown-item" href="#">Jumpsuit Gown</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
              <li class="nav-item dropdown position-static">
                <a class="nav-link dropdown-toggle" href="product-listing.php" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">MEN’s</a>
                    <div class="dropdown-menu p-4" aria-labelledby="dropdown04">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 right_line">
                               <div class="mega_menu">
                                      <h2>Men Ethnic</h2>
                                    <div class="row justify-content-center">
                                       <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <a class="dropdown-item" href="#">Kurta</a>
                                            <a class="dropdown-item" href="#">Kurta Set</a>
                                            <a class="dropdown-item" href="#">Nehru Jacket</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                             <div class="col-lg-6 right_line">
                               <div class="mega_menu">
                                      <h2>Men Western</h2>
                                    <div class="row justify-content-center">
                                            <div class="col-md-5">
                                            <a class="dropdown-item" href="#">Men's Shirt Pants</a>
                                            <a class="dropdown-item" href="#">Men's Tshirt</a>
                                            <a class="dropdown-item" href="#">Men's Jacket</a>
                                            <a class="dropdown-item" href="#">Men's Jeans</a>
                                            <a class="dropdown-item" href="#">3Pc Suit</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li> -->
        @php
            $isbrandActive = '';
        @endphp
        @if (!empty($pagename) && $pagename == 'brandlisting')
            <?php $isbrandActive = 'active'; ?>
        @endif
        <li class="nav-item dropdown position-static">
            <a class="nav-link dropdown-toggle {{ $isbrandActive }}" href="{{ route('branddetails') }}"
                id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Brands</a>
            <div class="dropdown-menu p-4" aria-labelledby="dropdown04">
                <div class="row partnerpage">
                    <?php
                    
                    $vendorsdetails = DB::table('vendors')
                        ->select('vendors.id', 'vendor_company_info.name', 'vendor_company_info.logo')
                        ->Join('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
                        ->where('vendors.status', 1)
                        ->where('vendors.isdeleted', 0)
                        ->orderBy('vendors.featuremart', 'desc')
                        ->groupBy('vendors.id')
                        ->limit(12)
                        ->get();
                    
                    ?>
                    @foreach ($vendorsdetails as $vendorsdetail)
                        <div class="col-3 col-sm-3 col-md-2 col-lg-2">
                            <div class="partner-logo">
                                @if (!empty($vendorsdetail->logo))
                                    <a href="{{ route('brandproducts', [base64_encode($vendorsdetail->id)]) }}"><img
                                            src="{{ asset('/uploads/vendor/company_logo/' . $vendorsdetail->logo) }}"></a>
                                @else
                                    <a href="{{ route('brandproducts', [base64_encode($vendorsdetail->id)]) }}"><img
                                            src="{{ asset('public/fronted/images/brands2.png') }}"></a>
                                @endif
                            </div>
                            <h5>{{ $vendorsdetail->name }}</h5>
                        </div>
                    @endforeach
                    <!--<div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands2.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/puma.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands1.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands2.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/puma.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands1.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands2.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/puma.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands1.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/brands2.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
                <div class="partner-logo">
                    <a href="brand-detail.php"><img src="{{ asset('public/fronted/images/puma.png') }}"></a>
                </div>
                <h5>Brand Name</h5>
            </div>
        </div>-->
                    <div class="btn-width">
                        <a href="{{ route('branddetails') }}" class="btn btn-outline-dark btn-block">SHOW MORE</a>
                    </div>
                </div>
        </li>
    </ul>
</div>
