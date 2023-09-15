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
    public function page_url(Request $request){
            $page_url=$request->page_url;
            $page_data=Pages::where('url_name',$page_url)->first();
       
            return view('fronted.mod_pages.custom_page',array('page_data'=>$page_data));
    }
    public function vendor_page(Request $request){
            $page_url=$request->page_url;
            $page_data=Pages::where('url_name',$page_url)->first();
            return view('fronted.mod_pages.vendor_page_custom_page',array('page_data'=>$page_data));
    }
   public function offers()
    {
        $page_data=Pages::where('id',1)->first();
        return view('fronted.mod_pages.about-us',array('page_data'=>$page_data));
    }
    
     public function about()
    {
        $page_data=Pages::where('id',1)->first();
        return view('fronted.mod_pages.about-us',array('page_data'=>$page_data));
    }
    
      public function page404()
    {
        
        return view('fronted.mod_pages.page404');
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
    	public function vendor_contactus()
    {
        return view('fronted.mod_pages.vendor_contactus');
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
	
	public function faq()
    {
        $page_data=DB::table('tbl_faq')->where('fld_faq_status',1)->where('fld_faq_type',0)->paginate(100);
        return view('fronted.mod_pages.faq',array('page_data'=>$page_data));
    }
    	public function vendor_faq()
    {
        $page_data=DB::table('tbl_faq')->where('fld_faq_status',1)->where('fld_faq_type',1)->paginate(100);
        return view('fronted.mod_pages.vendor_faq',array('page_data'=>$page_data));
    }
	
	public function become_a_seller()
    {
       
        return view('fronted.mod_pages.become_a_seller');
    }
	
 
}
