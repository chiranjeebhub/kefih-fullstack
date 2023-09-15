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
use App\OrdersDetail;
use App\Products;
use App\OrdersShipping;
use App\Customer;
use Config;

class OrderController extends Controller 
{
	public $successStatus = 200;
	public $site_base_path='https://phaukat.com/';
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	 
	  public function test_order_url(Request $request) {
	      $input = json_decode(file_get_contents('php://input'), true);
	   
	       DB::table('order_details')->where('id',$request->fld_order_id)->update(array(
                  "order_status"=>0
                  ));
                  
                  echo json_encode(array("msg"=>"updated"));
	  }
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
       public function cancel_return_order(Request $request) {
       
            $input =$request->all();
      
          
          
            switch($input['return_type']){
                    case 0:
                                $msg="Your order has been cancelled";
                    
                    $cancel_return_data=array(
                    "sub_order_id"=>$input['fld_sub_order_id'],
                    "reason"=>$input['fld_reason_id'],
                    "type"=>4,
                    "comments"=>$input['fld_comments']
                               );
                       $res=DB::table('cancel_return_refund_order')->insert($cancel_return_data);
                               if($res){
                                  DB::table('order_details')->where('id',$input['fld_sub_order_id'])->update(array(
                                      "order_status"=>4
                                      ));
                                 $out=array(
                        			"status"=>true,
                        			"statusCode"=>201,
                        			"message"=>$msg
                        			); 
                        		
                                    $this->generateMailforCancelAndReturnOrder($input['fld_sub_order_id'],0);
                        		
                        		
                                  	
                              } else{
                                  $out=array(
                        			"status"=>true,
                        			"statusCode"=>404,
                        			"message"=>"Something went wrong"
                        			);
                                 	
                              }
                       
                    break;
                    
                    case 1:
                                   $msg="Your refund request is saved";
                    
                    $cancel_return_data=array(
                    "sub_order_id"=>$input['fld_sub_order_id'],
                    "reason"=>$input['fld_reason_id'],
                    "type"=>5,
                    "comments"=>$input['fld_comments']
                               );
                       $res=DB::table('cancel_return_refund_order')->insert($cancel_return_data);
                               if($res){
                                  DB::table('order_details')->where('id',$input['fld_sub_order_id'])->update(array(
                                      "order_status"=>5,
                                       'return_what'=>0
                                      ));
                            $order_previous_data=OrdersDetail::where('id',$input['fld_sub_order_id'])->first();
                            $order_previous_msater=Orders::where('id',$input['fld_order_id'])->first();
                            $color_name='';
                            if($input['color_id']!='' && $input['color_id']!=null){
                                	$color_record =DB::table('colors')->select(
										'colors.id as fld_color_id',
										'colors.name as fld_color_name'
									)
									->where('colors.id',$input['color_id'])
									->first();
									if($color_record){
									    $color_name=$color_record->fld_color_name;
									}
                            }
                             $size_name='';
                            if($input['size_id']!='' && $input['size_id']!=null){
                                	$size_record =DB::table('sizes')->select(
										'sizes.id as fld_size_id',
										'sizes.name as fld_size_name'
									)
									->where('sizes.id',$input['size_id'])
									->first();
									if($size_record){
									    $size_name=$size_record->fld_size_name;
									}
                            }
                                      
                                      $order_detail_for_replace=array(
                            'suborder_no'=>$order_previous_msater->order_no.'_item_'.$order_previous_data->product_id,
                            'order_id'=>$order_previous_data->order_id,
                            'sub_order_id'=>$input['fld_sub_order_id'],
                            'product_id'=>$order_previous_data->product_id,
                            'product_name'=>$order_previous_data->product_name,
                            'product_qty'=>$order_previous_data->product_qty,
                            'product_price'=>$order_previous_data->product_price,
                            'product_price_old'=>  $order_previous_data->product_price_old,
                            'size'=>$size_name,
                            'color'=> $color_name,
                            'size_id'=>$input['size_id'],
                            'color_id'=>$input['color_id'],
                            'w_size_id'=>$order_previous_data->w_size_id,
                            'w_size'=>$order_previous_data->w_size,
                       
                            'order_reward_points'=>$order_previous_data->order_reward_points,
                            'order_shipping_charges'=>$order_previous_data->order_shipping_charges,
                            'order_commission_rate'=>$order_previous_data->order_commission_rate,
                            'return_days'=>$order_previous_data->return_days,
                            
                            'account_holder_name'=>$input['account_holder_name'],
                            'account_no'=>$input['account_no'],
                            'bank_name'=>$input['bank_name'],
                            'ifsc_code'=>$input['ifsc_code'],
                            'branch'=>$input['branch'],
                            
                            'remarks'=>$input['fld_comments'],
                            'return_type'=>0
                            );
				
			         	$res=DB::table('replace_order_details')->insert($order_detail_for_replace);
			         	

                                 $out=array(
                        			"status"=>true,
                        			"statusCode"=>201,
                        			"message"=>$msg
                        			); 
                        		
                                    $this->generateMailforCancelAndReturnOrder($input['fld_sub_order_id'],1);
                        		
                        		
                                  	
                              } else{
                                  $out=array(
                        			"status"=>true,
                        			"statusCode"=>404,
                        			"message"=>"Something went wrong"
                        			);
                                 	
                              }
                    break;
                    
                    
                    case 2:
                                   $msg="Your replce request is saved";
                    
                    $cancel_return_data=array(
                    "sub_order_id"=>$input['fld_sub_order_id'],
                    "reason"=>$input['fld_reason_id'],
                    "type"=>5,
                     "return_type"=>1,
                    "comments"=>$input['fld_comments']
                               );
                       $res=DB::table('cancel_return_refund_order')->insert($cancel_return_data);
                               if($res){
                                  DB::table('order_details')->where('id',$input['fld_sub_order_id'])->update(array(
                                      "order_status"=>5,
                                       'return_what'=>1
                                      ));
                            $order_previous_data=OrdersDetail::where('id',$input['fld_sub_order_id'])->first();
                            $order_previous_msater=Orders::where('id',$input['fld_order_id'])->first();
                                      
                            $order_detail_for_replace=array(
                            //'suborder_no'=>$order_previous_msater->order_no.'_item_'.$order_previous_data->product_id,
                             'suborder_no'=>$order_previous_data->suborder_no,
                            'order_id'=>$order_previous_data->order_id,
                            'sub_order_id'=>$input['fld_sub_order_id'],
                            'product_id'=>$order_previous_data->product_id,
                            'product_name'=>$order_previous_data->product_name,
                            'product_qty'=>$order_previous_data->product_qty,
                            'product_price'=>$order_previous_data->product_price,
                            'product_price_old'=>  $order_previous_data->product_price_old,
                            'size'=>  $order_previous_data->size,
                            'color'=> $order_previous_data->color,
                            'size_id'=>$order_previous_data->size_id,
                            'w_size_id'=>$order_previous_data->w_size_id,
                            'w_size'=>$order_previous_data->w_size,
                            'color_id'=>$order_previous_data->color_id,
                            'order_reward_points'=>$order_previous_data->order_reward_points,
                            'order_shipping_charges'=>$order_previous_data->order_shipping_charges,
                            'order_commission_rate'=>$order_previous_data->order_commission_rate,
                            'return_days'=>$order_previous_data->return_days,
                            
                            'account_holder_name'=>$input['account_holder_name'],
                            'account_no'=>$input['account_no'],
                            'bank_name'=>$input['bank_name'],
                            'ifsc_code'=>$input['ifsc_code'],
                            'branch'=>$input['branch'],
                            
                            'remarks'=>$input['fld_comments'],
                            'return_type'=>1
                            );
				
			         	$res=DB::table('replace_order_details')->insert($order_detail_for_replace);

                                 $out=array(
                        			"status"=>true,
                        			"statusCode"=>201,
                        			"message"=>$msg
                        			); 
                        		
                                    $this->generateMailforCancelAndReturnOrder($input['fld_sub_order_id'],1);
                        		
                        		
                                  	
                              } else{
                                  $out=array(
                        			"status"=>true,
                        			"statusCode"=>404,
                        			"message"=>"Something went wrong"
                        			);
                                 	
                              }
                    break;
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
	   file_put_contents('orderListing.txt',json_encode($input));
	  
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
							
								'order_details.order_deduct_reward_points as fld_order_deduct_reward_points',
								'order_details.id as fld_suborder_id',
								'order_details.product_id as fld_product_id',
								'order_details.product_name as fld_product_name',
								'order_details.product_price as spcl_price',
								'order_details.product_price_old as price',
								'order_details.product_qty as fld_order_qty',
								'products.short_description',
                                'orders.delivery_date as delivery_date',
                                'orders.delivery_time as delivery_time',
									
								'products.delivery_days',
								DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image"),
								DB::raw("CONCAT('".$this->site_base_path."api/order_invoice/',orders.id) AS order_invoice"),
								'order_details.order_detail_invoice_num',
								'order_details.size as fld_size',
								'order_details.color as fld_color',
								'order_details.order_status as order_status',
								'order_details.order_date as fld_order_date',
								'order_details.order_updated as fld_order_updated_date',
								'order_details.order_reward_points as fld_reward_points',
								'order_details.return_days as fld_return_days',
								DB::raw(" '' as fld_shipping_date"),
								  'orders.payment_mode',
								DB::raw(" 0 as fld_track_id"),
								DB::raw(" 'true' as fld_return_flag")
							)
						 ->join('orders','order_details.order_id','orders.id')
						 ->join('products','order_details.product_id','products.id')
						 ->where('orders.customer_id',$id)->orderBy('orders.id','desc');;
         switch($type){
                case 0:
                    $sub_orders=$sub_orders
                    ->Where(function($query) use ($id){
							 $query->orWhere('order_details.order_status',0);
							 $query->orWhere('order_details.order_status',1);
							 $query->orWhere('order_details.order_status',2);
							 
						 });
                    
					
                        $all_record=$sub_orders;
                      $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray(); 
                break;
                
                case 3:
                      $sub_orders=$sub_orders
                    ->where('order_details.order_status',$type);
                   
                      $all_record=$sub_orders;
                      
                     $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
				
                case 5:
                     $sub_orders=$sub_orders
                        ->where('order_details.order_status',$type);
                      
					  
					   $all_record=$sub_orders;
					   
                     $sub_orders=$sub_orders->offset($fld_page_no)
                    ->limit(10)
                    ->get()
                    ->toarray();
                break;
                
                case 4:
                    $sub_orders=$sub_orders
                    ->where('order_details.order_status',$type);
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
        
        $datats=array();
        foreach($sub_orders as $ode){
            $data=Products::productDetails($ode->fld_product_id); 
             $days= $data->return_days;
             $return_date= date('Y-m-d', strtotime($ode->fld_order_updated_date. ' + '.$days.' days'));
            $today= date('Y-m-d');
            if($today<$return_date){
                $ode->fld_return_flag=true;
            }else{
                 $ode->fld_return_flag=false; 
            }
            
            array_push($datats,$ode);
        }
		$message="Order Listing";
		$api_key='order_data';
		
	
		echo $this->msg_info($message,$datats,$page,$api_key,$all_record);
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
            file_put_contents("orderDetails.txt",json_encode($input));
	   $id=@$input['fld_user_id'];
	   $order_id=@$input['fld_order_id'];

	   
	   $fld_page_no=@$input['fld_page_no'];
	   $page=$fld_page_no;
	   
	   if($page!=0){
		 $fld_page_no=$fld_page_no*10;
	   }
	   
	   
	   $prd_img=$this->site_base_path.'uploads/products/';
        //$master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
        $sub_orders=DB::table('order_details')
              ->select(
                			'orders.id as fld_order_id',
                			DB::raw("FORMAT(order_details.order_deduct_reward_points,2) as fld_order_deduct_reward_points"),
                			DB::raw("FORMAT(order_details.order_coupon_amount,2) as coupon_amount"),
                			'order_details.id as fld_suborder_id',
						'order_details.order_detail_invoice_num as fld_invoice_num',
						'order_details.order_detail_invoice_date as fld_invoice_date',
                            'orders.delivery_date as delivery_date',
                            'orders.delivery_time as delivery_time',
						'order_details.product_id as fld_order_product_id',
						'order_details.product_name as fld_product_name',
						'order_details.product_price as spcl_price',
						'order_details.product_price_old as price',
						'order_details.product_qty as fld_order_qty',
						'order_details.order_shipping_charges as fld_shipping_charges',
						'products.short_description',
						DB::raw("CONCAT('$prd_img',products.default_image) AS default_image"), 
						DB::raw("(SELECT product_rating.rating FROM product_rating WHERE product_rating.user_id = orders.customer_id && product_rating.product_id = order_details.product_id) as fld_product_rating_count"),
						DB::raw("(SELECT vendor_rating.rating FROM vendor_rating WHERE vendor_rating.user_id = orders.customer_id && vendor_rating.product_id = order_details.product_id) as fld_seller_rating_count"),
						'order_details.w_size as fld_wsize',
						'order_details.size as fld_size',
						'order_details.color as fld_color',
						'product_attributes.unisex_type',
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
						'vendors.username as fld_seller_name',
					
						DB::raw(" 'Policy Title' AS fld_policy_title"),
						DB::raw(" 'Policy text' AS fld_policy_text")
					)
                ->leftjoin('products','order_details.product_id','products.id')
                ->leftjoin('vendors','vendors.id','products.vendor_id')
                ->leftjoin('orders','orders.id','order_details.order_id')
                ->leftjoin('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                ->leftjoin('orders_shipping','orders.id','orders_shipping.order_id')
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
	
	 public function generateMailforCancelAndReturnOrder($order_id,$type=0){
        //   type => 0 for cancel and 1 =>return 
        $opeation_pr='Return';
        if($type==0){
            $opeation_pr='Cancel';
        }
                $master_orders=OrdersDetail::where('id',$order_id)->first();
                $master_order=Orders::where('id',$master_orders->order_id)->first();
                $product_data = DB::table('products')->where('id',$master_orders->product_id)->first();
                
                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();

				if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_cancelled",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no
                        )
                        ) )->render();					
					
					}
				else {
					$msg=view("message_template.online_order_cancelled",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no
                        )
                        ) )->render();					
				}

                //$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your request for '.$opeation_pr.' order('.$master_orders->suborder_no.') is proceesing.';
                
                    
                    $mode= ($master_order->payment_mode==0)?"COD":"Paid";
                    
                    $email_msg=view("emails_template.order_cancel",
                        array(
                        'data'=>array(
                                        'mode'=>$mode,
                                        'operation_process'=>$opeation_pr,
                                        'customer_fname'=>$customer_data->name,
                                        'customer_lname'=>$customer_data->last_name,
                                        'suborder_no'=>$master_orders->suborder_no,
                                        'order_date'=>$master_order->order_date,
                                        'shipping_data'=>array(
                                                                  'shipping_name' => $shipping_data->order_shipping_name,
                                                                  'shipping_phone' =>  $shipping_data->order_shipping_phone,
                                                                  'shipping_email' => $shipping_data->order_shipping_email,
                                                                  'shipping_address' => $shipping_data->order_shipping_address,
                                                                  'shipping_address1' => $shipping_data->order_shipping_address1,
                                                                  'shipping_address2' => $shipping_data->order_shipping_address2,
                                                                  'shipping_city' =>  $shipping_data->order_shipping_city,
                                                                  'shipping_state' => $shipping_data->order_shipping_state,
                                                                  'shipping_zip' => $shipping_data->order_shipping_zip
                                                                ),
                                        'product_details' => array(
                                                                    'product_name' => $master_orders->product_name,
                                                                    'product_image' => $product_data->default_image,
                                                                    'product_size' => $master_orders->size,
                                                                    'product_color' => $master_orders->color,
                                                                    'product_qty' => $master_orders->product_qty,
                                                                    'product_price'=> $master_orders->product_price,
                                                                    'order_shipping_charges'=>$master_orders->order_shipping_charges,
                                                                    'order_cod_charges'=>$master_orders->order_cod_charges,
                                                                    'order_coupon_amount'=>$master_orders->order_coupon_amount,
                                                                    'order_wallet_amount'=>$master_orders->order_wallet_amount,
                                                                    )
                                     )
                                     
                        ) )->render();
                        
                        // echo $email_msg;die;
                        
    //                 $email_msg='<tr>
    //             	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
    //             	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
    //                 <p>We have received your query for '.$opeation_pr.' order. We will send you an Email and SMS for further process</p>
    //                 <p>
    //                 Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
    //                 Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
    //                 Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
    //                 </p>
    //             </td>
    //         </tr>
    //         <tr>
    //             <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
    //             	<p><strong>Billing Info</strong><br />
    //                         '.$shipping_data->order_shipping_name.'<br />
    //                         '.$shipping_data->order_shipping_phone.'<br />
    //                         '.$shipping_data->order_shipping_email.'<br />
    //                         '.$shipping_data->order_shipping_address.'<br />
    //                         '.$shipping_data->order_shipping_address1.'<br />
    //                         '.$shipping_data->order_shipping_address2.'<br />
    //                         '.$shipping_data->order_shipping_city.'<br />
    //                         '.$shipping_data->order_shipping_state.'<br />
    //                         '.$shipping_data->order_shipping_zip.'<br />
    //                 </p
    //             </td>
    //             <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
    //             	<p><strong>Pick up Info</strong><br />
    //                 '.$shipping_data->order_shipping_name.'<br />
    //                 '.$shipping_data->order_shipping_phone.'<br />
    //                 '.$shipping_data->order_shipping_email.'<br />
    //                 '.$shipping_data->order_shipping_address.'<br />
    //                 '.$shipping_data->order_shipping_address1.'<br />
    //                 '.$shipping_data->order_shipping_address2.'<br />
    //                 '.$shipping_data->order_shipping_city.'<br />
    //                 '.$shipping_data->order_shipping_state.'<br />
    //                 '.$shipping_data->order_shipping_zip.'<br />
                        
    //                 </p>
    //             </td>
    //         </tr> 
    //         <tr>
    //         	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
    //             	<p>Order Summary</p>
    //             </td>
    //         </tr>';
            
    //         $email_msg.='<tr>
    //         	<td colspan="2">
    //             	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
    //                   <tr>
    //                     <th style="padding:5px 0px;">S.no.</th>
    //                     <th>Item Name</th>
				// 		<th>Quantity</th>
    //                     <th>Price</th>
				// 		<th>Amt</th>
    //                   </tr>';
                      
    //                   $i=1;
    //                 $email_msg.='<tr>
    //                 <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty*$master_orders->product_price.'</td></tr>';
                            
                        //---------------------------------------------   
                      
						
						
    //                  $email_msg.='<tr>
    //                     <td style="padding:5px 10px;">&nbsp;</td>
    //                     <td>&nbsp;</td>
    //                     <td>&nbsp;</td>
				// 		<td>&nbsp;</td>
    //                     <td>Total Amount</td>
    //                     <td>hyh</td>
    //                   </tr>
                   
    //               if($master_order->coupon_code!=''){
    //                   $email_msg.='
				// 	    <tr>
				// 		<td colspan="5"><p>Discount Applied Code <strong>'.$master_order->coupon_code.'</strong> to get </p></td>
				// 		<td><strong>'.$master_order->discount_amount.'</strong></td>
				// 	 </tr>';
    //               }
    //               if($master_order->total_shipping_charges!=''){
    //                   $email_msg.='
				// 	    <tr>
				// 		<td colspan="5"><p>Shiiping Charge</td>
				// 		<td><strong>'.$master_order->total_shipping_charges.'</strong></td>
				// 	 </tr>';
    //               }
                   //-----------------------------------  
					 
            //         $email_msg.='<tr bgcolor="#d1d4d1">
            //         <td style="padding:5px 10px;">&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td><strong>Total Amount Rs.:'.$master_orders->product_qty*$master_orders->product_price.' </strong></td>
            //         </tr>';
					  
					
					  
            //         $email_msg.='</table>

            //     </td>
            // </tr>';
            
            
	           //$email_data = [
            //                 'to'=>$customer_data->email,
            //                 'subject'=>$opeation_pr.' Order',
            //                 "body"=>view("emails_template.order_sts_change",
            //                 array(
            //                 'data'=>array(
            //                 'message'=>$email_msg
            //                 )
            //                 ) )->render(),
            //                 'phone'=>$customer_data->phone,
            //                 'phone_msg'=>$msg
            //              ];
            
            
            
            $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>$opeation_pr.' Order',
                            "body"=>$email_msg,
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
            CommonHelper::SendmailCustom($email_data);
           CommonHelper::SendMsg($email_data);
      }
	
}