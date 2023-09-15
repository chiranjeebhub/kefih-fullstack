<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Customer; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use App\Advertise;
use Config;

class FAQController extends Controller 
{
	public $successStatus = 200;
		public $site_base_path='https://phaukat.com/';
		
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
    }
	/** 
     * Advertisement Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function faqCategory(Request $request) {
      $cats=DB::table('faq_category')
                    ->select('id as faq_category_id','name as faq_category_name')
                    ->get()
                    ->toarray();
        
					
		if(sizeof($cats)>0){
			$statusCode=201;
			$message="Faq categories listed";
			$slider_data=$cats;
		}else{
			$statusCode=201;
			$message="No Faq categories found";
			$slider_data=array();
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"faq_categories_data"=>$slider_data
				);
		
		echo json_encode($res);
	}
	
	public function faqByCategory(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    $fld_cat_id = @$input['faq_category_id'];
	$faqs=DB::table('tbl_faq')
	             ->select('fld_faq_question as fld_faq_question','fld_faq_answer as fld_faq_answer')
	             ->where('faq_category',$fld_cat_id)
	             ->where('fld_faq_status',1)
	             ->get()
	             ->toarray();
        
					
		if(sizeof($faqs)>0){
			$statusCode=201;
			$message="Faqs listed";
			$slider_data=$faqs;
		}else{
			$statusCode=201;
			$message="No Faqs found";
			$slider_data=array();
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"faq_data"=>$slider_data
				);
		
		echo json_encode($res);
	}
	
	
	
	


}