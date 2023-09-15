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
use App\ProductCategories;
use App\Orders;


class OrderController extends Controller 
{
	public $successStatus = 200;
	
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
       public function cancel_return_order(Request $request) {
            $input = json_decode(file_get_contents('php://input'), true);
          $stype=0;
            $otype=0;
            $msg="";
          if($input['fld_reason_type']==0){
                    $stype=4;
                    $msg="Your canceled order being proceed";
              } else{
                   $msg="";
                        $stype=5;
                         $msg="Your returned order being proceed";
              }
          $cancel_return_data=array(
                    "sub_order_id"=>$input['fld_order_id'],
                    "reason"=>$input['fld_reason_id'],
                    "type"=>$stype,
                    "comments"=>$input['fld_comments']
                  
              );
          $res=DB::table('cancel_return_refund_order')->insert($cancel_return_data);
          if($res){
              DB::table('order_details')->where('id',$input['fld_order_id'])->update(array(
                  "order_status"=>$stype
                  ));
             $out=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>$msg
				); 
              	
          } else{
              $out=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong"
				);
             	
          }
          	echo json_encode($out);
       }
       public function getReason(Request $request) {
            $input = json_decode(file_get_contents('php://input'), true);
    
          
                $res=array(
                "policy"=>'',
                "reasons"=>array()
                );
                $policy='';
         if($input['fld_reason_type']==0){
             $reasons=DB::table('order_cancel_reason')
             ->select(
                 'order_cancel_reason.id as  fld_reason_id',
                 'order_cancel_reason.reason as fld_reason'
                 )
               ->join('reason_category','reason_category.reason_id','order_cancel_reason.id')
               ->join('product_categories','reason_category.category_id','product_categories.cat_id')
                ->where('product_categories.product_id',$input['fld_product_id'])
                    ->where('order_cancel_reason.reason_type',0)
                    ->groupBy('order_cancel_reason.id')
                        ->get();
                        
                         
             $cats=ProductCategories::select('categories.cancel_description')
                                    ->join('categories','categories.id','product_categories.cat_id')
                                    ->where('product_categories.product_id',$input['fld_product_id'])
                                    ->first();
                                    if($cats){
                                        $policy=$cats->cancel_description; 
                                    }
                                    
                                    
               
         } else{
             $reasons=DB::table('order_cancel_reason')
            ->select(
                 'order_cancel_reason.id as  fld_reason_id',
                 'order_cancel_reason.reason as fld_reason'
                 )
               ->join('reason_category','reason_category.reason_id','order_cancel_reason.id')
               ->join('product_categories','reason_category.category_id','product_categories.cat_id')
                ->where('product_categories.product_id',$input['fld_product_id'])
                    ->where('order_cancel_reason.reason_type',1)
                    ->groupBy('order_cancel_reason.id')
                        ->get();
                        
                       
              $cats=ProductCategories::select('categories.return_description')
                                    ->join('categories','categories.id','product_categories.cat_id')
                                    ->where('product_categories.product_id',$input['fld_product_id'])
                                    ->first();
             if($cats){
               $policy=$cats->return_description;
             }
                  
         }
          $res=array(
                "policy"=>$policy,
                "reasons"=>$reasons
                );
        $message="reason return succesfully ";
        $api_key='reason_data';
		
		echo $this->msg_info($message,$reasons,0,$api_key,array());
           
       }
       public function order_listing(Request $request) {
				    
	   $input = json_decode(file_get_contents('php://input'), true);
	  
	   $id=@$input['fld_user_id'];
	   $type=@$input['fld_order_type'];
	   
	   $fld_page_no=@$input['fld_page_no'];
	   $page=$fld_page_no;
	
	   if($page!=0){
		 $fld_page_no=$fld_page_no*10;
	   }
		
		 $sub_orders=DB::table('order_details')
						  ->select(
								'order_details.id as fld_order_id',
								'order_details.suborder_no as fld_suborder_id',
								'order_details.product_id as fld_product_id',
								'order_details.product_name as fld_product_name',
								'order_details.product_price as spcl_price',
								'order_details.product_price_old as price',
								'products.short_description',
								DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',products.default_image) AS default_image"),
								DB::raw("CONCAT('http://aptechbangalore.com/test/api/order_invoice/',orders.id) AS order_invoice"),
								'order_details.size as fld_size',
								'order_details.color as fld_color',
								'order_details.order_status as order_status',
								'order_details.order_date as fld_order_date',
								'order_details.order_updated as fld_order_updated_date',
								'order_details.order_reward_points as fld_reward_points',
								'order_details.return_days as fld_return_days',
								DB::raw(" '' as fld_shipping_date"),
								DB::raw(" 0 as fld_track_id"),
								DB::raw(" 'false' as fld_return_flag")
							)
						 ->join('orders','order_details.order_id','orders.id')
						 ->join('products','order_details.product_id','products.id')
						 ->where('orders.customer_id',$id);
         switch($type){
                case 0:
                    $sub_orders=$sub_orders
                    ->Where(function($query) use ($id){
							 $query->orWhere('order_details.order_status',0);
							 $query->orWhere('order_details.order_status',1);
							 $query->orWhere('order_details.order_status',2);
						 })
                   
					->orderBy('orders.id','desc');
					
                        $all_record=$sub_orders;
                      $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray(); 
                break;
                
                case 3:
                      $sub_orders=$sub_orders
                    ->where('order_details.order_status',$type)
                    ->orderBy('orders.id','desc');
                      $all_record=$sub_orders;
                      
                     $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
				
                case 5:
                     $sub_orders=$sub_orders
                        ->where('order_details.order_status',$type)
                        
					  ->orderBy('orders.id','desc');
					  
					   $all_record=$sub_orders;
					   
                     $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
                
                case 4:
                    $sub_orders=$sub_orders
                    ->where('order_details.order_status',$type)
                   
					->orderBy('orders.id','desc');
					
					  $all_record=$sub_orders;
					   
					
					 $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();

                break;
         }
         

	$all_record=$all_record 
        ->get()
        ->toarray();
		$message="Order Listing";
		$api_key='order_data';
		
		echo $this->msg_info($message,$sub_orders,$page,$api_key,$all_record);
	}
     	
    public function listing(Request $request) {
				    
	   $input = json_decode(file_get_contents('php://input'), true);

	   $id=@$input['fld_user_id'];
	   $type=@$input['fld_order_type'];
	   
	   
	   $fld_page_no=@$input['fld_page_no'];
	   $page=$fld_page_no;
	
	   if($page!=0){
		 $fld_page_no=$fld_page_no*10;
	   }
		
         switch($type){
                case 0:
                    $orders=Orders::select(
                    DB::raw(" orders.id as order_id"),
                    'orders.*',
                    DB::raw("( SELECT COUNT(*) FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->where('order_status',0)
                    ->orwhere('order_status',1)
                    ->orwhere('order_status',2)
                    ->where('customer_id',$id)
					->orderBy('orders.id','desc')
                    ->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
                
                case 3:
                      $orders=Orders::select(
                            DB::raw(" orders.id as order_id"),
                            'orders.*'
                          )->where('order_status',$type)->where('customer_id',$id)
					  ->orderBy('orders.id','desc')
					  ->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
				
                case 5:
                      $orders=Orders::select(
                            DB::raw(" orders.id as order_id"),
                            'orders.*'
                          )->where('order_status',$type)->where('customer_id',$id)
					  ->orderBy('orders.id','desc')
                    ->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
                
                case 4:
                    $orders=Orders::select(
                         DB::raw(" orders.id as order_id"),
                        'order_details.suborder_no as order_no',
                        'order_details.id',
                        'order_details.product_price as grand_total',
                        'orders.payment_mode',
                        'orders.order_date',
                        'orders.id as master_id'
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',$type)->where('orders.customer_id',$id)
					->orderBy('orders.id','desc')
					->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();

                break;
         }

		$allrecord=10;
		$message="Order Listing";
		$api_key='order_data';
		
		echo $this->msg_info($message,$orders,$page,$api_key,$allrecord);
	}
	
	public function orderdetail(Request $request)
	{
	   $input = json_decode(file_get_contents('php://input'), true);
	   $id=@$input['fld_user_id'];
	   $order_id=@$input['fld_order_id'];
	   $fld_page_no=@$input['fld_page_no'];
	   $page=$fld_page_no;
	   
	   if($page!=0){
		 $fld_page_no=$fld_page_no*10;
	   }
	   
        //$master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
        $sub_orders=DB::table('order_details')
              ->select(
						'order_details.id as fld_order_id',
						'order_details.suborder_no as fld_suborder_id',
						'order_details.order_detail_invoice_num as fld_invoice_num',
						'order_details.order_detail_invoice_date as fld_invoice_date',
						'order_details.product_name as fld_product_name',
						'order_details.product_price as spcl_price',
						'order_details.product_price_old as price',
						'order_details.order_shipping_charges as fld_shipping_charges',
						'products.short_description',
						DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',products.default_image) AS default_image"),
						'order_details.size as fld_size',
						'order_details.color as fld_color',
						'order_details.order_reward_points as fld_reward_points',
						'order_details.return_days as fld_return_days',
						'order_details.order_status as fld_order_status',
						'order_details.order_date as fld_order_date',
						'order_details.order_updated as fld_order_updated_date',
						'orders_shipping.order_shipping_name as fld_order_shipping_name',
						'orders_shipping.order_shipping_phone as fld_order_shipping_phone',
						'orders_shipping.order_shipping_email as fld_order_shipping_email',
						'orders_shipping.order_shipping_address as fld_order_shipping_address',
						'orders_shipping.order_shipping_address1 as fldorder_shipping_locality',
						'orders_shipping.order_shipping_state as fld_order_shipping_state',
						'orders_shipping.order_shipping_city as fld_order_shipping_city',
						'orders_shipping.order_shipping_zip as fld_order_shipping_zip',
						DB::raw(" 'INDIFASHION' AS fld_seller_name"),
						DB::raw(" 'Policy Title' AS fld_policy_title"),
						DB::raw(" 'Policy text' AS fld_policy_text")
					)
            ->join('products','order_details.product_id','products.id')
            ->join('orders','orders.id','order_details.order_id')
            ->join('orders_shipping','orders.id','orders_shipping.order_id')
            ->where('order_details.id',$order_id)->first();
           
        $allrecord=10;
		$message="Order Detail ";
		$api_key='order_detail_data';
		
		echo $this->msg_info($message,$sub_orders,$page,$api_key,$allrecord);
    }
	
	public function order_invoice(Request $request)
	{
	   $input = json_decode(file_get_contents('php://input'), true);
	   $id=@$input['fld_user_id'];
	   //$order_id=@$input['fld_order_id'];
	   $order_id=$request->fld_order_id;
	   
         $master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
		 $cust_info=DB::table('customers')->select('customers.*')
										->where('id',$master_order->customer_id)->first();
		 $ship_info=DB::table('orders_shipping')->select('orders_shipping.*')
										->where('order_id',$order_id)->first();
			
         $sub_orders=DB::table('order_details')->select('order_details.*','products.hsn_code','products.shipping_charges','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_id',$order_id)
             ->where('order_details.order_status',1)
            ->get();
            
        return view('fronted.mod_template.order_invoice',[
            'myorder'=>'active',
            'master_order'=>$master_order,
			'billing_info'=>$cust_info,
			'shipping_info'=>$ship_info,
            'sub_orders'=>$sub_orders
            ]);
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