<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Products;
use App\Pages;
class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    /* public function index()
    {
        return view('home');
    }
    */
	
	public function welcome(){
		
		 return view('fronted.mailtemplate.welcome');
	}
	public function didnt_collect(){
		
		 return view('fronted.mailtemplate.didnt-collect');
	}
	
	public function shipped(){
		
		 return view('fronted.mailtemplate.shipped');
	}
	
	public function reset_password(){
		
		 return view('fronted.mailtemplate.reset-password');
	}
	public function order(){
		
		 return view('fronted.mailtemplate.order');
	}
	public function out_delivery(){
		
		 return view('fronted.mailtemplate.out-for-delivery');
	}
	public function furture_delivery(){
		
		 return view('fronted.mailtemplate.furture-delivery');
	}
	public function delivered(){
		
		 return view('fronted.mailtemplate.delivered');
	}
	public function cancel(){
		
		 return view('fronted.mailtemplate.cancel');
	}
	

    public function page_url(Request $request){
            $page_url=$request->page_url;
            $page_data=Pages::where('url_name',$page_url)->first();
            return view('fronted.mod_pages.custom_page',array('page_data'=>$page_data));
    }
    
     public function refer_and_earn()
    {     
       
        // $cat_id=base64_decode($request->category_id);
        //  $page_data=DB::table('tbl_faq')
        //                     ->where('fld_faq_status',1)
        //                     ->where('faq_category',$cat_id)
        //                     ->paginate(100);
        return view('fronted.mod_pages.refer_and_earn',array());
    }
    public function offers()
    {
        
        $position12=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',12)
					->where('tbl_advertise.status',1)->first();
		
		$position13=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',13)
					->where('tbl_advertise.status',1)->first();
					
					$position14=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',14)
					->where('tbl_advertise.status',1)->first();
        return view('fronted.mod_pages.offers',array(
                'advertise_position12'=>$position12,
                'advertise_position13'=>$position13,
                'advertise_position14'=>$position14,
           ));
    }
    
     public function snapbook()
    {
        
        $data=DB::table('product_rating')
                     ->select('uploads')
					->where('product_rating.uploads','!=','')
					->where('product_rating.isActive',1)
					->where('product_rating.is_in_snap_book',1)
					->get();
					
				
		
        return view('fronted.mod_pages.snapbook',array(
                'snap_books'=>$data
           ));
    }
   public function about()
    {
        $page_data=Pages::where('id',1)->first();
        return view('fronted.mod_pages.about-us',array('page_data'=>$page_data));
    }
    
     public function return_policy()
    {
         $page_data=Pages::where('id',4)->first();
        return view('fronted.mod_pages.return_policy',array('page_data'=>$page_data));
    }
    
     public function exchange()
    {
        $page_data=Pages::where('id',7)->first();
        return view('fronted.mod_pages.exchange',array('page_data'=>$page_data));
    }
    
     public function delivery()
    {
         $page_data=Pages::where('id',2)->first();
        return view('fronted.mod_pages.delivery',array('page_data'=>$page_data));
    }
     public function payment()
    {
          $page_data=Pages::where('id',3)->first();
        return view('fronted.mod_pages.payment',array('page_data'=>$page_data));
    }
	public function contact()
    {
        return view('fronted.mod_pages.contact-us');
    }
	
	public function help()
    {   $page_data=Pages::where('id',6)->first();
        return view('fronted.mod_pages.help-faq',array('page_data'=>$page_data));
    }
	
	public function termsConditions()
    {
          $page_data=Pages::where('id',5)->first();
        return view('fronted.mod_pages.terms-conditions',array('page_data'=>$page_data));
    }
	
		
	public function faq(Request $request)
    {      $cat_id=base64_decode($request->category_id);
         $page_data=DB::table('tbl_faq')
                            ->where('fld_faq_status',1)
                            ->where('faq_category',$cat_id)
                            ->paginate(100);
        return view('fronted.mod_pages.faq',array('page_data'=>$page_data,'category_id'=>$cat_id));
    }
	
	public function become_a_seller()
    {
       
        return view('fronted.mod_pages.become_a_seller');
    }
	
	
	public function app_get_in_touch()
    {
        //$page_data=Pages::where('id',1)->first();
        return view('fronted.mod_pages.app_get_in_touch_form');
    }
	
	public function app_contact_adddress()
    {
        //$page_data=Pages::where('id',1)->first();
        return view('fronted.mod_pages.app_contact_address');
    }
	
	public function app_faq()
    {
        //$page_data=Pages::where('id',1)->first();
        return view('fronted.mod_pages.app_faq');
    }
	
	
	
 
}
