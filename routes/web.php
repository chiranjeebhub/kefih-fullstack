<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
   $exitCode = Artisan::call('cache:clear');
   return "Cache Clear";// return what you want
});

Route::group(['middleware' => ['auth:vendor' or 'auth']], function () {
    $real_path = realpath(__DIR__).DIRECTORY_SEPARATOR.'group_admin_routes'.DIRECTORY_SEPARATOR;
include($real_path.'zone.php');  
include($real_path.'deliveryboy.php');    
include($real_path .'categories.php');
include($real_path .'brand.php');
include($real_path .'city.php');
include($real_path .'materials.php');
include($real_path .'filters.php');
include($real_path .'attributes.php');
include($real_path .'products.php');
include($real_path .'coupon.php');
include($real_path .'vendor_products.php');
include($real_path .'customers.php');
include($real_path .'vendors.php');
include($real_path .'logistics.php');
include($real_path .'pincode.php');
include($real_path .'orders.php');
include($real_path .'permissions.php');
include($real_path .'users_role.php');
include($real_path .'extra_function.php');
include($real_path .'notification.php');
include($real_path .'banner.php');
include($real_path .'advertise.php');
include($real_path .'blog.php');
include($real_path .'whatsmore.php');
include($real_path .'testimonial.php');
include($real_path .'ledger.php');
include($real_path .'pages.php');
});



Route::group(['prefix' => 'admin'], function () {
        Route::any('/vendor_home', 'sws_Admin\VendorController@index')->name('vendor_home');
        Route::get('/vendor_logout', 'sws_Admin\VendorController@vendor_logout')->name('vendor_logout');
        Route::get('/admin_logout', 'sws_Admin\AdminController@admin_logout')->name('admin_logout');
    Route::get('/dashboard', function (){
        
        if(Auth::check()){
   return view('admin.dashboard.home');
        } else{
			 return view('auth.login');
		}
          }
        
        )->name('dashboard');
    
    Route::get('/', function () {
	
	if(Auth::check()){
    $role = Auth::user()->user_role; 
       // Check user role
    switch ($role) {
        case 0:    // Super Admin
               return redirect()->route('dashboard');
            break;
        case 1:    // Admin
               return redirect()->route('categories');
            break; 
         case 2:   // Vendor
                return redirect()->route('vendor_home');
            break;
    }
        } else{
			 return view('auth.login');
		}
});

	Route::any('/vendor_register/{level}', 'UserController@vendor_register')->name('vendor_register');
	Route::get('/email_verify/{email}/{code}', 'UserController@email_verify')->name('email_verify');
	Route::any('/vendor_login', 'UserController@vendor_login')->name('vendor_login');

	Route::any('/resend_otp', 'UserController@resend_otp')->name('resend_otp');
	Route::post('/login', 'LoginController@login')->name('admin_login');
	Route::get('/login', 'LoginController@showLoginForm')->name('showLoginForm');
	
    Route::any('/admin_forgot_password', 'UserController@admin_forgot_password')->name('admin_forgot_password');  
    Route::any('/admin_update_password', 'UserController@admin_update_password')->name('admin_update_password');
    Route::any('/admin_resend_otp', 'UserController@admin_resend_otp')->name('admin_resend_otp');
                
            Route::any('/vendor_forgot_password', 'UserController@vendor_forgot_password')->name('vendor_forgot_password');  
            Route::any('/update_password', 'UserController@vendor_update_password')->name('vendor_update_password');
            Route::any('/vendor_resend_otp', 'UserController@vendor_resend_otp')->name('vendor_resend_otp');
});

Auth::routes();

 // Route::get('/', function () {
//	echo "<pre>";
//	echo "hello";
//  });


Route::group(['middleware' => ['web']], function () {
	Route::post('/timeslotdata','HomeController@timeslot')->name('timeslotdata');
    Route::get('/redirect_gp', 'CustomerController@redirect_gp')->name('redirect_gp');
	Route::get('/redirect_fb', 'CustomerController@redirect_fb')->name('redirect_fb');
	Route::get('/callback_fb', 'CustomerController@callback_fb');
	Route::get('/callback_gp', 'CustomerController@callback_gp');

Route::any('/vendor_resend_otp', 'UserController@vendor_resend_otp')->name('vendor_resend_otp');
Route::any('/productVariations', 'CustomerAuthController@productVariations')->name('productVariations');
Route::any('/productVariationSize', 'CustomerAuthController@productVariationSize')->name('productVariationSize');

    //Customer Starts
	Route::get('/customer_home', 'CustomerController@index')->name('customer_home');
	// Route::any('/register', 'CustomerController@customer_register')->name('customer_register');
	// Route::any('/login', 'CustomerController@customer_login')->name('customer_login');

   Route::any('/register', 
   function(){
	return redirect()->back();
	// echo 'route not allowed';
   })->name('customer_register');
	Route::any('/login', function(){
		return redirect()->back();
		// echo 'route not allowed';
	   })->name('customer_login');


	Route::any('/OTP_login', 'CustomerController@OTP_login')->name('OTP_login');

	Route::post('/user-login', 'CustomerController@UserLoginSignup')->name('user-login');
	Route::post('/user-login-verifiy', 'CustomerController@UserLoginSignupVerifiy')->name('user-login-verifiy');
	Route::any('/user-login-otp', 'CustomerController@UserLoginOTP')->name('user-login-otp');

	Route::post('/update-fcm-token', 'CustomerController@update_fcm')->name('update-fcm-token');



	Route::any('/verifiy', 'CustomerController@verifiy')->name('verifiy');
	Route::any('/send_otp', 'CustomerController@send_otp')->name('send_otp');
	Route::any('/customer_resend_login_otp_message', 'CustomerController@customer_resend_login_otp_message')->name('customer_resend_login_otp_message');
	Route::any('/login_with_otp', 'CustomerController@login_with_otp')->name('login_with_otp');
	Route::any('/forgot_password','CustomerController@forgot_password')->name('forgot_password');
	Route::any('/update_password','CustomerController@update_password')->name('update_password');

	Route::get('customer/login', 'CustomerLoginController@getCustomerLogin');
    Route::post('customer/login', ['as'=>'customer.auth','uses'=>'CustomerLoginController@customerAuth']);
    Route::get('customer/logout', ['as'=>'customer.logout','uses'=>'CustomerController@logout']);
    
    //Customer auth
        Route::get('/mydashboard','CustomerAuthController@myDashboard')->name('mydashboard');
        Route::any('/accountinfo','CustomerAuthController@accountInfo')->name('accountinfo');
        Route::any('/changepass','CustomerAuthController@changepassword')->name('changepass');
		Route::any('/change-phone','CustomerAuthController@changePhoneVerify')->name('change-phone');

		
        Route::get('/myorder/{type}','CustomerAuthController@orderlist')->name('myorder');
        Route::get('/order_filter/{type}/{level}','CustomerAuthController@order_filter')->name('order_filter');
        
        Route::get('/order_detail/{order_id}/{order_details_id}','CustomerAuthController@orderdetail')->name('myorder-detail');
		Route::get('/download-service-invoice/{id}','CustomerAuthController@downloadServiceInvoice')->name('download-service-invoice');



         Route::get('/track_order/{order_id}/{order_details_id}','CustomerAuthController@track_order')->name('track_order');
		Route::get('/cancel_order_detail/{order_id}','CustomerAuthController@cancelorderdetail')->name('cancel-myorder-detail');
		Route::get('/order_invoice/{order_detail_id}','CustomerAuthController@order_invoice')->name('invoice-download');
        Route::any('/cancel_order/{order_id}','CustomerAuthController@cancel_order')->name('cancel_order');
        Route::any('/return_refund_order/{order_id}','CustomerAuthController@return_refund_order')->name('return_refund_order');
        Route::get('/savelater','CustomerAuthController@savelater')->name('savelater');
		Route::post('/removeSavelaterItem','CustomerAuthController@removeSavelaterItem')->name('removeSavelaterItem');
		Route::get('/wishlist','CustomerAuthController@wishlist')->name('wishlist');

		Route::get('/user-notifications','CustomerAuthController@notifications')->name('user-notifications');


		Route::post('/removeWishlistItem','CustomerAuthController@removeWishlistItem')->name('removeWishlistItem');
        Route::get('/wallet','CustomerAuthController@wallet')->name('wallet');
		Route::post('/wallet-recharge','CustomerAuthController@walletRecharge')->name('wallet-recharge');
		Route::any('/wallet-recharge-success/{id}','CustomerAuthController@walletRechargeSuccess')->name('wallet-recharge-success');
		Route::any('/wallet-recharge-faild/{id}','CustomerAuthController@walletRechargeFaild')->name('wallet-recharge-faild');
		Route::any('/wallet_recharge_callback','CustomerAuthController@wallet_recharge_callback')->name('wallet_recharge_callback');
		

          Route::get('/referrals','CustomerAuthController@referarls')->name('referrals');
         Route::get('/shippingDetails','CustomerAuthController@shippingDetails')->name('shippingDetails');
        Route::post('/addReview','CustomerAuthController@addReview')->name('addReview');
          Route::post('/sellerRating','CustomerAuthController@sellerRating')->name('sellerRating');
        Route::any('/myReviews','CustomerAuthController@myReviews')->name('myReviews');
        
         Route::any('/reason_and_policy','CustomerAuthController@reason_and_policy')->name('reason_and_policy');
    //Customer auth
       
       
        
    //Customer Ends
	
	/*Route::get('/cookie/set','CookieController@setCookie');
	Route::get('/cookie/get','CookieController@getCookie');*/
    Route::any('/test', 'TestController@test')->name('test');
    Route::any('/test2', 'TestController@test2')->name('test2');
    Route::any('/test3', 'TestController@test3')->name('test3');
     Route::any('/test4', 'TestController@test4')->name('test4');
      Route::any('/catsss', 'TestController@catsss')->name('catsss');
    Route::any('/customer_address', 'TestController@customer_address')->name('customer_address');

	Route::any('/fb-coversion', 'TestController@fbCoversion')->name('fb-coversion');

	// Route::any('/testsms', 'TestController@testsms')->name('testsms');
	Route::any('/testmail', 'TestController@testmail')->name('testmail');

    Route::get('/','HomeController@index')->name('index');
    Route::post('/filterCityOnState','HomeController@filterCityOnState')->name('filterCityOnState');
	Route::post('/filterCityIdOnState','HomeController@filterCityIdOnState')->name('filterCityIdOnState');
	Route::post('/filterStateOnCountry','HomeController@filterStateOnCountry')->name('filterStateOnCountry');

    Route::get('/home','HomeController@index')->name('index');
     Route::get('/home2','CheckoutController@index2')->name('index2');
    Route::post('/subscribe','HomeController@subscribe')->name('subscribe');
    Route::any('/get_size_chart','HomeController@get_size_chart')->name('get_size_chart');
	
	Route::get('/razor-pay-order-details/{razorpay_order_id}','HomeController@getRazorPayOrderDetails')->name('razor-pay-order-details');

	

	/* Fronted Slider Management */
	Route::get('slider','SliderController@getSlider')->name('slider');
	/* Fronted Slider Management */
	
	/* Fronted Static Management */
	   Route::get('/page/{page_url}','PagesController@page_url')->name('page_url');
	        Route::get('/offers','PagesController@offers')->name('offers');
	        Route::get('/snapbook','PagesController@snapbook')->name('snapbook');
            Route::get('/about','PagesController@about')->name('about');
            Route::get('/contact','PagesController@contact')->name('contact');
			Route::get('/coupon-list','PagesController@couponList')->name('couponlist');

            Route::get('/payment','PagesController@payment')->name('payment');
            Route::get('/help','PagesController@help')->name('help');
            Route::get('/terms_conditions','PagesController@termsConditions')->name('terms_conditions');
            Route::get('/return_policy','PagesController@return_policy')->name('return_policy');
            Route::get('/exchange','PagesController@exchange')->name('exchange');
            Route::get('/delivery','PagesController@delivery')->name('delivery');
			// Route::get('/faq/{category_id}','PagesController@faq')->name('faq');
			Route::get('/faq','PagesController@faq')->name('faq');
    Route::get('/refer_and_earn','PagesController@refer_and_earn')->name('refer_and_earn');
			Route::get('/become-a-seller','PagesController@become_a_seller')->name('become-a-seller');
	
	Route::get('/app-get-in-touch','PagesController@app_get_in_touch')->name('app-get-in-touch');
	Route::get('/app-contact-address','PagesController@app_contact_adddress')->name('app-contact-address');
	Route::get('/app-faq','PagesController@app_faq')->name('app-faq');
	
	/* Fronted Static Management */
	
	/* Fronted Product Management */
	
	
	
	
	/*Templatemail*/
	Route::get('/welcome','PagesController@welcome')->name('welcome');
	Route::get('/didnt-collect','PagesController@didnt_collect')->name('didnt-collect');
	Route::get('/shipped','PagesController@shipped')->name('shipped');
	Route::get('/reset-password','PagesController@reset_password')->name('reset-password');
	Route::get('/order','PagesController@order')->name('order');
	Route::get('/furture-delivery','PagesController@furture_delivery')->name('furture-delivery');
	Route::get('/out-for-delivery','PagesController@out_delivery')->name('out-for-delivery');
	Route::get('/delivered','PagesController@delivered')->name('delivered');
	Route::get('/cancel','PagesController@cancel')->name('cancel');
	
            Route::get('/customized','ProductController@customized')->name('customized');
            
            Route::get('/customizedform/{product_id}','ProductController@customizedform')->name('customizedform');
            
            Route::post('/customerQyeryforCustomized/{id}','ProductController@customerQyeryforCustomized')->name('customerQyeryforCustomized');

	/*Templatemail*/
	
	Route::get('/products/{cat}','ProductController@products')->name('prd');
	Route::get('search','ProductController@SearchProduct')->name('SearchProduct');
   
		
	Route::get('/product/{id}','ProductController@productDetails')->name('product_details');
	Route::get('/p/{rcat_name}/{cat_name}/{scat_name}/{name}/{id}','ProductController@productDetails')->name('scat_p_detail');
	Route::get('/p/{rcat_name}/{cat_name}/{name}/{id}','ProductController@productDetails')->name('cat_p_detail');
	Route::get('/p/{rcat_name}/{name}/{id}','ProductController@productDetails')->name('rcat_p_detail');
	Route::get('/p/{name}/{id}','ProductController@productDetails')->name('p_detail');
	Route::get('/cat/{name}/{catname}/{scatname}/{cat_id}','ProductController@cat_wise')->name('cat_wise');
	Route::get('/cat/{name}/{catname}/{cat_id}','ProductController@cat_wise')->name('cat_wise');
	Route::get('/cat/{name}/{cat_id}','ProductController@cat_wise')->name('cat_wise');
	Route::get('/offer/{name}/{type}','ProductController@offer')->name('offer');

	Route::get('/all-products/{type}/{id}','ProductController@AllProductView')->name('all_products');

	//Route::get('/offerzone','ProductOfferController@offerzone')->name('offerzone');
	Route::get('/offerzone','ProductOfferController@offerzone_category_product')->name('offerzone');
	Route::get('/catoffer/{name}/{id}','ProductOfferController@cat_offer_wise')->name('cat_offer_wise');
	

	Route::get('/offer-zone-products/{offer_id}','ProductOfferController@offerZoneProducts')->name('offer-zone-products');

	

	Route::any('/listingfilter','ProductController@listing_filter');
	Route::any('/vendorprdlistingfilter','ProductController@vendorprdlistingfilter');


	Route::post('/brandfilter','ProductController@brand_filter');
	Route::post('/sizefilter','ProductController@size_filter');
	Route::post('/colorfilter','ProductController@color_filter');
	Route::post('/searchfilter','ProductController@search_filter')->name('search_filter');
	Route::any('/quickView','ProductController@quickView')->name('quickView');
	Route::post('/setColoredImages','ProductController@setColoredImages')->name('setColoredImages');
	Route::any('/brand/{brands}','ProductController@brands_product')->name('brands_product');
	Route::any('/brand-products/{vendor_id}','ProductController@vendorProducts')->name('brandproducts');
	Route::any('/brand-products/{vendor_id}/{cat_name}/{cat_id}','ProductController@vendorProducts')->name('rbrandproducts');


	//more details
	Route::any('/branddetails','ProductController@branddetails')->name('branddetails');
	Route::any('/mendmbrandsshownore','ProductController@mendmbrandsshownore')->name('mendmbrandsshownore');
	Route::any('/womensbrandsshownore','ProductController@womensbrandsshownore')->name('womensbrandsshownore');
	
    Route::get('/moreSeller/{product_code}','HomeController@moreSeller')->name('moreSeller');
    Route::any('/check_pinCode','HomeController@check_pinCode')->name('check_pinCode');
    Route::any('/searchPincode','HomeController@searchPincode')->name('searchPincode');
    Route::any('/contact_us','HomeController@contact_us')->name('contact_us');
	Route::post('/product_enquiry','HomeController@product_enquiry')->name('product_enquiry');

	Route::any('/blog','BlogController@blog_listing')->name('blog');
	Route::get('/blog_detail/{blog_id}','BlogController@blogdetail')->name('blog-detail');
   
	
	Route::any('/product_review/{id}', 'ProductController@product_review')->name('product_review');
	/* Fronted Product Management */
	
	/* Fronted Cart Management */
	 Route::any('/apply_coupon','cart\CartController@apply_coupon')->name('apply_coupon');
	   Route::any('/mapProductWebApp','CookieController@mapProductWebApp')->name('mapProductWebApp');
	Route::any('/cart', 'cart\CartController@index')->name('cart');
	Route::any('/add_to_cart', 'ProductController@add_to_cart')->name('add_to_cart');
	
		Route::any('/cookieSetReset', 'ProductController@cookieSetReset')->name('cookieSetReset');
	Route::any('/update_cart', 'cart\CartController@update_cart')->name('update_cart');
	Route::post('/removeCoupon', 'cart\CartController@removeCoupon')->name('removeCoupon');

	Route::any('/changeQtyOfCartProduct', 'cart\CartController@changeQtyOfCartProduct')->name('changeQtyOfCartProduct');
	Route::any('/remove_cart_item', 'ProductController@remove_cart_item')->name('remove_cart_item');
    Route::any('/get_attr_dependend', 'ProductController@get_attr_dependend')->name('get_attr_dependend');
    Route::any('/getAttPrice', 'ProductController@getAttPrice')->name('getAttPrice');
	 Route::any('/sizeStock', 'ProductController@sizeStock')->name('sizeStock');
	Route::any('/add_to_savelater', 'cart\CartController@add_to_savelater')->name('add_to_savelater');
    /* Fronted Cart Management */
	
	/* Fronted Wishlist Management */
	Route::any('/add_to_wishlist', 'cart\CartController@add_to_wishlist')->name('add_to_wishlist');
	Route::any('/update_wishlist', 'cart\CartController@update_wishlist_count')->name('update_wishlist');
	/* Fronted Wishlist Management */

	 /* Billing Address Management */
	 Route::any('/billing-address', 'CheckoutController@billingAddresses')->name('billingAddresses');
	 Route::any('/addBillingAddress', 'CheckoutController@addBillingAddress')->name('addBillingAddress');
	 Route::any('/selectBillingAddress/{shipping_id}', 'CheckoutController@selectBillingAddress')->name('selectBillingAddress');
	 Route::any('/removeBillingAddress/{shipping_id}', 'CheckoutController@removeBillingAddress')->name('removeBillingAddress');
	 Route::any('/editBillingAddress/{shipping_id}', 'CheckoutController@editBillingAddress')->name('editBillingAddress');
 
	
	/* Fronted Checkout Management */
    Route::any('/checkout', 'CheckoutController@index')->name('checkout');
	Route::any('/verifysecurityCode', 'CheckoutController@verifysecurityCode')->name('verifysecurityCode');

    Route::any('/addShippingAddress', 'CheckoutController@add')->name('addShippingAddress');
	Route::any('/editShippingAddress/{shipping_id}', 'CheckoutController@editShippingAddress')->name('editShippingAddress');
	
	Route::any('/editShippingDetailsAddress/{shipping_id}', 'CustomerAuthController@editShippingDetailsAddress')->name('editShippingDetailsAddress');
	
    Route::any('/removeShippingAddress/{shipping_id}', 'CheckoutController@delete_shipping_address')->name('removeShippingAddress');
    Route::any('/selectShippingAddress/{shipping_id}', 'CheckoutController@selectShippingAddress')->name('selectShippingAddress');
	Route::any('/review_order', 'CheckoutController@review_order')->name('review_order');
	Route::any('/thankyou', 'CheckoutController@submit_order')->name('thankyou');
	Route::any('/callback', 'CheckoutController@callback')->name('callback');
	Route::any('/success/{merchant_order_id}/{merchant_trans_id}', 'CheckoutController@success')->name('success');
	Route::any('/failed/{merchant_order_id}/{merchant_trans_id}', 'CheckoutController@failed')->name('failed'); 
	Route::any('/chat', 'ChatController@index')->name('chat'); 
	#Route::get('/load-latest-messages', 'MessagesController@getLoadLatestMessages');
	Route::any('/send', 'ChatController@postSendMessage');
		Route::any('/testtttt', 'ProductController@testtttt');
		
		
	Route::any('/cronstock', 'CustomerController@cron_track')->name('cron_track');
	
	#Route::get('/fetch-old-messages', 'MessagesController@getOldMessages');
	
	/* Fronted Checkout Management */
	
	
// 	/  firebase routes
Route::any('/testnotificatiion', 'FirebaseController@testnotificatiion');
// 	/  firebase routes


	/*Compare Product*/
	         Route::post('brandSelection','ProductController@brandSelection')->name('brandSelection');
            Route::any('/compare', 'CompareController@index')->name('compare');
            Route::any('/addProductToCompare', 'ProductController@addProductToCompare')->name('addProductToCompare');
              Route::any('/otherSeller', 'ProductController@otherSeller')->name('otherSeller');
            Route::any('/RemovecompareProduct', 'ProductController@RemovecompareProduct')->name('RemovecompareProduct');
	/*Compare Product*/

	
	Route::group(['middleware' => ['customer']], function () {
		Route::get('customer/dashboard', ['as'=>'customer.dashboard','uses'=>'CustomerController@dashboard']);
    });
    
Route::view('/sellerrating','fronted.sellerrating')->name('sellerrating');
	Route::post('/filterareaOnCity','HomeController@filterareaOnCity')->name('filterareaOnCity');
});






