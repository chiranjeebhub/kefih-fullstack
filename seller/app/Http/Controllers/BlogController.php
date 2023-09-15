<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Blogs;
use App\Helpers\CommonHelper;
class BlogController extends Controller
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
     * Show the application dashboard.blog_listing
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    /* public function index()
    {
        return view('home');
    }
    */
    
   public function blog_listing()
    {
        
        $page_data=Blogs::where('isdeleted',0)->where('status',1)->orderBy('id','desc')->paginate(8);
		return view('fronted.mod_blog.blog',array('blog_data'=>$page_data));
    }
	
	public function blogdetail(Request $request){
	    
         $blog_id=base64_decode($request->blog_id);
         $master_blog=Blogs::select('blogs.*')->where('id',$blog_id)->first();
            
            
        return view('fronted.mod_blog.blog-details',[
            'blog_detail'=>$master_blog
            ]);
    }
    
	
	
 
}
