<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use App\Wallet;


class WalletController extends Controller 
{
	public $successStatus = 200;
	
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
     	
    public function listing(Request $request) {
				    
	   $input = json_decode(file_get_contents('php://input'), true);
		$type=@$input['fld_wallet_type'];
	   $fld_page_no=@$input['fld_page_no'];
	   $page=$fld_page_no;
		
	
	   if($page!=0){
		 $fld_page_no=$fld_page_no*10;
	   }
		
	   $wallet_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
		->where('fld_customer_id',$input['fld_user_id'])->orderBy('tbl_wallet_history.id','desc');
		
	   	$allrecord =DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
		->where('fld_customer_id',$input['fld_user_id'])->orderBy('tbl_wallet_history.id','desc');
    
		if($type=='0'){
			$wallet_history->where('fld_wallet_date', '>=', date('Y-m'));
			$allrecord->where('fld_wallet_date', '>=',date('Y-m'));
		} else{
			$allrecord->where('fld_wallet_date', '<=',date('Y-m'));
			$wallet_history->where('fld_wallet_date', '<=', date('Y-m'));
		}
    
		 $wallet_history=$wallet_history
		                ->offset($fld_page_no)
                        ->limit(10)
                        ->get()
						->toarray();
						
   
    $allrecord=$allrecord
    ->get()
    ->toarray();
   
    
                $message="Wallet History";
                $api_key='wallet_data';
		
		echo $this->msg_info($message,$wallet_history,$page,$api_key,$allrecord);
	}
	public function msg_info(
                    $msg,
                    $data,
                    $page_no,
                    $api_key,
                    $Allrecord=array(),
                    $extra_keys='',
                    $extra_data=array()
	          )
	{
	    if($data){
			$status=true;
			$statusCode=201;
			$message=$msg;
			$data_list=$data;
			$return_page=($page_no+1);
		}else{
			$status=false;
			$statusCode=404;
			$message='No '.$msg.' Found';
			$data_list=null;
			$return_page=$page_no;
		}
		if($extra_keys!=''){
		   	$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					 "fld_total_page"=>ceil(sizeof($Allrecord)/10),
                    $extra_keys=>$extra_data,
					"message"=>$message,
					"next_page"=>$return_page,
					$api_key=>$data_list
				); 
		} else{
		    	$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					 "fld_total_page"=>ceil(sizeof($Allrecord)/10),
					"message"=>$message,
					"next_page"=>$return_page,
					$api_key=>$data_list
				);
		}
	
		
		return json_encode($res);
	}
	
}