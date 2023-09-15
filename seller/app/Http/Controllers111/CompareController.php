<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\ProductSlider;
use App\Products;
use App\Helpers\MsgHelper;

class CompareController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        	//$this->middleware('auth:customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function index(Request $request)
    {
        
        $prd_id=array();
            $user_compare_product=DB::table('compare_product')
            ->select('product_id')
            ->where('user_ip','=',$request->ip())
            ->get();
            foreach($user_compare_product as $prd){
                array_push($prd_id,$prd->product_id);
            }
            
            
            	$products=Products::
		    whereIn('id',$prd_id)->get();
		    
            return view('fronted.mod_compare.compare',array("products"=>$products));
    }
	
	
   
	
	
 
}
