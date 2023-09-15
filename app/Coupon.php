<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use App\CouponDetails;
use App\Products;
use App\Orders;
use App\Helpers\CommonHelper;

class Coupon extends Model
{
	
	
    protected $table = 'coupons';

    public function CouponDetail()
	{
		$data=$this->hasMany("App\CouponDetails","coupon_id");
	   return $data;
	}
	
	public static function perPersonUsedCoupon($user_id,$coupon_code){
	    
	    
	    
	     $response = array(
                        "Error" => 0,
                        "Msg" => "continue"
                    );
                    
	    $total_used_by_this_customer=Orders::select('id')->where('customer_id',$user_id)
	                                        ->where('coupon_code',$coupon_code->coupon_code)
	                                        ->where('order_status','!=',7)
	                                        ->count();
	   if($coupon_code->uses_per_user<=$total_used_by_this_customer){
	       $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid"
                    );
                    
	   }
	   
	   return $response;
	    
	}
	
	public static function maxCustomerUsed($coupon_code){
	     $response = array(
                        "Error" => 0,
                        "Msg" => "continue"
                    );
                    
	  $total_used_by_this_customer=Orders::select('id')
	                                        ->where('coupon_code',$coupon_code->coupon_code)
	                                         ->where('order_status','!=',7)
	                                        ->count();
	   if($coupon_code->number_of_user<=$total_used_by_this_customer){
	       $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid"
                    );
                    
	   }
	   
	   return $response;
	    
	}
	
	public static function forNewCustomer($user_id){
	     $response = array(
                        "Error" => 0,
                        "Msg" => "continue"
                    );
                    
	    $orderPlaced=Orders::select('id')->where('customer_id',$user_id)
	                                        ->where('order_status','!=',7)
	                                        ->count();
	   if($orderPlaced>0){
	       $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid"
                    );
                    
	   }
	   
	   return $response;
	    
	}
	
	//Removed Product Starts
	public static function verifyAppliedCouponProductDiscountAmt($code,$user_id,$cart_id){
	      
	    $coupondata =CouponDetails::select(
										'coupons.coupon_type',
										'coupons.id',
										'coupons.coupon_type',
										'coupons.max_discount',
										'coupons.below_cart_amt',
										'coupons.above_cart_amt',
										'coupons.started_date',
										'coupons.end_date',
										'coupons.max_discount',
										'coupons.discount_value',
										'coupons.total_coupon',
										'coupon_details.coupon_code'
									)
							->join('coupons','coupons.id','coupon_details.coupon_id')
							->where('coupon_details.coupon_code',$code)
							->where('coupon_details.coupon_used',0)
							->where('coupons.status',1)
							->first();
	   if($coupondata){
	       
	       $obj=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	       
	       if($obj){
	    
			switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
														->join('products','products.id','cart.prd_id')
														->join('product_categories','product_categories.product_id','products.id')
														->join('categories','product_categories.cat_id','categories.id')
														->where('user_id',$user_id)
														->whereIn('cart.id',$cart_id)
														->where('categories.id',$obj->fld_assign_type_id)
														->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=$discount_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
									$product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
									$prd_attr=DB::table('product_attributes')
													->where('size_id',$categoryProductIncart->size_id)
													->where('color_id',$categoryProductIncart->color_id)
													->where('product_id',$categoryProductIncart->prd_id)
													->first();
                                
									$total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;
                               
									if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
										$total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
									}
									$cart_total+=$total;
									$discount_total+=($total*$coupondata->discount_value)/100;
                            }
                            
                            if($coupondata->max_discount<$discount_total){
                            $discount_total=$coupondata->max_discount;
                            }
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$cart_total;
                            //$response= self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"ProductCouponCartAmount"=>$input['cart_total']
											);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break; 
                
                case 2: // brand wise assign
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
													->join('products','products.id','cart.prd_id')
													->join('brands','products.product_brand','brands.id')
													->where('user_id',$user_id)
													->whereIn('cart.id',$cart_id)
													->where('brands.id',$obj->fld_assign_type_id)
													->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=$discount_total=0;
                            foreach($brandProductIncarts as $brandProductIncart){
                                $product_data=Products::select('price','spcl_price')->where('id',$brandProductIncart->prd_id)->first();
								$prd_attr=DB::table('product_attributes')
													->where('size_id',$brandProductIncart->size_id)
													->where('color_id',$brandProductIncart->color_id)
													->where('product_id',$brandProductIncart->prd_id)
													->first();
                                
								$total=($product_data->price+$prd_attr->price)*$brandProductIncart->qty;
                               
								if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
									$total=($product_data->spcl_price+$prd_attr->price)*$brandProductIncart->qty;
								}
                                $cart_total+=$total;
								$discount_total+=($total*$coupondata->discount_value)/100;
                            }
							
							if($coupondata->max_discount<$discount_total){
                            $discount_total=$coupondata->max_discount;
                            }
							
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$cart_total;
							//$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"ProductCouponCartAmount"=>$input['cart_total']
										);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break;
                
                case 3: // product wise assign
                 
                        $prdIncart=DB::table('cart')
												->where('user_id',$user_id)
												->whereIn('cart.id',$cart_id)
												->where('prd_id',$obj->fld_assign_type_id)
												->first();
                        if($prdIncart){
                            $product_data=Products::select('price','spcl_price')->where('id',$obj->fld_assign_type_id)->first();
                            $prd_attr=DB::table('product_attributes')
												->where('size_id',$prdIncart->size_id)
												->where('color_id',$prdIncart->color_id)
												->where('product_id',$obj->fld_assign_type_id)
												->first();
                                
                            $total=($product_data->price+$prd_attr->price)*$prdIncart->qty;
                               
							if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
								$total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
							}
							$discount_total=($total*$coupondata->discount_value)/100;
							
                            if($coupondata->max_discount<$discount_total){
                            $discount_total=$coupondata->max_discount;
                            }
							
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$total;
                            //$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"ProductCouponCartAmount"=>$input['cart_total']
										);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break;



				case 4: // seller wise assign
					
					$SellerProductIncarts = DB::table('cart')->select('cart.*')
											->join('products', 'products.id', 'cart.prd_id')
											->where('cart.user_id', $user_id)
											->whereIn('cart.id',$cart_id)
											->where('products.vendor_id', $obj->fld_assign_type_id)
											->get();
	
					if (sizeof($SellerProductIncarts) > 0)
					{
						$cart_total=$discount_total=0;
						foreach ($SellerProductIncarts as $productData)
						{
							$product_data = Products::select('price', 'spcl_price')->where('id', $productData->prd_id)
								->first();
							$prd_attr = DB::table('product_attributes')->where('size_id', $productData->size_id)
								->where('color_id', $productData->color_id)
								->where('product_id', $productData->prd_id)
								->first();
	
							$total = ($product_data->price + $prd_attr->price) * $productData->qty;
	
							if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
							{
								$total = ($product_data->spcl_price + $prd_attr->price) * $productData->qty;
	
							}
							$cart_total += $total;
							$discount_total+=($total*$coupondata->discount_value)/100;

						}					
					
							if($coupondata->max_discount<$discount_total){
							$discount_total=$coupondata->max_discount;
							}
							$input['cart_total']=$discount_total;
							$response=array(
									"ProductCouponCartAmount"=>$input['cart_total']
									);
					} else
					{
						$response = array(
							"Error" => 1,
							"Msg" => "Coupon code invalid"
						);
					 
					}
	
				break; 



	    }
	       } else{
	          //$input['cart_total']=self::getCartTotal($user_id,$cart_id,$request);
			  $input['cart_total']=self::getCartProductCouponTotal($user_id,$cart_id,$coupondata->discount_value,$coupondata->max_discount);
	          //$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
			  $response=array(
								"ProductCouponCartAmount"=>$input['cart_total']
							);
	       }
	   } else{
	       $response=array(
					"Error"=>1,
					"Msg"=>"Coupon code invalid"
				);
	   }
	   return $response;
	}
	
	public static function getCartProductCouponTotal($user_id,$cart_id,$discount_percent,$max_discount){
		    
		$shipping_charges_details=CommonHelper::getShippingDetails();
		$tax=0;
		$discount=0;
		$shipping_charge=0;
		$grand_total=0;
        $reward_points=0;  
		
		$cust_id=$user_id;
		$cart_data=self::getCartCouponProduct_item($cust_id,$cart_id); 
		
		$cust_info=Customer::where('id',$cust_id)->first();
		$reward_points=$cust_info->total_reward_points;
			
		$html='';
		$total=$discount_total=0;
		foreach($cart_data as $row){
			 $old_prc=$row->master_price;
			if ($row->master_spcl_price!='' && $row->master_spcl_price!=0)
			  {
				  $prc=$row->master_spcl_price;
			  }else{
				  $prc=$row->master_price;
			  }
			  
			  if($row->color_id==0 && $row->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	 if($row->color_id!=0 && $row->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($row->color_id!=0 && $row->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
		  
			$total+=$prc*$row->qty;
			
			$discount_total+=($prc*$row->qty)*$discount_percent/100;
           
		}
		//return $total;
		
		if($max_discount<$discount_total){
                            $discount_total=$max_discount;
                            }
		return $discount_total;
	}
	
	public static function getCartCouponProduct_item($customer_id,$cart_id){
	          
 		$cart_data=DB::table('cart')->select('cart.*',
											'products.default_image',
											'products.name',
											'products.id as prd_id',
											'products.price as master_price',
											'products.spcl_price as master_spcl_price',
											'cart.qty as qty',
											'products.delivery_days as delivery_days',
											'products.shipping_charges as shipping_charges')
									->join('products', 'cart.prd_id', '=', 'products.id')
									->whereIn('cart.id',$cart_id);
 		
		$cart_data=$cart_data->where('cart.user_id','=',$customer_id);	
 		
 		$cart_data=$cart_data->get();
		
		return $cart_data;
	}
	
	//Removed Product Starts
	
	//Applied Starts
	public static function verifyAppliedCouponDiscountAmt($code,$user_id,$cart_id){
	      
	    $coupondata =CouponDetails::select(
										'coupons.coupon_type',
										'coupons.id',
										'coupons.coupon_type',
										'coupons.max_discount',
										'coupons.below_cart_amt',
										'coupons.above_cart_amt',
										'coupons.started_date',
										'coupons.end_date',
										'coupons.discount_value',
										'coupons.total_coupon',
										'coupon_details.coupon_code'
									)
							->join('coupons','coupons.id','coupon_details.coupon_id')
							->where('coupon_details.coupon_code',$code)
							->where('coupon_details.coupon_used',0)
							->where('coupons.status',1)
							->first();
	   if($coupondata){
	       
	       $obj=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	       
	       if($obj){
	    
			switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
														->join('products','products.id','cart.prd_id')
														->join('product_categories','product_categories.product_id','products.id')
														->join('categories','product_categories.cat_id','categories.id')
														->where('user_id',$user_id)
														->whereIn('cart.id',$cart_id)
														->where('categories.id',$obj->fld_assign_type_id)
														->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=$discount_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
									$product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
									$prd_attr=DB::table('product_attributes')
													->where('size_id',$categoryProductIncart->size_id)
													->where('color_id',$categoryProductIncart->color_id)
													->where('product_id',$categoryProductIncart->prd_id)
													->first();
                                
									$total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;
                               
									if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
										$total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
									}
									$cart_total+=$total;
									$discount_total+=($total*$coupondata->discount_value)/100;
                            }
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$cart_total;
                            //$response= self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"AppliedCartAmount"=>$input['cart_total']
											);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break; 
                
                case 2: // brand wise assign
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
													->join('products','products.id','cart.prd_id')
													->join('brands','products.product_brand','brands.id')
													->where('user_id',$user_id)
													->whereIn('cart.id',$cart_id)
													->where('brands.id',$obj->fld_assign_type_id)
													->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=$discount_total=0;
                            foreach($brandProductIncarts as $brandProductIncart){
                                $product_data=Products::select('price','spcl_price')->where('id',$brandProductIncart->prd_id)->first();
								$prd_attr=DB::table('product_attributes')
													->where('size_id',$brandProductIncart->size_id)
													->where('color_id',$brandProductIncart->color_id)
													->where('product_id',$brandProductIncart->prd_id)
													->first();
                                
								$total=($product_data->price+$prd_attr->price)*$brandProductIncart->qty;
                               
								if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
									$total=($product_data->spcl_price+$prd_attr->price)*$brandProductIncart->qty;
								}
                                $cart_total+=$total;
								$discount_total+=($total*$coupondata->discount_value)/100;
                            }
							
							
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$cart_total;
							//$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"AppliedCartAmount"=>$input['cart_total']
										);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break;
                
                case 3: // product wise assign
                 
                        $prdIncart=DB::table('cart')
												->where('user_id',$user_id)
												->whereIn('cart.id',$cart_id)
												->where('prd_id',$obj->fld_assign_type_id)
												->first();
                        if($prdIncart){
                            $product_data=Products::select('price','spcl_price')->where('id',$obj->fld_assign_type_id)->first();
                            $prd_attr=DB::table('product_attributes')
												->where('size_id',$prdIncart->size_id)
												->where('color_id',$prdIncart->color_id)
												->where('product_id',$obj->fld_assign_type_id)
												->first();
                                
                            $total=($product_data->price+$prd_attr->price)*$prdIncart->qty;
                               
							if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
								$total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
							}
							$discount_total=($total*$coupondata->discount_value)/100;
							
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$total;
                            //$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"AppliedCartAmount"=>$input['cart_total']
										);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break;
	    }
	       } else{
	          //$input['cart_total']=self::getCartTotal($user_id,$cart_id,$request);
			  $input['cart_total']=self::getCartCouponTotal($user_id,$cart_id,$coupondata->discount_value);
	          //$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
			  $response=array(
								"AppliedCartAmount"=>$input['cart_total']
							);
	       }
	   } else{
	       $response=array(
					"Error"=>1,
					"Msg"=>"Coupon code invalid"
				);
	   }
	   return $response;
	}
	
	//Applied Starts
	
	public static function verifyAppliedCouponRemoveAmt($code,$user_id,$cart_id){
	      
	    
	    $coupondata =CouponDetails::select(
										'coupons.coupon_type',
										'coupons.id',
										'coupons.coupon_type',
										'coupons.max_discount',
										'coupons.below_cart_amt',
										'coupons.above_cart_amt',
										'coupons.started_date',
										'coupons.end_date',
										'coupons.discount_value',
										'coupons.total_coupon',
										'coupon_details.coupon_code'
									)
							->join('coupons','coupons.id','coupon_details.coupon_id')
							->where('coupon_details.coupon_code',$code)
							->where('coupon_details.coupon_used',0)
							->where('coupons.status',1)
							->first();
	   if($coupondata){
	       
	       $obj=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	       
	       if($obj){
	    
			switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
														->join('products','products.id','cart.prd_id')
														->join('product_categories','product_categories.product_id','products.id')
														->join('categories','product_categories.cat_id','categories.id')
														->where('user_id',$user_id)
														->whereIn('cart.id',$cart_id)
														->where('categories.id',$obj->fld_assign_type_id)
														->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=$discount_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
									$product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
									$prd_attr=DB::table('product_attributes')
													->where('size_id',$categoryProductIncart->size_id)
													->where('color_id',$categoryProductIncart->color_id)
													->where('product_id',$categoryProductIncart->prd_id)
													->first();
                                
									$total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;
                               
									if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
										$total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
									}
									$cart_total+=$total;
									$discount_total+=($total*$coupondata->discount_value)/100;
                            }
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$cart_total;
                            //$response= self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"RemovedCartAmount"=>$input['cart_total']
											);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break; 
                
                case 2: // brand wise assign
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
													->join('products','products.id','cart.prd_id')
													->join('brands','products.product_brand','brands.id')
													->where('user_id',$user_id)
													->whereIn('cart.id',$cart_id)
													->where('brands.id',$obj->fld_assign_type_id)
													->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=$discount_total=0;
                            foreach($brandProductIncarts as $brandProductIncart){
                                $product_data=Products::select('price','spcl_price')->where('id',$brandProductIncart->prd_id)->first();
								$prd_attr=DB::table('product_attributes')
													->where('size_id',$brandProductIncart->size_id)
													->where('color_id',$brandProductIncart->color_id)
													->where('product_id',$brandProductIncart->prd_id)
													->first();
                                
								$total=($product_data->price+$prd_attr->price)*$brandProductIncart->qty;
                               
								if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
									$total=($product_data->spcl_price+$prd_attr->price)*$brandProductIncart->qty;
								}
                                $cart_total+=$total;
								$discount_total+=($total*$coupondata->discount_value)/100;
                            }
							
							
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$cart_total;
							//$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"RemovedCartAmount"=>$input['cart_total']
										);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break;
                
                case 3: // product wise assign
                 
                        $prdIncart=DB::table('cart')
												->where('user_id',$user_id)
												->whereIn('cart.id',$cart_id)
												->where('prd_id',$obj->fld_assign_type_id)
												->first();
                        if($prdIncart){
                            $product_data=Products::select('price','spcl_price')->where('id',$obj->fld_assign_type_id)->first();
                            $prd_attr=DB::table('product_attributes')
												->where('size_id',$prdIncart->size_id)
												->where('color_id',$prdIncart->color_id)
												->where('product_id',$obj->fld_assign_type_id)
												->first();
                                
                            $total=($product_data->price+$prd_attr->price)*$prdIncart->qty;
                               
							if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
								$total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
							}
							$discount_total=($total*$coupondata->discount_value)/100;
							
							$input['cart_total']=$discount_total;
                            //$input['cart_total']=$total;
                            //$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
							$response=array(
											"RemovedCartAmount"=>$input['cart_total']
										);
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        }
                break;
	    }
	       } else{
	          //$input['cart_total']=self::getCartTotal($user_id,$cart_id,$request);
			  $input['cart_total']=self::getCartCouponTotal($user_id,$cart_id,$coupondata->discount_value);
	          //$response=self::validation_coupon_whencart_changes($coupondata,$input,$user_id,$request);
			  $response=array(
								"RemovedCartAmount"=>$input['cart_total']
							);
	       }
	   } else{
	       $response=array(
					"Error"=>1,
					"Msg"=>"Coupon code invalid"
				);
	   }
	   return $response;
	}
	
	public static function validation_coupon_whencart_changes($obj,$input,$user_id,$request){
	    
	    switch($obj->coupon_type){
	          
	           case 0:
	           case 4:
						$response=array(
									"Error"=>0,
									"Msg"=>"Coupon Applied "
								);
						break;
                case 3:
                case 7: // check date and cart amount 
	        
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){ // check date 
    
 if (
        $input['cart_total'] > $obj->below_cart_amt && 
        $input['cart_total'] < $obj->above_cart_amt
        ) {  // check cart amount
       $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied "
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Not valid for this  total "
				);
} 
    
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid "
				);
				
}
	        
	            break;
	            
	           case 2:
	           case 6:  // check date
	           
	              $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
  $response=array(
				"Error"=>0,
				"Msg"=>"Coupon code Applied "
				);
				  
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid "
				);
				
}
	            break;
	            
	            case 1 :
	                case 5:  // check  cart amount
	            
	             if (
        $input['cart_total'] > $obj->below_cart_amt && 
        $input['cart_total'] < $obj->above_cart_amt
        ) {  // check cart amount
    $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied"
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
					"Msg"=>"Not valid for this  total"
				);
} 
	            break;
	            
	    }
	 return $response;
			
	}
	
	public static function getCartCouponTotal($user_id,$cart_id,$discount_percent){
		    
		$shipping_charges_details=CommonHelper::getShippingDetails();
		$tax=0;
		$discount=0;
		$shipping_charge=0;
		$grand_total=0;
        $reward_points=0;  
		
		$cust_id=$user_id;
		$cart_data=self::getCartCoupon_item($cust_id,$cart_id);
		
		$cust_info=Customer::where('id',$cust_id)->first();
		$reward_points=$cust_info->total_reward_points;
			
		$html='';
		$total=$discount_total=0;
		foreach($cart_data as $row){
			 $old_prc=$row->master_price;
			if ($row->master_spcl_price!='' && $row->master_spcl_price!=0)
			  {
				  $prc=$row->master_spcl_price;
			  }else{
				  $prc=$row->master_price;
			  }
			  
			  if($row->color_id==0 && $row->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	 if($row->color_id!=0 && $row->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($row->color_id!=0 && $row->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
		  
			$total+=$prc*$row->qty;
			
			$discount_total+=($prc*$row->qty)*$discount_percent/100;
           
		}
		//return $total;
		return $discount_total;
	}
	
	public static function getCartCoupon_item($customer_id,$cart_id){
	          
 		$cart_data=DB::table('cart')->select('cart.*',
											'products.default_image',
											'products.name',
											'products.id as prd_id',
											'products.price as master_price',
											'products.spcl_price as master_spcl_price',
											'cart.qty as qty',
											'products.delivery_days as delivery_days',
											'products.shipping_charges as shipping_charges')
									->join('products', 'cart.prd_id', '=', 'products.id')
									->whereIn('cart.id',$cart_id);
 		
		$cart_data=$cart_data->where('cart.user_id','=',$customer_id);	
 		
		$cart_data=$cart_data->get();
		return $cart_data;
	}
	
	public static function getCartTotal($user_id,$cart_id,$request=''){
		    $shipping_charges_details=CommonHelper::getShippingDetails();
	    
                //$ip=$request->ip();
				$ip='';
                //$isActivate= $this->getActivateCoupon($ip);
                $tax=0;
                $discount=0;
                $shipping_charge=0;
                $grand_total=0;
                
                
        $reward_points=0;  
		//if(Auth::guard('customer')->check())
		//{
			//$cust_id=auth()->guard('customer')->user()->id ;
			$cust_id=$user_id;
			$cart_data=self::getCart_item($ip,$cust_id,$cart_id);
			
			$cust_info=Customer::where('id',$cust_id)->first();
			$reward_points=$cust_info->total_reward_points;
			
		/*}else{
			$cart_data=self::getCart_item($ip);
		}*/
		$html='';
		$total=0;
		foreach($cart_data as $row){
			 $old_prc=$row->master_price;
			if ($row->master_spcl_price!='' && $row->master_spcl_price!=0)
			  {
				  $prc=$row->master_spcl_price;
			  }else{
				  $prc=$row->master_price;
			  }
			  
			  if($row->color_id==0 && $row->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	 if($row->color_id!=0 && $row->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($row->color_id!=0 && $row->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
		  
			$total+=$prc*$row->qty;
           
           
		}
		return $total;
		    
		}
		
		public static function getCart_item($ip,$customer_id='',$cart_id){
	      /*self::cart_product_delete();
	     $return_data=app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie(); 
	    
	     if($return_data==''){
	         return array();
	     }
	    	$cookie_data=json_decode($return_data);
	    	
	    	$cart_data=array();
	    	foreach($cookie_data as $cookie){
	    	     $products_in_cart=Products::select(
                    'products.default_image',
                    'products.name',
                    'products.id as prd_id',
                    'products.price as master_price',
                    'products.spcl_price as master_spcl_price',
                    'products.delivery_days as delivery_days',
                    'products.shipping_charges as shipping_charges'
		
		)->where('id',$cookie->product_id)->first();
            $products_in_cart->size_id=$cookie->size_id;
            $products_in_cart->color_id=$cookie->color_id;
            $products_in_cart->qty=$cookie->qty;
            array_push($cart_data,$products_in_cart);
	    	}
               */ 
                
               
                
 		$cart_data=DB::table('cart')->select('cart.*',
 		'products.default_image',
 		'products.name',
 		'products.id as prd_id',
 		'products.price as master_price',
 		'products.spcl_price as master_spcl_price',
 		'cart.qty as qty',
 		'products.delivery_days as delivery_days',
 		'products.shipping_charges as shipping_charges')
 				->join('products', 'cart.prd_id', '=', 'products.id')
 				//->where('cart.user_ip','=',$ip);
				->whereIn('cart.id',$cart_id);
 		if($customer_id!='')
 		{
			
			$cart_data=$cart_data->where('cart.user_id','=',$customer_id);
				
 		}
 		$cart_data=$cart_data->get();
		
		
		return $cart_data;
	}




	/**
	 * Method for split coupon amount
	 */
	public static function couponSplitAmountCheck($code,$user_id,$order_id,$discountAmount,$grandTotal){
	      
	    $coupondata =CouponDetails::select(
										'coupons.coupon_type',
										'coupons.id',
										'coupons.coupon_type',
										'coupons.max_discount',
										'coupons.below_cart_amt',
										'coupons.above_cart_amt',
										'coupons.started_date',
										'coupons.end_date',
										'coupons.max_discount',
										'coupons.discount_value',
										'coupons.total_coupon',
										'coupon_details.coupon_code'
									)
							->join('coupons','coupons.id','coupon_details.coupon_id')
							->where('coupon_details.coupon_code',$code)
							->where('coupon_details.coupon_used',0)
							->where('coupons.status',1)
							->first();
	   if($coupondata){
	       
	       $obj=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	       
	       if($obj){
	    
			switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
														->join('products','products.id','cart.prd_id')
														->join('product_categories','product_categories.product_id','products.id')
														->join('categories','product_categories.cat_id','categories.id')
														->where('user_id',$user_id)
														->where('categories.id',$obj->fld_assign_type_id)
														->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=$discount_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
							
								$product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
								$prd_attr=DB::table('product_attributes')
													->where('size_id',$categoryProductIncart->size_id)
													->where('color_id',$categoryProductIncart->color_id)
													->where('product_id',$categoryProductIncart->prd_id)
													->first(); 
								$total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;	

								if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
									$total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
								}
								
								$cart_total+=$total;								
                            }
							
							foreach($categoryProductIncarts as $categoryProductIncart){
							
								$product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();

								$prd_attr=DB::table('product_attributes')
												->where('size_id',$categoryProductIncart->size_id)
												->where('color_id',$categoryProductIncart->color_id)
												->where('product_id',$categoryProductIncart->prd_id)
												->first();
							
								$total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;	

								if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
									$total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
								}
								$discount_total = ($total/$cart_total)*$discountAmount;
								 
								$orderData = DB::table('order_details')->where([
									'order_id' => $order_id,
									'product_id' => $categoryProductIncart->prd_id,
									'color_id' => $categoryProductIncart->color_id,
									'size_id' => $categoryProductIncart->size_id,
								  ])->update(['order_coupon_amount' => number_format($discount_total,2)]);


						}  
                          							
                        } 
                break; 
                
                case 3: // product wise assign

                        $prdIncart=DB::table('cart')
												->where('user_id',$user_id)
												->where('prd_id',$obj->fld_assign_type_id)
												->first();
                        if($prdIncart){
							$product_data=Products::select('price','spcl_price')->where('id',$obj->fld_assign_type_id)->first();

                            $prd_attr=DB::table('product_attributes')
												->where('size_id',$prdIncart->size_id)
												->where('color_id',$prdIncart->color_id)
												->where('product_id',$obj->fld_assign_type_id)
												->first();
							$total=($product_data->price+$prd_attr->price)*$prdIncart->qty;	

							if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
								$total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
							}													
							$discount_total=($total*$coupondata->discount_value)/100;							
                            if($coupondata->max_discount<$discount_total){
                                $discount_total=$coupondata->max_discount;
                            }	

							 $orderData = DB::table('order_details')->where([
								'order_id' => $order_id,
								'product_id' => $prdIncart->prd_id,
								'color_id' => $prdIncart->color_id,
								'size_id' => $prdIncart->size_id,
							  ])->update(['order_coupon_amount' => number_format($discount_total,2)]);						
                        } 

                break;

				case 4: // seller wise assign
					
					$SellerProductIncarts = DB::table('cart')->select('cart.*')
											->join('products', 'products.id', 'cart.prd_id')
											->where('cart.user_id', $user_id)
											->where('products.vendor_id', $obj->fld_assign_type_id)
											->get();
					file_put_contents('sellerCouponCheckoutProducts.txt',json_encode($SellerProductIncarts));

					if (sizeof($SellerProductIncarts) > 0)
					{
						$cart_total=$discount_total=0;

						foreach($SellerProductIncarts as $productData){
						
							$product_data=Products::select('price','spcl_price')->where('id',$productData->prd_id)->first();
							$prd_attr=DB::table('product_attributes')
												->where('size_id',$productData->size_id)
												->where('color_id',$productData->color_id)
												->where('product_id',$productData->prd_id)
												->first(); 
							$totalm=($product_data->price+$prd_attr->price)*$productData->qty;	

							if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
								$totalm=($product_data->spcl_price+$prd_attr->price)*$productData->qty;
							}
							
							$cart_total+=$totalm;								
						}
						file_put_contents('sellerCouponProductsGrandtotal.txt',json_encode($cart_total));


						foreach ($SellerProductIncarts as $productData)
						{
							$product_data = Products::select('price', 'spcl_price')->where('id', $productData->prd_id)
								->first();
							$prd_attr = DB::table('product_attributes')->where('size_id', $productData->size_id)
								->where('color_id', $productData->color_id)
								->where('product_id', $productData->prd_id)
								->first();
	
							$total = ($product_data->price + $prd_attr->price) * $productData->qty;
	
							if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
							{
								$total = ($product_data->spcl_price + $prd_attr->price) * $productData->qty;
	
							}
						
							$discount_total = ($total/$cart_total)*$discountAmount;	

							file_put_contents('Latestcheckoutsellerproductdiscount.txt',json_encode($product_data).' | Discount Amount:'.$discount_total.' | Total Amount:'.$total,FILE_APPEND);

							$orderData = DB::table('order_details')->where([
								'order_id' => $order_id,
								'product_id' => $productData->prd_id,
								'color_id' => $productData->color_id,
								'size_id' => $productData->size_id,
							  ])->update(['order_coupon_amount' => number_format($discount_total,2)]);					

						}					
					
							
					} 	
				break; 

	    }
	       } else{
			
				$cart_data = app(\App\Http\Controllers\cart\CartController::class)->getCart_item('', $cust_id);
				foreach($cart_data as $row){		

					$prdIncart = DB::table('cart')							
									->where([
											'user_id' => $user_id,
										    'prd_id'=>$row->prd_id,
											'color_id' =>$row->color_id,
											'size_id' =>$row->size_id,
											])
									->first();

					if($prdIncart){

					$product_data=Products::select('price','spcl_price')->where('id',$prdIncart->prd_id)->first();
					$prd_attr=DB::table('product_attributes')
										->where('size_id',$prdIncart->size_id)
										->where('color_id',$prdIncart->color_id)
										->where('product_id',$prdIncart->prd_id)
										->first();
					$total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;	

					if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
						$total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
					}	

					$itemCouponAmount = 0;					
					$itemCouponAmount = ($total/$grandTotal) * $discountAmount;
					$orderData = DB::table('order_details')->where([
														'order_id' => $order_id,
														'product_id' => $row->prd_id,
														'color_id' => $row->color_id,
														'size_id' => $row->size_id,
													  ])->update(['order_coupon_amount' => number_format($itemCouponAmount,2)]);

					}
				}
	       }
	   }
	}

}
