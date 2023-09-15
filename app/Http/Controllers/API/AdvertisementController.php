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

class AdvertisementController extends Controller 
{
	public $successStatus = 200;
		public $site_base_path='https://phaukat.com/';
		
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Advertisement Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function advertisement_listing(Request $request) {
				    
                $input = json_decode(file_get_contents('php://input'), true);
		$records =Advertise::
			      	select('id as fld_advertisement_id',
					'url as fld_advertisement_url',
				     DB::raw('"fld_cat_id" as fld_product_type'),
					'mobile_cat_id as fld_cat_prd_id',
					'advertise_position as fld_advertisement_position',
					'mobile_position as mobile_position',
					DB::raw("CONCAT('".$this->site_base_path."uploads/advertise/',mobile_image) AS fld_advertisement_image")
						)
					->where('status','=',1)
					->limit(7)
					->get()
					->toarray(); 
					
				
				$advs=array();
			$i =0;
				foreach($records as $record){
					
				     $record['fld_cat_compare']=0;
				    if($record['fld_product_type']=='fld_cat_id'){
				        $dts=DB::table('categories')
				         ->where('cat_compare',1)
				        ->first();
				        if($dts){
				            $record['fld_cat_compare']=1; 
				        } }
			
				    array_push($advs,$record);
				    
				    $i++;
				}
						
		if($advs){
			$statusCode=201;
			$message="Advertisement Listing";
			$slider_data=$advs;
		}else{
			$statusCode=404;
			$message="No advertisement found";
			$slider_data=null;
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"advertisement_data"=>$slider_data
				);
		
		echo json_encode($res);
	}
	
	
	
	


}