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
use Exception;
use App\Helpers\CommonHelper;
use App\Coupon;
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Orders;
use App\OrdersDetail;
use App\OrdersShipping;
use App\Sizes;
use App\ProductCategories;
use App\ProductImages;
use App\Category;
use App\CouponDetails;
use App\CheckoutShipping;
use Config;
class CheckOutController extends Controller 
{
      	public $successStatus = 200;
		public $site_base_path='https://phaukat.com/';
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
      public function deliverySlots_jj(Request $request){
  	
        $fixed= "4 PM";
                $given_time_slot='9 aM - 11 Am';
                $pint= explode("-",$given_time_slot);
                $start=$pint[0];
                $end_time= $pint[1];
            $start = new \DateTime($start);  
          $end = new \DateTime($end_time);  
          $start_time = $start->format('H:i');
           $end_time = $end->format('H:i');
          
          $fixed = new \DateTime($fixed);  
          $fixtime_time = $fixed->format('H:i');
         
            
            // strtotime(date('H:i'))." ".strtotime($fixtime_time);
          
  	 	   if(strtotime(date('H:i')) >= strtotime($fixtime_time)){
  	 	      
  	 	      return response()->json([
                        "status" => false,
                        'message'=>'Oops! No slot available for today',
                        'code' => 404
                        ]);
                        
          }
           else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){ 
             
                return response()->json([
                        "status" => true,
                        'message'=>'slot available',
                        'code' => 200
                        ]);
           }
            else if(strtotime(date('H:i')) <= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){    
               return response()->json([
                        "status" => true,
                        'message'=>'slot available',
                        'code' => 201
                        ]);
                        
            }
            
              else{
                  return response()->json([
                        "status" => false,
                        'message'=>'Oops! No slot available for today',
                        'code' => 404
                        ]);   
                }
           
          
  }
      public function deliverySlots(Request $request){
      	 $data = array(); $data1 = array();$res=array();
      	 $inputs = $request->all();
               $validation = Validator::make($request->all(), [ 
                'slot_date' => 'required',
            ]);
           
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Invalid request data',
                    'status' => false,
                    "data" => $validation->errors(),
                ]);
            } 
else {
  	       $date=$request->slot_date;
       
       $timeslo=DB::table('tbl_timeslot')->where(['status'=>1])->get();
       $city_html=""; $ccdata = date('d-m-Y');$status='0'; $j=1;
        

          foreach($timeslo as $trow){
            
        	$pint= explode("-",$trow->name);
        	$price =($trow->price)?"<i class='fa fa-inr'></i>".$trow->price:'';
        	$price1 =($trow->price)?$trow->price:'0';
        	$chk = ($j==1)?'':'';  //checked=""
          $start=$pint[0];
         $end_time= $pint[1];
          $fixed= "4 PM";
         
          $timewise=(date("ha")!='11AM')?'':'';
		   //echo date("a");die;
           if($ccdata==$date){
          
          $start = new \DateTime($start);  
          $end = new \DateTime($end_time);  
          $start_time = $start->format('H:i');
           $end_time = $end->format('H:i');
          
          $fixed = new \DateTime($fixed);  
          $fixtime_time = $fixed->format('H:i');
         
          
           if(strtotime(date('H:i')) >= strtotime($fixtime_time)){
             $data = array();
                $msg='Oops! No slot available for today';     
                $code=404;
                $status=FALSE;
           }
           
          
      else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){    
          $data[$j]['id'] = $trow->id;
          $data[$j]['name'] = $trow->name;
          $data[$j]['price'] = ($trow->price)?$trow->price:'0.0';
          $msg='slot available';
          $code=201;
          $status=TRUE;
                }
           else if(strtotime(date('H:i')) <= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){    
  $data[$j]['id'] = $trow->id;
          $data[$j]['name'] = $trow->name;
          $data[$j]['price'] = ($trow->price)?$trow->price:'0.0';
          $msg='slot available';
          $code=201;
          $status=TRUE;
                }
                
                else{
                $data = array();
                $msg='Oops! No slot available for today';     
                $code=404;
                $status=FALSE;
              
                }
         
                
          }
         /// not same day
         	else{
         	//$data.=$trow->name;
          $data[$j]['id'] = $trow->id;
          $data[$j]['name'] = $trow->name;
          $data[$j]['price'] = ($trow->price)?$trow->price:'0.0';
          $msg='slot available';
          $code=201;
          $status=TRUE;
			}
	    
			$res=array_merge($data1,$data);
          $j++; 
           
        }
        echo json_encode(
            array(
          "status" => $status,
          'message'=>$msg,
          'code' => $code,
          "slot_list"=>$res
                )
            );


       }
	}

    public function delivery_option(Request $request){
  	 try{
                
     $timeslot=DB::table('tbl_timeslot')->where('status',1)->get();
     $date=date('Y-m-d');
   $day=date('d');
   $month=date('M');
   $month1=date('m');
   $year=date('Y');
   
   	$total_day=cal_days_in_month(CAL_GREGORIAN,$month1,$year);
      $start_date = date('Y-m-d');
//$data = array();
for ($i = 0; $i <7; $i++) 
 {
$end_date= date('d-m-Y', strtotime($start_date. ' + '.$i.'days'));
$timestamp = strtotime($i."-".$month1."-".$year);
$date = '2014-02-25';
$dt=date('D', strtotime($end_date));
$day = date('l', $timestamp);
$datecon = $i." ".$month." ".$year;
$datecon1 = $i."-".$month."-".$year; 
$selval =$dt."_".$end_date;

$data['date'][$i]['date']= $dt." ".$end_date;
$data['date'][$i]['day_date']=$end_date;
$data['date'][$i]['dayfull']= $dt;//substr($dt,0,1);
$data['date'][$i]['day']= substr($dt,0,1);
}
//echo  '<pre>';print_r($data);die;

    
      if(sizeof((array)$timeslot)) {
        $j = 0;
    
        foreach ($timeslot as $key => $order) {
        $data['slots'][$j]['id'] = $order->id;
        $data['slots'][$j]['name'] = $order->name;
        $data['slots'][$j]['price'] = intval($order->price);
        
          $j++;
        }
        return response()->json([
          "status" => true,
          'message'=>'Success',
          'code' => 200,
          "slot_list"=>$data
        ]);
      } else{
        return response()->json([
          "status" => false,
          'message'=>'No Slot available',
          'code' => 404,
          "slot_list"=>array()
        ]);
      }
     }   catch (Exception $e) {
       return response()->json([
         "status" => false,
       'message'=>'Error',
         'code' => 500
       ]);
     }
  }
     	public function validation_coupon($obj,$input){
	    
	    switch($obj->coupon_type){
	          
	        case ($obj->coupon_type==3 || $obj->coupon_type==7): // check date and cart amount 
	        
				$paymentDate = date('Y-m-d');
				$paymentDate=date('Y-m-d', strtotime($paymentDate));
				$contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
				$contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

				if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){ // check date 
					
					if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt)){  // check cart amount
							$response=$this->msg_sucess_info('Coupon Applied', $obj);
					}else{
							$response=$this->msg_failed_info('Not valid for this cart total', null);
					} 
					
				}else{
							$response=$this->msg_failed_info('Coupon code invalid', null);
				}
	        
	        break;
				
			case ($obj->coupon_type==2 || $obj->coupon_type==6):  // check date
	           
				$paymentDate = date('Y-m-d');
				$paymentDate=date('Y-m-d', strtotime($paymentDate));
				$contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
				$contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

				if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
				   
					$response=$this->msg_sucess_info('Coupon Applied', $obj);			  
				}else{
					$response=$this->msg_failed_info('Coupon code invalid', null);				
				}
	         
			 break;
			    
	         case ($obj->coupon_type==1 || $obj->coupon_type==5):  // check  cart amount
	            
				if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt)){  // check cart amount
			   
					$response=$this->msg_sucess_info('Coupon Applied', $obj);	
				}  else{
					$response=$this->msg_failed_info('Not valid for this cart total', null);	
				} 
	         break;
				
	    }
	  return   $response;
			
	}
	
	public function selectedPaymentGateway(){
	    $input = json_decode(file_get_contents('php://input'), true);
	    $cod_charges=0;
	     $output=array(
                        "Error"=>0,
                        "Msg"=>"please do not add cod charges",
                        "cod_charges"=>0
                        );  
               $store_charge=DB::table('store_shipping_charges')
                             ->select('store_shipping_charges.cod_charges')
                             ->first();
	    if($input['fld_payment_method']==0){
	         $output['Msg']="Add cod charges";
	         $output['cod_charges']=$store_charge->cod_charges;
	    }
	   
	    echo json_encode($output);
	}
	
		public function ValidateNewCustomer($user_id){
	    $newCustomer=false;
            $myfistr_order=DB::table('orders')
            ->where('customer_id',$user_id)
            ->get(); 
            if(sizeof($myfistr_order)>0){
               $newCustomer=true; 
            }
            return $newCustomer;
	}
    public function applyCoupon(Request $request) {
		$input = json_decode(file_get_contents('php://input'), true);

		
	$output=array(
            "Error"=>1,
            "Msg"=>"",
            //"cart_total"=>"",
            "coupon_code"=>"",
            "discount_percent"=>0,
            "discount_amount"=>0
	    );
		   $res_data=$this->verifyAppliedCoupon($input);
		 
		   
    
              
		   if($res_data['Error']==0){
		        $coupondata =CouponDetails::select(
                                        'coupons.discount_value',
                                        'coupons.max_discount',
                                        'coupon_details.coupon_code'
									)
								->join('coupons','coupons.id','coupon_details.coupon_id')
								->where('coupon_details.coupon_code',$input['coupon_code'])
								->where('coupon_details.coupon_used',0)
								->where('coupons.status',1)
								->first();
								
                $total=$res_data['cart_total'];
                
                if($total>0){
                    $discount= ( $total*$coupondata->discount_value)/100; 
                    $coupon_array['coupon_code']=$coupondata->coupon_code;
                    $coupon_array['discount_value']=$coupondata->discount_value;
                    
                if($coupondata->max_discount){
                    if($coupondata->max_discount<$discount){
                    $discount=$coupondata->max_discount;
                    }
                }    
    
                    
                    $output=array(
                    "Error"=>$res_data['Error'],
                    "Msg"=>$res_data['Msg'],
                    "coupon_code"=>$coupondata->coupon_code,
                    "discount_percent"=>$coupondata->discount_value,
                    "discount_amount"=>round($discount)
                    
                    
                    
                    );
                } else{
                        $output=array(
                        "Error"=>1,
                        "Msg"=>"Cart total invalid",
                        "coupon_code"=>"",
                        "discount_percent"=>0,
                        "discount_amount"=>0
                        );  
                }
               
               
		   }
		   else{
                    $output=array(
                    "Error"=>$res_data['Error'],
                    "Msg"=>$res_data['Msg'],
                    "coupon_code"=>"",
                    "discount_percent"=>0,
                    "discount_amount"=>0
                    );  
		   }
		   
	echo json_encode($output);	
	}
	
	
	public function applyCoupon2(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	
	$output=array(
            "Error"=>"",
            "Msg"=>"",
            "cart_total"=>"",
            "coupon_code"=>"",
            "discount_percent"=>"",
            "discount_amount"=>""
	    );
		   $res_data=$this->verifyAppliedCoupon($input);
               
		   if($res_data['Error']==0){
		        $coupondata =CouponDetails::select(
                    'coupons.discount_value',
                    'coupon_details.coupon_code'
	          )
	   ->join('coupons','coupons.id','coupon_details.coupon_id')
	   ->where('coupon_details.coupon_code',$input['coupon_code'])
	   ->where('coupon_details.coupon_used',0)
	    ->where('coupons.status',1)
	   ->first();
                $total=$res_data['cart_total'];
                $discount= ( $total*$coupondata->discount_value)/100; 
                $coupon_array['coupon_code']=$coupondata->coupon_code;
                $coupon_array['discount_value']=$coupondata->discount_value;
             
                  
        $output=array(
            "Error"=>$res_data['Error'],
            "Msg"=>$res_data['Msg'],
            "coupon_code"=>$coupondata->coupon_code,
            "discount_percent"=>$coupondata->discount_value,
            "discount_amount"=>$discount
	    );
               
		   }
		   else{
                    $output=array(
                    "Error"=>$res_data['Error'],
                    "Msg"=>$res_data['Msg'],
                    "coupon_code"=>$coupondata->coupon_code,
                    "discount_percent"=>"",
                    "discount_amount"=>""
                    );  
		   }
		   
	echo json_encode($output);
		  
	}
	

	
		public function verifyAppliedCoupon($input_array){
	      
	    $code=$input_array['coupon_code'];
	   // $code='RLFREERPR';
	   //  $user_id=$input_array['fld_user_id'];
	     $user_id = (int)$input_array['fld_user_id'];
	       $coupondata = CouponDetails::select(
                    'coupons.coupon_type',
                    'coupons.number_of_user',
                    'coupons.uses_per_user',
                    'coupons.id',
                    'coupons.coupon_type', 
                    'coupons.max_discount',
                    'coupons.below_cart_amt',
                    'coupons.above_cart_amt',
                    'coupons.coupon_for', 
                    'coupons.started_date',
                    'coupons.end_date',
                    'coupons.discount_value',
                    'coupons.total_coupon',
                    'coupon_details.coupon_code'
            )->join('coupons', 'coupons.id', 'coupon_details.coupon_id')
            ->where('coupon_details.coupon_code', $code)
            ->where('coupon_details.coupon_used', 0)
            ->first();
            
	   
	   
	 
	  
	   if($coupondata){
	       
	   
	      
                $cust_id=$input_array['fld_user_id'];
                
                $perPersonUsed=Coupon::perPersonUsedCoupon($cust_id,$coupondata);
                
               
	       
	          
                if($perPersonUsed['Error']==1){
                  return  $perPersonUsed;
                        die();
                }
                
           $maxCustomerUsed=Coupon::maxCustomerUsed($coupondata);
           
             
              
                if($maxCustomerUsed['Error']==1){
                    return  $maxCustomerUsed;
                        die();
                }
               
                if($coupondata->coupon_for==2){
                    $forNewCustomer=Coupon::forNewCustomer($cust_id);
                if($forNewCustomer['Error']==1){
                   return  $forNewCustomer;
                        die();
                }
                }
            
           
	       
	   //   if($coupondata->coupon_for==2){
	   //       $flag=$this->ValidateNewCustomer($input_array['fld_user_id']);
    //     if($flag){
    //          $response=array(
    //     			"Error"=>1,
    //     			"Msg"=>"Coupon code invalid",
    //     			"cart_total"=>0
    //     			);
        			
    //     			return $response;
             
    //     }
	   //   }
        
              
	       
	       $obj=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	      
	       if($obj){
	          
	    switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('product_categories','product_categories.product_id','products.id')
                         ->join('categories','product_categories.cat_id','categories.id')
                        ->where('cart.user_id',$user_id)
                        ->where('categories.id',$obj->fld_assign_type_id)
                        ->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=0;
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
                            $input['cart_total']=$cart_total;
                               $response= $this->validation_coupon_whencart_changes($coupondata,$input);
                               
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid",
                                 "cart_total"=>0
                            );
                        
                        }
                break;
                
                case 2: // brand wise assign
               
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('brands','products.product_brand','brands.id')
                          ->where('cart.user_id',$user_id)
                        ->where('brands.id',$obj->fld_assign_type_id)
                        ->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=0;
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
                                
                            }
                            $input['cart_total']=$cart_total;
                                $response=$this->validation_coupon_whencart_changes($coupondata,$input);
                                
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid",
                                 "cart_total"=>0
                            );
                          
                        }
                break;
                
                case 3: // product wise assign
                 
                       
                       
                      
                       
                        $prdIncart=DB::table('cart')
                        ->where('cart.user_id',$user_id)
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
                                $input['cart_total']=$total;
                                $response=$this->validation_coupon_whencart_changes($coupondata,$input);
                              
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid",
                                "cart_total"=>0
                            );
                            
                        }
                       
                          
                break;
	        
	    }
                
	       } else{
	           
	          
	           $input['cart_total']=$this->getCartTotal($input_array['fld_user_id']);
	          $response=$this->validation_coupon_whencart_changes($coupondata,$input);
	           
	       }
	       
	   } else{
	       $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid",
				"cart_total"=>0
				);
				
	   }
	  
	    return $response;
	}
	public function getCartTotal($user_id){
            $cart_data=DB::table('cart')
            ->select(
                'cart.prd_id as prd_id',
                'cart.size_id as size_id',
                'cart.color_id as color_id',
                'cart.qty as qty',
                'products.default_image',
                'products.name',
                'products.price as master_price',
                'products.spcl_price as master_spcl_price',
                'products.delivery_days as delivery_days',
                'products.shipping_charges as shipping_charges')
                ->join('products','products.id','cart.prd_id')
            ->where('user_id',$user_id)
            ->get();
           
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
	public function validation_coupon_whencart_changes($obj,$input){
	    switch($obj->coupon_type){
	          
	           case 0:
	           case 4:
	                 $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied ",
				 "cart_total"=> $input['cart_total']
				);
	               break;
                case 3:
                case 7: // check date and cart amount 
	        
            $paymentDate = date('Y-m-d h:i:s');
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
				"Msg"=>"Coupon Applied ",
				 "cart_total"=> $input['cart_total']
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Not valid for this  total ",
				 "cart_total"=>0
				);
} 
    
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid ",
				 "cart_total"=>0
				);
				
}
	        
	            break;
	            
	           case 2:
	           case 6:  // check date
	           
	              $paymentDate = date('Y-m-d h:i:s');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
  $response=array(
				"Error"=>0,
				"Msg"=>"Coupon code Applied ",
				 "cart_total"=> $input['cart_total']
				);
				  
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid ",
				 "cart_total"=>0
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
				"Msg"=>"Coupon Applied",
				 "cart_total"=> $input['cart_total']
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
					"Msg"=>"Not valid for this  total",
					 "cart_total"=>0
				);
} 
	            break;
	            
	    }
	 return $response;
			
	}
	
	public function save_order(Request $request) {
		
		$input = json_decode(file_get_contents('php://input'), true);
	//	file_put_contents('saveOrderfromApp.txt',json_encode(	$input));

// 			$response=array(
// 				"status"=>false,
// 				"statusCode"=>404,
// 				"message"=>"something went wrong",
// 				"success_order_id"=>''
// 				);
				
// 				echo json_encode($response);
// 				die();
		  $shipping_address_id=$input['fld_shipping_id'];
		if($shipping_address_id==0){
		  
			$shipping_adddress= Customer::select(
												'name as shipping_name',
												'phone as shipping_mobile',
												'email as shipping_email',
												'address as shipping_address',
												'address1 as shipping_address1',
												'address2 as shipping_address2',
												'city as shipping_city',
												'state as shipping_state',
												'pincode as shipping_pincode'
									)->where('id',$cust_id)->first();
		} else{
		    $shipping_adddress=CheckoutShipping::where('id',$shipping_address_id)->where('customer_id',$input['fld_user_id'])->first();
		}
		
		 $sitecityname = 'NA';
            $sitecityId = '0';
        if(isset($input['city_id'])){
            $sitecityId= $input['city_id'];
            $sitecityname= $input['city_name'];
        }
        if(!$shipping_adddress){
            	$response=array(
				"status"=>false,
				"statusCode"=>404,
				"message"=>"Shipping address invalid",
				"success_order_id"=>''
				);
				
				echo json_encode($response);
				die();
        }
        
         if($input['deliveryTime']=='delivery_time' || $input['deliveryTime']=='' ||  $input['deliveryTime']==null){
            	$response=array(
				"status"=>false,
				"statusCode"=>404,
				"message"=>"Select Delivery Slot",
				"success_order_id"=>''
				);
				
				echo json_encode($response);
				die();
        }
        
        
        $fixed= "4 PM";
        $given_time_slot=$input['deliveryTime'];
        $pint= explode("-",$given_time_slot);
        $start=$pint[0];
        $end_time= $pint[1];
        $start = new \DateTime($start);  
        $end = new \DateTime($end_time);  
        $start_time = $start->format('H:i');
        $end_time = $end->format('H:i');
        
        $fixed = new \DateTime($fixed);  
        $fixtime_time = $fixed->format('H:i');
         $date =$input['deliveryTime'];
            $ccdata = date('d-m-Y');
            // strtotime(date('H:i'))." ".strtotime($fixtime_time);
        if($ccdata==$date[1]){  
  	 	   if(strtotime(date('H:i')) >= strtotime($fixtime_time)){
  	 	      
  	 	      return response()->json([
                        "status" => false,
                        'message'=>'Oops! No slot available for today',
                        'code' => 404
                        ]);
                        
          }
		}
        //   else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){ 
             
        //         return response()->json([
        //                 "status" => true,
        //                 'message'=>'slot available',
        //                 'code' => 200
        //                 ]);
        //   }
        //     else if(strtotime(date('H:i')) <= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){    
        //       return response()->json([
        //                 "status" => true,
        //                 'message'=>'slot available',
        //                 'code' => 201
        //                 ]);
                        
        //     }
            
             /* else{
                  return response()->json([
                        "status" => false,
                        'message'=>'Oops! No slot available for today',
                        'code' => 404
                        ]);   
                }*/
        

     if($shipping_adddress->shipping_city!=$sitecityname){
                 	$response=array(
            		"status"=>false,
            		"statusCode"=>404,
            		"message"=>"Products not available in your city",
            		"success_order_id"=>''
            		);
            		
            		echo json_encode($response);
            		die();
            }
	   $cust_id=$input['fld_user_id'];
     
	   $check_product=$input['fld_purchase_type'];
	   
	   $coupon_code=$input['fld_coupon_code'];
	   
		$transaction_id='';
		$transaction_sts='';
		$order_sts='';
      if($input['fld_payment_mode']==1){
		  if($input['fld_txn_status']=='true'){
                $transaction_id=$input['fld_txn_id'];
                $order_sts=0;
                $transaction_sts=$input['fld_txn_status'];
		  } else{
				$transaction_id='';
				$order_sts=7;
				$transaction_sts=$input['fld_txn_status'];
		  }  
          
      } else{
			$transaction_id=$input['fld_txn_id'];
			$order_sts=0;
			$transaction_sts=$input['fld_txn_status'];
      }
      
	   if($check_product==1)
	   {
		   //buy_now
		  // $prd_info=DB::table('products')
		  //              ->select(
				// 		'products.name as fld_product_name',
				// 		'products.tax as fld_product_tax',
    //                     'products.price as price',
    //                     'products.spcl_price as spcl_price',
				// 		'products.delivery_days as fld_delivery_days',
				// 		'products.shipping_charges as fld_shipping_charges')
    //                     ->join('vendors','products.vendor_id','vendors.id')
    //                     ->where('products.id',$input['fld_product_id'])
    //                     ->where('products.status',1)
    //                      ->where('products.qty','>',1)
    //                     ->where('products.isdeleted',0)
    //                     ->where('vendors.status',1)
    //                     ->where('vendors.isdeleted',0)
				// 		->first();
						
				// 		if(!$prd_info){
    //                             	$response=array(
    //                         "status"=>false,
    //                         "statusCode"=>404,
    //                         "message"=>"Products not available",
    //                         "success_order_id"=>''
    //                         );
                            
    //                         echo json_encode($response);
    //                         die();
				// 		}
									
    //                 $prc=0;
    //                 $prc=$prd_info->spcl_price;
                    
                    
    //                 $extras=DB::table('product_attributes')
    //                 ->where('product_id',$input['fld_product_id'])
    //                 ->where('size_id',@$input['fld_product_size'])
    //                 ->where('color_id',@$input['fld_product_color'])
    //                 ->first();
    //                 if($extras){
    //                 $prc+=$extras->price;
                    
    //                 }
    //                 $input['fld_grand_total']=$prc;
			     	
		  // $cart_data[]=(object) array(
				// 			'fld_product_id'=>@$input['fld_product_id'],
				// 			'fld_product_name'=>@$prd_info->fld_product_name,
				// 			'fld_product_tax'=>@$prd_info->fld_product_tax,
				// 			'fld_product_qty'=>1,
				// 			'fld_product_price'=>@$prd_info->price,
				// 			'fld_spcl_price'=>@$prd_info->spcl_price,
				// 			'fld_product_color'=>@$input['fld_product_color'],
				// 			'fld_product_size'=>@$input['fld_product_size'],
				// 			'fld_product_wsize'=>@$input['fld_product_wsize'],
				// 			'fld_delivery_days'=>@$prd_info->fld_delivery_days,
				// 			'fld_shipping_charges'=>@$prd_info->fld_shipping_charges,
				// 		);
				
				
					$cart_data = DB::table('cart')
									->select(
												'products.name as fld_product_name',
												'products.tax as fld_product_tax',
												'cart.id as fld_cart_id',
												'cart.qty as fld_product_qty',
												'products.price as fld_product_price',
												'products.spcl_price as fld_spcl_price',
												'cart.color_id as fld_product_color',
												'cart.size_id as fld_product_size',
												'cart.w_size_id as fld_product_wsize',
												'cart.prd_id as fld_product_id',
												'products.delivery_days as fld_delivery_days',
												'products.shipping_charges as fld_shipping_charges',
												DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
											)
									->join('products','cart.prd_id','products.id')
									->join('vendors','products.vendor_id','vendors.id')
									->where('cart.user_id',$cust_id)
									 ->where('products.qty','>',1)
                                    ->where('products.status',1)
                                    ->where('products.isdeleted',0)
                                    ->where('vendors.status',1)
                                    ->where('vendors.isdeleted',0)
									->get()
									->toarray();
					
	   }else if($check_product==2){
		   
		   //addtocart
		   $cart_data = DB::table('cart')
									->select(
												'products.name as fld_product_name',
												'products.tax as fld_product_tax',
												'cart.id as fld_cart_id',
												'cart.qty as fld_product_qty',
												'products.price as fld_product_price',
												'products.spcl_price as fld_spcl_price',
												'cart.color_id as fld_product_color',
												'cart.size_id as fld_product_size',
												'cart.w_size_id as fld_product_wsize',
												'cart.prd_id as fld_product_id',
												'products.delivery_days as fld_delivery_days',
												'products.shipping_charges as fld_shipping_charges',
												DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
											)
									->join('products','cart.prd_id','products.id')
									->join('vendors','products.vendor_id','vendors.id')
									->where('cart.user_id',$cust_id)
									 ->where('products.qty','>',1)
                                    ->where('products.status',1)
                                    ->where('products.isdeleted',0)
                                    ->where('vendors.status',1)
                                    ->where('vendors.isdeleted',0)
									->get()
									->toarray();
	   }
		
		
		$acutalcart = DB::table('cart')
    								->select(
    											'products.name as fld_product_name',
    											'products.tax as fld_product_tax',
    											'cart.id as fld_cart_id',
    											'cart.qty as fld_product_qty',
    											'products.price as fld_product_price',
    											'products.spcl_price as fld_spcl_price',
    											'cart.color_id as fld_product_color',
    											'cart.size_id as fld_product_size',
    											'cart.w_size_id as fld_product_wsize',
    											'cart.prd_id as fld_product_id',
    											'products.delivery_days as fld_delivery_days',
    											'products.shipping_charges as fld_shipping_charges',
    											DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
    										)
    								->join('products','cart.prd_id','products.id')
    								->join('vendors','products.vendor_id','vendors.id')
    								->where('cart.user_id',$cust_id)
    								 ->where('products.qty','>',1)
                                    ->where('products.status',1)
                                    ->where('products.isdeleted',0)
                                    ->where('vendors.status',1)
                                    ->where('vendors.isdeleted',0)
    								->count();
    								
					$incart = DB::table('cart')
					->select(
								'products.name as fld_product_name',
								'products.tax as fld_product_tax',
								'cart.id as fld_cart_id',
								'cart.qty as fld_product_qty',
								'products.price as fld_product_price',
								'products.spcl_price as fld_spcl_price',
								'cart.color_id as fld_product_color',
								'cart.size_id as fld_product_size',
								'cart.w_size_id as fld_product_wsize',
								'cart.prd_id as fld_product_id',
								'products.delivery_days as fld_delivery_days',
								'products.shipping_charges as fld_shipping_charges',
								DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
							)
					->join('products','cart.prd_id','products.id')
					->join('vendors','products.vendor_id','vendors.id')
					->where('cart.user_id',$cust_id)
                    ->where('products.status',1)
                    ->where('products.isdeleted',0)
                    ->where('vendors.status',1)
                    ->where('vendors.isdeleted',0)
					->count();
    								
		
		  if($acutalcart!=$incart){
		      $str='is';
		      if($incart==1 || $incart==0){
		          $str='is'; 
		      }else{
		           $str='are';
		      }
             	$response=array(
            		"status"=>false,
            		"statusCode"=>404,
            		"message"=>"Your product $str out of stock",
            		"success_order_id"=>''
            		);
            		
            		echo json_encode($response);
            		die();
             
         }
         
        $order_no='Redliips'.date('YmdHis');
         
           if($input['fld_purchase_type']==1){
                $total_products=sizeof($cart_data);
           } else{
               $total_products=sizeof($cart_data);
           }
         
         if($total_products==0){
             	$response=array(
            		"status"=>false,
            		"statusCode"=>404,
            		"message"=>"No Products in your cart",
            		"success_order_id"=>''
            		);
            		
            		echo json_encode($response);
            		die();
             
         }
          $finalAddressCity=$shipping_adddress->shipping_city;
            $invalidProducts=0;
            $validProducts=0;
         foreach($cart_data as $singleProduct){
             
              $productInSelectedLocation = DB::table('products')
									->select(
												'products.id'
											)
                                        ->join('vendors','products.vendor_id','vendors.id')
                                        ->join('vendor_company_info','vendor_company_info.vendor_id','vendors.id')
                                        ->where('vendor_company_info.city',$finalAddressCity)
                                        ->where('products.id',$singleProduct->fld_product_id)
							            ->first();
				if($productInSelectedLocation){
				   $validProducts++;
				}else{
				     $invalidProducts++;
				}			            
         }
         if($invalidProducts>0){
             	$response=array(
            		"status"=>false,
            		"statusCode"=>404,
            		"message"=>"Products not available in your city",
            		"success_order_id"=>''
            		);
            		
            		echo json_encode($response);
            		die();
         }
         
         
            $cod_charges=0;
            $shiiping_charges=$input['fld_shipping_charges'];
        if($input['fld_payment_mode']==0){
         $shipping_charges_details=CommonHelper::getShippingDetails();
         if($total_products>0){
               $cod_charges=($shipping_charges_details->cod_charges)/$total_products;
         }
      
        }
        
        
          $product_shipping_charges=0;
        if($input['fld_shipping_charges']>0){
             if($total_products>0){
              $product_shipping_charges=($input['fld_shipping_charges'])/$total_products;
         }
         
        }
        
        $dis=0;
        if($input['fld_discount_amount']>0){
           
        $dis=($input['fld_discount_amount'])/$total_products;
        }
		
        $order_shipping=array(
				'order_id'=>'',
				'order_shipping_name'=>$shipping_adddress->shipping_name,
				'order_shipping_address'=>$shipping_adddress->shipping_address,
				'order_shipping_address1'=>$shipping_adddress->shipping_address1,
				'order_shipping_address2'=>$shipping_adddress->shipping_address2,
				'order_shipping_city'=>$shipping_adddress->shipping_city,
				'order_shipping_state'=>$shipping_adddress->shipping_state,
				'order_shipping_zip'=>$shipping_adddress->shipping_pincode,
				'order_shipping_phone'=>$shipping_adddress->shipping_mobile,
				'order_shipping_email'=>$shipping_adddress->shipping_email,
			);
			
		DB::table('orders_shipping')->insert($order_shipping);
		$shipping_id=DB::getPdo()->lastInsertId();
		
		$wallet_consume_percent=0;
		$order_total_check=0;
		if($input['fld_wallet_amount']!=0)
		{
			$wallet_setting=DB::table('wallet_setting')->first();
			
			$wallet_consume_percent=$wallet_setting->wallet_consume_percent;
			$order_total_check=$input['fld_grand_total']+$input['fld_discount_amount']-$input['fld_shipping_charges'];
		}		
		
        $tcs_info=DB::table('tbl_settings')->select('tcs_tax_percentage')->where('id',1)->first();
        $tcs_amt=number_format(((($input['fld_grand_total'])*$tcs_info->tcs_tax_percentage)/100),2);
		
		$order=array(
                'customer_id'=>$cust_id,
                'shipping_id'=>$shipping_id,
                'order_no'=>$order_no,
                'grand_total'=>$input['fld_grand_total'],
                'coupon_code'=>$input['fld_coupon_code'],
                'coupon_percent'=>$input['fld_coupon_percent'],
                'coupon_amount'=>$input['fld_discount_amount'],
				'deduct_wallet_percent'=>$wallet_consume_percent,
				'deduct_reward_points'=>$input['fld_wallet_amount'],
                'total_shipping_charges'=>$input['fld_shipping_charges'],
                'payment_mode'=>$input['fld_payment_mode'],
                'tax_percent'=>$input['fld_tax'],
                'txn_id'=>$transaction_id,
                'txn_status'=>$transaction_sts,
                'tcs_percentage'=>$tcs_info->tcs_tax_percentage,
                'tcs_amt'=>$tcs_amt,
                'order_status'=>$order_sts,
                'delivery_time'=>$input['deliveryTime'],
                'slot_price'=>$input['slotPrice'],
                'order_date'	=> date("Y-m-d H:i:s"),
                'delivery_date'=>$input['deliveryDate'],
                'delivery_day'=>$input['deliveryDay'],
                'order_from'=>'APP'
			);
			
         	DB::table('orders')->insert($order);
         
         	$order_id=DB::getPdo()->lastInsertId();
         		DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'order_no'=>'Redliips'.$order_id
		 	        )
		 	    );
         	
			DB::table('orders_shipping')->where('id',$shipping_id)
										->update(
											array(
												'order_id'=>$order_id
											));
         	
		 $grand_total=$total_reward_points=$product_commission_master=0;
		 
		 for($i=0;$i<count($cart_data);$i++)
		 {
			 $prd_points=DB::table('product_reward_points')->where('product_id',$cart_data[$i]->fld_product_id)->first();
			 $prc=$cart_data[$i]->fld_spcl_price;
			 $old_price=0;
			 
			 $reward_points=($prd_points)?$prd_points->reward_points:0;
			 
			  if ($cart_data[$i]->fld_product_price!='')
			  {
				  $old_price=$cart_data[$i]->fld_product_price;
				 
			  }
			  
			  $extras=DB::table('product_attributes')
                    ->where('product_id',$cart_data[$i]->fld_product_id)
                    ->where('size_id',$cart_data[$i]->fld_product_size)
                    ->where('color_id',$cart_data[$i]->fld_product_color)
                    ->first();
			     if($extras){
			         $prc+=$extras->price;
			         if($old_price!=0){
			             $old_price+=$extras->price;
			         }
			         
			     }

			$product_commission_rate=0;
			 
			$ship_data=Products::productDetails($cart_data[$i]->fld_product_id);
			$commission_data=Products::productsFirstCatData($cart_data[$i]->fld_product_id);
			
			$product_commission_rate=$commission_data->commission_rate;
			$product_commission_master+=$product_commission_rate;
			
		
			  
			$tax=$ship_data->product_tax;
			
			$order_deduct_reward_points=0;
			if($order_total_check!=0)
			{
				$order_deduct_reward_points=round((($prc/$order_total_check)*$input['fld_wallet_amount']),2);
			}
			
			$product_coupon_amt=0;
			
			if($coupon_code!='')
			{
				$product_removed=array();
				array_push($product_removed,@$cart_data[$i]->fld_cart_id);
				
				$ss=Coupon::verifyAppliedCouponProductDiscountAmt($coupon_code,$cust_id,$product_removed);
				
				if(@$ss['ProductCouponCartAmount']!='')
				{
					$product_coupon_amt=round(@$ss['ProductCouponCartAmount']);
				}
			}
			
			 $tcs_info_amt=number_format(((($cart_data[$i]->fld_product_qty*$prc)*$tcs_info->tcs_tax_percentage)/100),2);
			 
			$order_detail=array(
								'suborder_no'=>$order_no.'_item_'.$i,
								'order_id'=>$order_id,
								'order_cod_charges'=>$cod_charges,
								'product_id'=>$cart_data[$i]->fld_product_id,
								'product_name'=>$cart_data[$i]->fld_product_name,
								'product_tax'=>$cart_data[$i]->fld_product_tax,
								'product_qty'=>$cart_data[$i]->fld_product_qty,
								'product_price'=>$prc,
								'product_price_old'=>$old_price,
								//'product_tax'=>$tax,
								'w_size'=> Products::getSizeName($cart_data[$i]->fld_product_wsize ),
								'size'=> Products::getSizeName($cart_data[$i]->fld_product_size ),
								'color'=> Products::getcolorName($cart_data[$i]->fld_product_color ),
								'w_size_id'=>$cart_data[$i]->fld_product_wsize,
								'size_id'=>$cart_data[$i]->fld_product_size,
								'color_id'=>$cart_data[$i]->fld_product_color,
								'order_reward_points'=>($reward_points==null || $reward_points=='')?0:$reward_points,
								'order_deduct_reward_points'=>$order_deduct_reward_points,
                                'order_coupon_amount'=>$dis,
                                'tcs_percentage'=>$tcs_info->tcs_tax_percentage,
                                'tcs_amt'=>$tcs_info_amt,
                                //  'order_coupon_amount'=>$product_coupon_amt,
                                'order_shipping_charges'=>$product_shipping_charges,
								'order_commission_rate'=>$product_commission_rate,
								'return_days'=>$ship_data->return_days,
								'order_status'=>$order_sts
							);
			DB::table('order_details')->insert($order_detail);
			$order_detail_id=DB::getPdo()->lastInsertId();
			DB::table('order_details')->where('id',$order_detail_id)->update(
		 	    array(
		 	        'suborder_no'=>'Redliips'.$order_detail_id
		 	        )
		 	    );
			
			$grand_total+=$cart_data[$i]->fld_product_qty*$prc;
			$total_reward_points+=($reward_points==null || $reward_points=='')?0:$reward_points;	
		 }
		 
		if($input['fld_payment_mode']==1){
			if($input['fld_txn_status']=='true'){
					 
					if($order['deduct_reward_points']!=0 && $order['deduct_reward_points']!=''){
						$wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Deducted',
							'fld_reward_deduct_points'=>$order['deduct_reward_points']
						);
				
						DB::table('tbl_wallet_history')->insert($wallet);
					}
			 
					if($total_reward_points!=0 && $total_reward_points!='')
					{
						 $wallet=array(
									'fld_customer_id'=>$cust_id,
									'fld_order_id'=>$order_id,
									'fld_order_detail_id'=>0,
									'fld_reward_narration'=>'Earned',
									'fld_reward_points'=>$total_reward_points
								);
						
						 DB::table('tbl_wallet_history')->insert($wallet);
					}
			
					$cust_points=DB::table('customers')->where('id',$cust_id)->first();
					
					if($cust_points->total_reward_points!=0 && $cust_points->total_reward_points!='')
					{
						$deduct_amt=$cust_points->total_reward_points-$order['deduct_reward_points'];
					}else{
						$deduct_amt=0;
					}
					
					
			
			DB::table('customers')->where('id',$cust_id)
					->update(
						array(
							'total_reward_points'=>($deduct_amt+$total_reward_points)
						));
		  }  
			  
		  } else{
				 if($order['deduct_reward_points']!=0){
					 $wallet=array(
								'fld_customer_id'=>$cust_id,
								'fld_order_id'=>$order_id,
								'fld_order_detail_id'=>0,
								'fld_reward_narration'=>'Deducted',
								'fld_reward_deduct_points'=>$order['deduct_reward_points']
							);
					
					 DB::table('tbl_wallet_history')->insert($wallet);
				 }
			 
				 $wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Earned',
							'fld_reward_points'=>$total_reward_points
						);
				
				DB::table('tbl_wallet_history')->insert($wallet);
			 
				$cust_points=DB::table('customers')->where('id',$cust_id)->first();
				$deduct_amt=$cust_points->total_reward_points-$order['deduct_reward_points'];
				
				DB::table('customers')->where('id',$cust_id)
										->update(
											array(
												'total_reward_points'=>($deduct_amt+$total_reward_points)
											));      
		  }
	     		    
		DB::table('cart')->where('user_id',$cust_id)->delete();
		
		$stock_data=DB::table('order_details')->where('order_id',$order_id)->get();
		for($i=0;$i<count($stock_data);$i++)
		{
			 $prd_id=$stock_data[$i]->product_id;
			 $size_id=$stock_data[$i]->size_id;
			 $color_id= $stock_data[$i]->color_id;
			 $qty=$stock_data[$i]->product_qty; 
			 Products::decreaseProductQty($prd_id,$size_id,$color_id,$qty);
		}
			
			
		if($input['fld_payment_mode']==0){ //cod
			
			
			$response=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your Order is placed succesfully",
				"success_order_id"=>$order_id
				);
			$this->generateMailforOrder($order_id,$cust_id);
		}else{
		    
		      if($input['fld_txn_status']=='true'){
		          	$response=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your Order is placed succesfully",
				"success_order_id"=>$order_id
				);
				$this->generateMailforOrder($order_id,$cust_id);
		      } else{
		          	$response=array(
				"status"=>false,
				"statusCode"=>404,
				"message"=>"Placed order failed",
				"success_order_id"=>''
				);
		      }
		
		}
		
		echo json_encode($response);
		
	}
	
	public function msg_sucess_info($msg, $data)
	{
	     $res=array(
                "status"=>true,
                "statusCode"=>201,
                "message"=>$msg,
                "Coupon_data"=>$data
				);
	
		
		return ($res);
	}
	
	public function msg_failed_info($msg, $data)
	{
	     $res=array(
                "status"=>false,
                "statusCode"=>404,
                "message"=>$msg,
                "Coupon_data"=>$data
				);
	
		
		return ($res);
	}
	
    
	//function for mail and message
	public function saveOrdertm(Request $request) {
		   $this->generateMailforOrder($request->order_id,$request->cust_id);
		}
		
		public function  toVendormailOnOrderplace($order_id,$customer_data,$order_details,$vdr_details,$mode,$shipping_data,$city_data,$state_data,$master_order){
      	$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Thank you for your order</p>
                    <p>We have received your  cgvgg order. We will send you an Email and SMS the moment your order items are dispatched to your address</p>
                    <p>
                       Order ID: <span style="color:#00bbe6;">'.$order_details->id.'</span><br />
                       Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                       Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Billing Info</strong><br />
                            '.$shipping_data->order_shipping_name.'<br />
                            '.$shipping_data->order_shipping_phone.'<br />
                            '.$shipping_data->order_shipping_email.'<br />
                            '.$shipping_data->order_shipping_address.'<br />
                            '.$shipping_data->order_shipping_address1.'<br />
                            '.$shipping_data->order_shipping_address2.'<br />
                            '.$shipping_data->order_shipping_city.'<br />
                            '.$shipping_data->order_shipping_state.'<br />
                            '.$shipping_data->order_shipping_zip.'<br />
                    </p
                </td>
                <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Shipping Info</strong><br />
                    '.$shipping_data->order_shipping_name.'<br />
                    '.$shipping_data->order_shipping_phone.'<br />
                    '.$shipping_data->order_shipping_email.'<br />
                    '.$shipping_data->order_shipping_address.'<br />
                    '.$shipping_data->order_shipping_address1.'<br />
                    '.$shipping_data->order_shipping_address2.'<br />
                    '.$shipping_data->order_shipping_city.'<br />
                    '.$shipping_data->order_shipping_state.'<br />
                    '.$shipping_data->order_shipping_zip.'<br />
                        
                    </p>
                </td>
            </tr> 
            <tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Order Summary</p>
                </td>
            </tr>';
            
            $email_msg.='<tr>
            	<td colspan="2">
                	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
                      <tr>
                       
                        <th>Item Name</th>
						<th>Quantity</th>
                        <th>Price</th>
						<th>Amt</th>
                      </tr>';
                      
                    
                        
                            $email_msg.='<tr>
                           
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_name.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_qty.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_price.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_qty*$order_details->product_price.'</td></tr>';
                            
                      
					
						
						
 
                   if($master_order->coupon_code!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Discount Applied Code <strong>'.$master_order->coupon_code.'</strong> to get </p></td>
						<td><strong>'.$master_order->discount_amount.'</strong></td>
					 </tr>';
                   }
                   if($master_order->total_shipping_charges!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Shiiping Charge</td>
						<td><strong>'.$master_order->total_shipping_charges.'</strong></td>
					 </tr>';
                   }
                     
					 
                       $email_msg.='<tr bgcolor="#d1d4d1">
                        <td style="padding:5px 10px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
                        <td><strong>Total Amount Rs.:'.($order_details->product_price*$order_details->product_qty+$order_details->order_cod_charges-$order_details->order_deduct_reward_points).' </strong></td>
                      </tr>
					  
					
					  
                    </table>

                </td>
            </tr>';
            
            
         $msg='Hi '.$vdr_details->username." You have a new order . Your order id is ".$order_details->id." .";
	           $email_data = [
                            'to'=>$vdr_details->email,
                            'subject'=>'New Order',
                            "body"=>view("emails_template.to_vendor_order_confirmation",
                            array(
                            'message'=>$email_msg,
                            'customer_info'=>$customer_data,
                            'vdr'=>$vdr_details,
                            'shipping_info'=>$shipping_data,
                            'extra_info'=>$master_order,
                            'products'=>$order_details,
                            'city_info'=>$city_data,
                            'state_info'=>$state_data,
                            'payment'=>$mode
                            ) )->render(),
                            'phone'=>$vdr_details->phone,
                            'phone_msg'=>$msg
                         ];
                         
                  
               CommonHelper::SendMsg($email_data);  
             CommonHelper::SendmailCustom($email_data);
         
     }
     public function toAdminMailOnOrderPlace($order_id,$cust_id){
            $master_order=Orders::where('id',$order_id)->first();
            $master_orders=OrdersDetail::where('order_id',$order_id)->get();
                $mode= ($master_order->payment_mode==0)?"COD":"Paid";
                
                $customer_data=Customer::where('id',$cust_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$order_id)->first();
                $city_data=DB::table('cities')->where('id',$shipping_data->order_shipping_city)->first();
                $state_data=DB::table('states')->where('id',$shipping_data->order_shipping_state)->first();
			

            foreach($master_orders as $order_details){
                
                
                $vdr_details=DB::table('products')
                                   ->select('vendors.username','vendors.email','vendors.phone')
                                 ->join('vendors','vendors.id','products.vendor_id')
                                 ->where('products.id',$order_details->product_id)
                                 ->first();
           $this->toVendormailOnOrderplace($order_id,$customer_data,$order_details,$vdr_details,$mode,$shipping_data,$city_data,$state_data,$master_order);             
        
                
            }
          	if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_placedMessage",
										array(
									'data'=>array(
										'name'=>ucfirst($customer_data->name),
										'order_no'=>$master_order->order_no,
										'order_date'=>$master_order->order_date
										)
										) )->render();					
								
					}
				else {
					$msg=view("message_template.online_order_placedMessage",
										array(
									'data'=>array(
                                        'name'=>$customer_data->name,
                                        'order_no'=>$master_order->order_no,
                                        'order_date'=>$master_order->order_date
										)
										) )->render();
				
				}
			 
          
										    
            	$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Thank you for your order</p>
                    <p>We have received your  cgvgg order. We will send you an Email and SMS the moment your order items are dispatched to your address</p>
                    <p>
                       Order ID: <span style="color:#00bbe6;">'.$master_order->order_no.'</span><br />
                       Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                       Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Billing Info</strong><br />
                            '.$shipping_data->order_shipping_name.'<br />
                            '.$shipping_data->order_shipping_phone.'<br />
                            '.$shipping_data->order_shipping_email.'<br />
                            '.$shipping_data->order_shipping_address.'<br />
                            '.$shipping_data->order_shipping_address1.'<br />
                            '.$shipping_data->order_shipping_address2.'<br />
                            '.$shipping_data->order_shipping_city.'<br />
                            '.$shipping_data->order_shipping_state.'<br />
                            '.$shipping_data->order_shipping_zip.'<br />
                    </p
                </td>
                <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Shipping Info</strong><br />
                    '.$shipping_data->order_shipping_name.'<br />
                    '.$shipping_data->order_shipping_phone.'<br />
                    '.$shipping_data->order_shipping_email.'<br />
                    '.$shipping_data->order_shipping_address.'<br />
                    '.$shipping_data->order_shipping_address1.'<br />
                    '.$shipping_data->order_shipping_address2.'<br />
                    '.$shipping_data->order_shipping_city.'<br />
                    '.$shipping_data->order_shipping_state.'<br />
                    '.$shipping_data->order_shipping_zip.'<br />
                        
                    </p>
                </td>
            </tr> 
            <tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Order Summary</p>
                </td>
            </tr>';
            
            $email_msg.='<tr>
            	<td colspan="2">
                	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
                      <tr>
                        <th style="padding:5px 0px;">S.no.</th>
                        <th>Item Name</th>
						<th>Quantity</th>
                        <th>Price</th>
						<th>Amt</th>
                      </tr>';
                      
                      $i=1;
                      foreach($master_orders as $products){
                          
                            $email_msg.='<tr>
                            <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_name.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_qty.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_price.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_qty*$products->product_price.'</td></tr>';
                            
                            $i++;
                      }
					
						
						
 
                   if($master_order->coupon_code!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Discount Applied Code <strong>'.$master_order->coupon_code.'</strong> to get </p></td>
						<td><strong>'.$master_order->discount_amount.'</strong></td>
					 </tr>';
                   }
                   if($master_order->total_shipping_charges!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Shiiping Charge</td>
						<td><strong>'.$master_order->total_shipping_charges.'</strong></td>
					 </tr>';
                   }
                     
					 
                       $email_msg.='<tr bgcolor="#d1d4d1">
                        <td style="padding:5px 10px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
                        <td><strong>Total Amount Rs.:'.$master_order->grand_total.' </strong></td>
                      </tr>
					  
					
					  
                    </table>

                </td>
            </tr>';
            
            
	           $email_data = [
                            'to'=>Config::get('constants.email.admin_to'),
                            'subject'=>'New Order',
                            "body"=>view("emails_template.to_admin_order_confirmation",
                            array(
                            'message'=>$email_msg,
                            'customer_info'=>$customer_data,
                            'shipping_info'=>$shipping_data,
                            'extra_info'=>$master_order,
                            'product_info'=>$master_orders,
                            'city_info'=>$city_data,
                            'state_info'=>$state_data,
                            'payment'=>$mode
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
                         
                    
            //CommonHelper::SendMsg($email_data);  
            CommonHelper::SendmailCustom($email_data);
         
     }
     	
public function isIt_myFirst_order($cust){
	
// 	  if my first order
	    $myfistr_order=DB::table('user_referrals')
	    ->where('c_id',$cust->id)
	    ->where('p_id',$cust->r_by)
	    ->where('first_order_placed',0)
	    ->first(); 
	    
	    if($myfistr_order){
	       // 	  get define price
	    $refer_price=DB::table('store_info')
	      ->select('parent_amount','child_amount')
	    ->first();
	   
	    // 	 transfer to parent
	    DB::table('tbl_refer_earn')
	    ->insert(array(
                'user_id'=>$cust->r_by,
                'rel_id'=>$cust->id,
                'amount'=>$refer_price->parent_amount,
                'mode'=>1
	        ));
	        
	        
	        // 	 update my order 
	        DB::table('user_referrals')
                ->where('c_id',$cust->id)
                ->where('p_id',$cust->r_by)
	    ->update(array(
            'first_order_placed'=>1
	        ));
	        
	         // update parents refer amount
                Customer::
                where('id',$cust->r_by)
                ->increment('r_amount',$refer_price->parent_amount);
                
                //update wallet amount of customer
                 Customer::
                where('id',$cust->r_by)
                ->increment('total_reward_points',$refer_price->parent_amount);
                
                 // create wallet history
	    DB::table('tbl_wallet_history')
	    ->insert(array(
                'fld_customer_id'=>$cust->r_by,
                'fld_order_id'=>0,
                'fld_order_detail_id'=>0,
                'fld_reward_points'=>$refer_price->parent_amount,
                'fld_reward_narration'=>'Earned',
                'mode'=>2
	        ));
	        
                // 	transfer to child
               
	    DB::table('tbl_refer_earn')
	    ->insert(array(
                'user_id'=>$cust->id,
                'rel_id'=>$cust->r_by,
                'amount'=>$refer_price->child_amount,
                'mode'=>0
	        ));
                
                // update parents refer amount
                Customer::
                where('id',$cust->id)
                ->increment('r_amount',$refer_price->child_amount);
                
                //update wallet amount of customer
                 Customer::
                where('id',$cust->id)
                ->increment('total_reward_points',$refer_price->child_amount);
                
                 // create wallet history
	    DB::table('tbl_wallet_history')
	    ->insert(array(
                'fld_customer_id'=>$cust->id,
                'fld_order_id'=>0,
                'fld_order_detail_id'=>0,
                'fld_reward_points'=>$refer_price->child_amount,
                'fld_reward_narration'=>'Earned',
                'mode'=>2
	        ));
     
                 
	    }
	    
	    
                
	}
	  public function generateMailforOrder($order_id,$cust_id){
	      
	          $cust=Customer::where('id',$cust_id)->first();
	          if($cust){
                    if($cust->r_by>0){
                    $this->isIt_myFirst_order($cust);
                    }
	          }
	        $this->toAdminMailOnOrderPlace($order_id,$cust_id);
            $adds=array(
                "order_id"=>$order_id,
                 "cust_id"=>$cust_id,
                );
               
                 
                  DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );
		 	     DB::table('order_details')->where('order_id',$order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );
            $master_order=Orders::where('id',$order_id)->first();
            $master_orders=OrdersDetail::where('order_id',$order_id)->get();
            $mode= ($master_order->payment_mode==0)?"COD":"Paid";
          
            $customer_data=Customer::where('id',$cust_id)->first();
            $shipping_data=OrdersShipping::where('order_id',$order_id)->first();
			$city_data=DB::table('cities')->where('id',$shipping_data->order_shipping_city)->first();
			$state_data=DB::table('states')->where('id',$shipping_data->order_shipping_state)->first();
				
				if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_placedMessage",
										array(
									'data'=>array(
                                        'name'=>ucfirst($customer_data->name),
                                        'order_no'=>$master_order->order_no,
                                        'order_date'=>$master_order->order_date
										)
										) )->render();					
								
					}
				else {
					$msg=view("message_template.online_order_placedMessage",
										array(
									'data'=>array(
                                        'name'=>$customer_data->name,
                                        'order_no'=>$master_order->order_no,
                                        'order_date'=>$master_order->order_date
										)
										) )->render();
				
				}
			 
          
										    
            	$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Thank you for your order</p>
                    <p>We have received your order. We will send you an Email and SMS the moment your order items are dispatched to your address</p>
                    <p>
                       Order ID: <span style="color:#00bbe6;">'.$master_order->order_no.'</span><br />
                       Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                       Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Billing Info</strong><br />
                            '.$shipping_data->order_shipping_name.'<br />
                            '.$shipping_data->order_shipping_phone.'<br />
                            '.$shipping_data->order_shipping_email.'<br />
                            '.$shipping_data->order_shipping_address.'<br />
                            '.$shipping_data->order_shipping_address1.'<br />
                            '.$shipping_data->order_shipping_address2.'<br />
                            '.$shipping_data->order_shipping_city.'<br />
                            '.$shipping_data->order_shipping_state.'<br />
                            '.$shipping_data->order_shipping_zip.'<br />
                    </p
                </td>
                <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Shipping Info</strong><br />
                    '.$shipping_data->order_shipping_name.'<br />
                    '.$shipping_data->order_shipping_phone.'<br />
                    '.$shipping_data->order_shipping_email.'<br />
                    '.$shipping_data->order_shipping_address.'<br />
                    '.$shipping_data->order_shipping_address1.'<br />
                    '.$shipping_data->order_shipping_address2.'<br />
                    '.$shipping_data->order_shipping_city.'<br />
                    '.$shipping_data->order_shipping_state.'<br />
                    '.$shipping_data->order_shipping_zip.'<br />
                        
                    </p>
                </td>
            </tr> 
            <tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Order Summary</p>
                </td>
            </tr>';
            
            $email_msg.='<tr>
            	<td colspan="2">
                	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
                      <tr>
                        <th style="padding:5px 0px;">S.no.</th>
                        <th>Item Name</th>
						<th>Quantity</th>
                        <th>Price</th>
						<th>Amt</th>
                      </tr>';
                      
                      $i=1;
                      foreach($master_orders as $products){
                          
                            $email_msg.='<tr>
                            <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_name.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_qty.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_price.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_qty*$products->product_price.'</td></tr>';
                            
                            $i++;
                      }
					
						
	
                   
                   if($master_order->coupon_code!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Discount Applied Code <strong>'.$master_order->coupon_code.'</strong> to get </p></td>
						<td><strong>'.$master_order->discount_amount.'</strong></td>
					 </tr>';
                   }
                   if($master_order->total_shipping_charges!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Shiiping Charge</td>
						<td><strong>'.$master_order->total_shipping_charges.'</strong></td>
					 </tr>';
                   }
                     
					 
                       $email_msg.='<tr bgcolor="#d1d4d1">
                        <td style="padding:5px 10px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
                        <td><strong>Total Amount Rs.:'.$master_order->grand_total.' </strong></td>
                      </tr>
					  
					
					  
                    </table>

                </td>
            </tr>';
            
            
	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Order',
                            "body"=>view("emails_template.order_confirmation",
                            array(
                            'message'=>$email_msg,
                            'customer_info'=>$customer_data,
                            'shipping_info'=>$shipping_data,
                            'extra_info'=>$master_order,
                            'product_info'=>$master_orders,
                            'city_info'=>$city_data,
                            'state_info'=>$state_data,
                            'payment'=>$mode
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
    
            CommonHelper::SendmailCustom($email_data);
             CommonHelper::SendMsg($email_data);
        
    }
	

}