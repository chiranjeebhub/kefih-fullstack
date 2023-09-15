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
use App\Coupon;
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\ProductCategories;
use App\ProductImages;
use App\Category;
use Config;

class CartController extends Controller 
{
	public $successStatus = 200;
		public $site_base_path='https://phaukat.com/';
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Cart Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
	 public function validateBuyProducts(){
         	$input = json_decode(file_get_contents('php://input'), true);
			
			//file_put_contents('buy_now_validation.txt',json_encode($input));
         	
		 $coupon_code=$input['fld_coupon_code'];
		 $user_id=$input['fld_user_id'];
		 $product_id=$input['fld_product_id'];
		 $color_id=$input['fld_color_id'];
		 $size_id=$input['fld_size_id'];
		 $women_size_id=$input['fld_women_size_id'];
		 $product_qty=$input['fld_product_qty'];
		 
         if($input['fld_pincode']=='' || $input['fld_user_id']==''){
             echo json_encode(
                 array(
                            "status"=>false,
							"statusCode"=>401,
                            "message"=>"Pincode can not be blank",
                            "product_data"=>array(),
                     )
                 );
         } else{
             $undelivered=array();
			 $delivered=array();
			 $undelievr=0;
			 $data_inputs=array(
                    "pincode"=>$input['fld_pincode'],
                    "price"=>200,
                    "product_name"=>"apple",
                    "qty"=>1,
                    "weight"=>2,
                    "height"=>2,
                    "length"=>2,
                    "width"=>2
                    );
 	    
 	     $back_response=CommonHelper::checkDelivery($data_inputs);
 	
        $output = (array)json_decode($back_response);
           if (array_key_exists("delivery_details",$output))
            {
              
            } else{
               $undelievr=1;
            }
             $all_prouctsInCart = DB::table('products')
		                ->select(
							'products.vendor_id',
							'products.id'
		                )
                        ->where('products.id',$product_id)
						->get()
						->toarray();
						foreach($all_prouctsInCart as $product){
						    
						    
						    $isDelivered=DB::table('logistic_vendor_pincode')
                                                    ->where('vendor_id',$product->vendor_id)
                                                    ->where('pincode',$input['fld_pincode'])
                                                    ->where('status',1)
                                                    ->where('isdeleted',0)
						                            ->first();
						                            if($undelievr==1){
						                                array_push($undelivered,$product->id);
						                            }else{
														array_push($delivered,$product->id);
													}
						}
                
		$removed_coupon_amt=0;
		$ss=Coupon::verifyAppliedCouponRemoveAmt($coupon_code,$user_id,$undelivered);
		
		if(@$ss['RemovedCartAmount']!='')
		{
			$removed_coupon_amt=@$ss['RemovedCartAmount'];
		}
		
		$applied_coupon_amt=0;
		$ss=Coupon::verifyAppliedCouponDiscountAmt($coupon_code,$user_id,$delivered);
		
		if(@$ss['AppliedCartAmount']!='')
		{
			$applied_coupon_amt=@$ss['AppliedCartAmount'];
		}
		
		$record = DB::table('products')
		                ->select(
							'products.name as fld_product_name',
							//'cart.id as fld_cart_id',
							//'cart.qty as fld_product_qty',
							'products.price as fld_product_price',
							'products.spcl_price as fld_spcl_price',
							//'cart.color_id as fld_product_color',
							//'cart.size_id as fld_product_size',
							'products.id as fld_product_id',
							'products.delivery_days as fld_delivery_days',
							'products.shipping_charges as fld_shipping_charges',
                            DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
		                )
		                //->join('products','cart.prd_id','products.id')
		                 ->whereIn('products.id',$undelivered)
						->get()
						->toarray();
						
		    $whole_data=array();
		    foreach($record as $row){
				
				$row->fld_delivery_days='No Delivery';
		        
		        $old_prc=$row->fld_product_price;
		        $prc=0;
				if ($row->fld_spcl_price!='' && $row->fld_spcl_price!=0)
				{
						$prc=$row->fld_spcl_price;
				}else{
						$prc=$row->fld_product_price;
				}
					  
				if($color_id==0 && $size_id!=0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('size_id',$size_id)
								->first();
						$prc+=$attr_data->price;
						$old_prc+=$attr_data->price;
				}
				if($color_id!=0 && $size_id==0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$color_id)
								->first();
						$prc+=$attr_data->price;
						$old_prc+=$attr_data->price;
				}
				 if($color_id!=0 && $size_id!==0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$color_id)
								->where('size_id',$size_id)
								->first();
						$prc+=@$attr_data->price;
						$old_prc+=@$attr_data->price;
				}
				
				if($color_id!=0 && $size_id!==0 && $women_size_id!==0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$color_id)
								->where('size_id',$size_id)
								->where('women_size_id',$women_size_id)
								->first();
						//$prc+=@$attr_data->price;
						//$old_prc+=@$attr_data->price;
				}
				$row->fld_spcl_price=$prc;
				$row->fld_product_price=$old_prc;
		        
				$row->product_coupon_amt="0";
				$product_removed=array();
				array_push($product_removed,$row->fld_product_id);
				
				$ss=Coupon::verifyAppliedCouponProductDiscountAmt($coupon_code,$user_id,$product_removed);
				
				if(@$ss['ProductCouponCartAmount']!='')
				{
					$row->product_coupon_amt=number_format(@$ss['ProductCouponCartAmount'],2);
				}
				
				array_push($whole_data,$row);
				
		    }
			
		    if((sizeof($whole_data))>0){
		        echo json_encode(
                 array(
                            "status"=>true,
							"statusCode"=>201,
                            "message"=>"undelivery products found",
							//"removed_coupon_amt"=>number_format($removed_coupon_amt,2),
							//"applied_coupon_amt"=>number_format($applied_coupon_amt,2),
                            "product_data"=>$whole_data,
					 )
                 );
		    } 
else{
		        echo json_encode(
                 array(
                            "status"=>false,
							"statusCode"=>401,
                            "message"=>"no products found",
							//"removed_coupon_amt"=>number_format($removed_coupon_amt,2),
							//"applied_coupon_amt"=>number_format($applied_coupon_amt,2),
                            "product_data"=>array(),
                     )
                 );
		    }
		  
         }
		
     }
	 
	 
     public function validateCartProducts(){
         	$input = json_decode(file_get_contents('php://input'), true);
         
		 $coupon_code=@$input['fld_coupon_code'];
		 $user_id=$input['fld_user_id'];
		 
         if($input['fld_pincode']=='' || $input['fld_user_id']==''){
             echo json_encode(
                 array(
                            "status"=>false,
							"statusCode"=>401,
                            "message"=>"Pincode can not be blank",
                            "product_data"=>array(),
                     )
                 );
         } else{
             $undelivered=array();
			 $delivered=array();
			 $undelievr=0;
			 $data_inputs=array(
                    "pincode"=>$input['fld_pincode'],
                    "price"=>200,
                    "product_name"=>"apple",
                    "qty"=>1,
                    "weight"=>2,
                    "height"=>2,
                    "length"=>2,
                    "width"=>2
                    );
 	    
 	     $back_response=CommonHelper::checkDelivery($data_inputs);
 	
        $output = (array)json_decode($back_response);
           if (array_key_exists("delivery_details",$output))
            {
              
            } else{
               $undelievr=1;
            }
             $all_prouctsInCart = DB::table('cart')
		                ->select(
							'products.vendor_id',
							'cart.id as cart_id'
		                )
                        ->join('products','cart.prd_id','products.id')
                        ->where('cart.user_id',$input['fld_user_id'])
						->get()
						->toarray();
						foreach($all_prouctsInCart as $product){
						    
						    
						    $isDelivered=DB::table('logistic_vendor_pincode')
                                                    ->where('vendor_id',$product->vendor_id)
                                                    ->where('pincode',$input['fld_pincode'])
                                                    ->where('status',1)
                                                    ->where('isdeleted',0)
						                            ->first();
						                            if($undelievr==1){
						                                array_push($undelivered,$product->cart_id);
						                            }else{
														array_push($delivered,$product->cart_id);
													}
						}
                
		$removed_coupon_amt=0;
		$ss=Coupon::verifyAppliedCouponRemoveAmt($coupon_code,$user_id,$undelivered);
		
		if(@$ss['RemovedCartAmount']!='')
		{
			$removed_coupon_amt=@$ss['RemovedCartAmount'];
		}
		
		$applied_coupon_amt=0;
		$ss=Coupon::verifyAppliedCouponDiscountAmt($coupon_code,$user_id,$delivered);
		
		if(@$ss['AppliedCartAmount']!='')
		{
			$applied_coupon_amt=@$ss['AppliedCartAmount'];
		}
		
		$record = DB::table('cart')
		                ->select(
							'products.name as fld_product_name',
							'cart.id as fld_cart_id',
							'cart.qty as fld_product_qty',
							'products.price as fld_product_price',
							'products.spcl_price as fld_spcl_price',
							'cart.color_id as fld_product_color',
							'cart.size_id as fld_product_size',
							'cart.prd_id as fld_product_id',
							'products.delivery_days as fld_delivery_days',
							'products.shipping_charges as fld_shipping_charges',
                            DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
		                )
		                ->join('products','cart.prd_id','products.id')
		                 ->whereIn('cart.id',$undelivered)
						->get()
						->toarray();
						
		    $whole_data=array();
		    foreach($record as $row){
				
				$row->fld_delivery_days='No Delivery';
		        
		        $old_prc=$row->fld_product_price;
		        $prc=0;
				if ($row->fld_spcl_price!='' && $row->fld_spcl_price!=0)
				{
						$prc=$row->fld_spcl_price;
				}else{
						$prc=$row->fld_product_price;
				}
					  
				if($row->fld_product_color==0 && $row->fld_product_size!=0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('size_id',$row->fld_product_size)
								->first();
						$prc+=$attr_data->price;
						$old_prc+=$attr_data->price;
				}
				if($row->fld_product_color!=0 && $row->fld_product_size==0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$row->fld_product_color)
								->first();
						$prc+=$attr_data->price;
						$old_prc+=$attr_data->price;
				}
				 if($row->fld_product_color!=0 && $row->fld_product_size!==0){
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$row->fld_product_color)
								->where('size_id',$row->fld_product_size)
								->first();
						$prc+=@$attr_data->price;
						$old_prc+=@$attr_data->price;
				}
				$row->fld_spcl_price=$prc;
				$row->fld_product_price=$old_prc;
		        
				$row->product_coupon_amt="0";
				$product_removed=array();
				array_push($product_removed,$row->fld_cart_id);
				
				$ss=Coupon::verifyAppliedCouponProductDiscountAmt($coupon_code,$user_id,$product_removed);
				
				if(@$ss['ProductCouponCartAmount']!='')
				{
					$row->product_coupon_amt=number_format(@$ss['ProductCouponCartAmount'],2);
				}
				
				array_push($whole_data,$row);
				
		    }
			
		    if((sizeof($whole_data))>0){
		        echo json_encode(
                 array(
                            "status"=>true,
							"statusCode"=>201,
                            "message"=>"undelivery products found",
							//"removed_coupon_amt"=>number_format($removed_coupon_amt,2),
							//"applied_coupon_amt"=>number_format($applied_coupon_amt,2),
                            "product_data"=>$whole_data,
					 )
                 );
		    } 
else{
		        echo json_encode(
                 array(
                            "status"=>false,
							"statusCode"=>401,
                            "message"=>"no products found",
							//"removed_coupon_amt"=>number_format($removed_coupon_amt,2),
							//"applied_coupon_amt"=>number_format($applied_coupon_amt,2),
                            "product_data"=>array(),
                     )
                 );
		    }
		  
         }
		
     }
     public function shipping_charges(Request $request){
         $shipping_charges_details=CommonHelper::getShippingDetails();
         echo json_encode(
                 array(
                            "status"=>true,
							"statusCode"=>200,
                            "message"=>"shipping charges found",
                            "data"=>$shipping_charges_details
                     )
                 );
        ;
     }
    public function cart_listing(Request $request) {
				      $shipping_charges_details=CommonHelper::getShippingDetails();
		$input = json_decode(file_get_contents('php://input'), true);
	
// 		 file_put_contents('cart'.date('Y-m-d H:i:s').'.txt',json_encode($input));
				    $save_total=0;
				    $shipping_total=0;
				    $cart_total=0;
		$record = DB::table('cart')
		                ->select(
							'products.name as fld_product_name',
							'cart.qty as fld_product_qty',
							'products.price as fld_product_price',
							'products.spcl_price as fld_spcl_price',
							'cart.color_id as fld_product_color',
							'cart.size_id as fld_product_size',
                            DB::raw('"NA" AS fld_product_color_name'),
                            DB::raw('"NA" AS fld_product_size_name'),
							'cart.w_size_id as women_sizes',
							 DB::raw('"NA" AS women_sizes_name'),
							'cart.prd_id as fld_product_id',
							'products.delivery_days as fld_delivery_days',
							'products.shipping_charges as fld_shipping_charges',
                            DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
		                )
		                ->join('products','cart.prd_id','products.id')
		                
						->where('user_id',$input['fld_user_id'])
						->get()
						->toarray();
						
		$total_data=$ship_total_data=$save_total_data=0;
		
		if($record){
		    $whole_data=array();
		    foreach($record as $row){
		        $row->fld_delivery_days='Delivery by '.rand(date('d')+1,30).' '.date('M').' '.date('D');
		        
		        $old_prc=$row->fld_product_price;
		        $prc=0;
				if ($row->fld_spcl_price!='' && $row->fld_spcl_price!=0)
				{
						$prc=$row->fld_spcl_price;
				}else{
						$prc=$row->fld_product_price;
				}
					  
					  if($row->women_sizes!=0){
					       $w_size_name=Sizes::where('id',$row->women_sizes)->first();
					      $row->women_sizes_name=$w_size_name->name;
					  }
				if($row->fld_product_color==0 && $row->fld_product_size!=0){
                            $size_name=Sizes::where('id',$row->fld_product_size)->first();
                            $row->fld_product_size_name=$size_name->name;
				       
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('size_id',$row->fld_product_size)
								->first();
						$prc+=$attr_data->price;
						$old_prc+=$attr_data->price;
				}
				if($row->fld_product_color!=0 && $row->fld_product_size==0){
                        $color_name=Colors::where('id',$row->fld_product_color)->first();
                        $row->fld_product_color_name=@$color_name->name;
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$row->fld_product_color)
								->first();
						$prc+=$attr_data->price;
						$old_prc+=$attr_data->price;
				}
				 if($row->fld_product_color!=0 && $row->fld_product_size!==0){
            $color_name=Colors::where('id',$row->fld_product_color)->first();
            $size_name=Sizes::where('id',$row->fld_product_size)->first();
            $row->fld_product_color_name=@$color_name->name;
            $row->fld_product_size_name=$size_name->name;
                                
                            
                            
						$attr_data=DB::table('product_attributes')
								->where('product_id',$row->fld_product_id)
								->where('color_id',$row->fld_product_color)
								->where('size_id',$row->fld_product_size)
								->first();
						$prc+=@$attr_data->price;
						$old_prc+=@$attr_data->price;
				}
				$row->fld_spcl_price=$prc;
				$row->fld_product_price=$old_prc;
		        array_push($whole_data,$row);
				
				$total_data+=$prc*$row->fld_product_qty;
				$ship_total_data+=$row->fld_shipping_charges*$row->fld_product_qty;
				//$save_total_data+=$row->fld_spcl_price*$row->fld_product_qty;
				$save_total_data+=($row->fld_product_price-$row->fld_spcl_price)*$row->fld_product_qty;
		    }
		    
		    if($shipping_charges_details->cart_total>$total_data){
				$ship_total_data=$shipping_charges_details->shipping_charge;
			} 
			
			$statusCode=201;
			$message="Cart Listing";
			$cart_data=$whole_data;
			$save_total=$save_total_data;
			$shipping_total=$ship_total_data;
			$cart_total=$total_data;
		}else{
			$statusCode=404;
			$message="No Cart Found";
			$cart_data=array();
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"cart_data"=>$cart_data,
					"save_total"=>$save_total,
					"shipping_total"=>$shipping_total,
					"cart_total"=>$cart_total
				);
		
		echo json_encode($res);
	}
	
	/** 
     * Cart Add/Update/Delete api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function cart_add_update(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	 
		
			
		$action_type=$input['fld_action_type'];
		$error=0;
	    $code='404';
		
		if($action_type==0)	
		{
		    
		     $dt=ProductAttributes::getProductQty($input['fld_product_id'],$input['fld_size_id'],$input['fld_color_id']);
		  
		     if((@$dt->qty)>=@$input['fld_product_qty']){
		         $data=array(
                    'prd_id'=>$input['fld_product_id'],
                    'user_id'=>$input['fld_user_id'],
                    'w_size_id'=>$input['fld_women_size_id'],
                    'size_id'=>$input['fld_size_id'],
                    'color_id'=>$input['fld_color_id'],
                    'qty'=>$input['fld_product_qty']
					);
		    $record=DB::table('cart')
						->where('prd_id',$input['fld_product_id'])
						->where('user_id',$input['fld_user_id'])
						->first();
						
						    
						if($record){
						  
                            $msg="product already in cart";
                            $code='404';
                            $error=1;
						} else{
                                $record=DB::table('cart')
                                ->insert($data);
						     	$msg="Product Added to cart";
						     		$code='201';
						}
		     } else{
                    $msg="No more quantity in stock";
                    $code='404';
                    $error=1;
		     }
			
		
		}elseif($action_type==1){
			$record=DB::table('cart')
						->where('prd_id',$input['fld_product_id'])
						->where('user_id',$input['fld_user_id'])
						->delete();
					
			$msg="Cart Deleted";
				$code='201';
		}
		
		elseif($action_type==2){
		    
			if(($input['fld_product_qty'])>0){
			    
			    
			     $dt=ProductAttributes::getProductQty($input['fld_product_id'],$input['fld_size_id'],$input['fld_color_id']);
		     if(($dt->qty)>=$input['fld_product_qty']){
		         $data=array(
                    'prd_id'=>$input['fld_product_id'],
                    'user_id'=>$input['fld_user_id'],
                    'w_size_id'=>$input['fld_women_size_id'],
                    'size_id'=>$input['fld_size_id'],
                    'color_id'=>$input['fld_color_id'],
                    'qty'=>$input['fld_product_qty']
					);
			$record=DB::table('cart')
						->where('prd_id',$input['fld_product_id'])
						->where('user_id',$input['fld_user_id'])
						->update($data);
                    $msg="Cart Updated";
                    $code='201';
		     } else{
		            $error=1;
                    $msg="No more quantity in stock";
                    $code='201';
		     }
			    		
                       
			} else{
			      $error=1;
			      $msg="Please check qty";
                $code='201';
                   
			}
			
			
		}else{
		    $error=1;	
            $msg="something went wrong";
            $code='404';
		}
		
		
		if($error==0){
        	  if($record){
        	    $status=true;
        		$statusCode=$code;
        		$message=$msg;
        		$cart_data=$record;
        	}else{
        		$status=false;
        		$statusCode=404;
        		$message="No Cart Found";
        		$cart_data=null;
        	}  
		} else{
		    $status=false;
            $statusCode=404;
            $message=$msg;
            $cart_data=null;
		}
		
		
		$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"cart_update_data"=>$cart_data
				);
		
		echo json_encode($res);
	}
	
	


}