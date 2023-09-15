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
	
	   $cust_info=DB::table('customers')->where('id',$input['fld_user_id'])->first();
	   
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
    
		$earned=$spend=0;						
		foreach($wallet_history as $row){
			if($row->fld_reward_points!=0){
				$earned+=$row->fld_reward_points;
			}else if($row->fld_reward_deduct_points!=0){
				$spend+=$row->fld_reward_deduct_points;
			}
		}
		$total_reward_points=$cust_info->total_reward_points;
		$earned=$earned;
		$spend=$spend;
		
                $message="Wallet History";
                $api_key='wallet_data';
		
		echo $this->msg1_info($message,$wallet_history,$page,$api_key,$total_reward_points,$earned,$spend,$allrecord);
	}
	public function msg1_info(
                    $msg,
                    $data,
                    $page_no,
                    $api_key,
					$total_reward_points,
					$earned,
					$spend,
                    $Allrecord=array(),
                    $extra_keys='',
                    $extra_data=array()
	          )
	{
	    if($data){
			$status=true;
			$statusCode=201;
			$message=$msg;
			$total_reward_points=$total_reward_points;
			$earned=$earned;
			$spend=$spend;
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
					"total_reward_points"=>$total_reward_points,
					"total_earned"=>$earned,
					"total_spend"=>$spend,
					"next_page"=>$return_page,
					$api_key=>$data_list
				);
		}
	
		
		return json_encode($res);
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