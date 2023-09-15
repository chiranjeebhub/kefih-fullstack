<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use App\Category;
use App\Brands;
use App\ProductCategories;
use App\Colors;
use App\Products;
use App\OrdersDetail;
use App\Orders;
use App\OrdersShipping;
use App\Sizes;
use App\Vendor;
use App\VendorSeoInfo;
use App\VendorCategory;
use App\VendorBankInfo;
use App\VendorTaxInfo;
use App\VendorCompanyInfo;
use App\VendorSupportInfo;
use App\Customer;
class Vendor extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	   protected $table = 'vendors';
	   
   

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public static function calculateReverseShippingCharge($vendor_ID){
        $orderData=$Orders =OrdersDetail::select('order_details.reverse_order_shipping_charge')
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
        ->join('products', 'products.id', '=', 'order_details.product_id')
        ->join('product_categories','product_categories.product_id','=','products.id')
        ->join('categories','categories.id','=','product_categories.cat_id')
        ->join('customers','orders.customer_id','=','customers.id')
        ->where('order_details.order_status',5)
        ->where('products.vendor_id',$vendor_ID)
        ->where('order_details.reverse_order_shipping_charge','>',0)
        ->groupBy('orders.id')->get();
       $totalAmount = 0;
       foreach($orderData as $row){
        $totalAmount+=$row->reverse_order_shipping_charge;
       }
       return $totalAmount;
    }

    
    public static function onChangeStsDelete($vdr_arr,$method){
        // 1 for delete  0 for sts changed
      $vdr_products=Products::select('id')->whereIn('vendor_id',$vdr_arr)->where('isdeleted',0)->get();
        $prd_ids=array();
        foreach($vdr_products as $vdr_product){
            array_push($prd_ids,$vdr_product->id);
        }
       
        switch($method){
            case 1:
                    Products::whereIn('id',$prd_ids)
                    ->update([
                    'isdeleted' =>1
                    ]);
            break ;
            
            case 0:
                Products::whereIn('id',$prd_ids)
                    ->update([
                    'status' =>0
                    ]);
            break;
        }
    }
      public static function productCount($vdr_id=0){
            $total=Products::select('id')->where('isdeleted',0)->where('vendor_id',$vdr_id)->get();
            return sizeof($total);
      }
      
      public static function productActiveCount($vdr_id=0){
            $total=Products::select('id')->where('isdeleted',0)->where('vendor_id',$vdr_id)->where('status',1)->get();
            return sizeof($total);
      }
      
      public static function productInactiveCount($vdr_id=0){
            $total=Products::select('id')->where('isdeleted',0)->where('vendor_id',$vdr_id)->where('status',0)->get();
            return sizeof($total);
      }
      
       public static function orderCount($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)->get();
            return sizeof($total);
      }
      
      
      public static function cancel_order_Count($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',4)
            ->get();
            return sizeof($total);
      }
      public static function new_order_Count($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',0)
            ->get();
            return sizeof($total);
      }
      
       public static function invoice_order_Count($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',1)
            ->get();
            return sizeof($total);
      }
      
        public static function shipped_order_Count($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',2)
            ->get();
            return sizeof($total);
      }
       public static function deliver_order_Count($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',3)
            ->get();
            return sizeof($total);
      }
      public static function return_order_Count($vdr_id=0){
            $total=OrdersDetail::join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',5)
            ->get();
            return sizeof($total);
      }

      public static function return_product_Count($vdr_id=0){
        $totalProducts=OrdersDetail::select('order_details.*')->join('products','products.id','order_details.product_id')
        ->where('products.vendor_id',$vdr_id)
        ->where('order_details.order_status',5)
        ->get();
         $totalReturnedProduct = 0; 
         foreach($totalProducts as $row){
            $totalReturnedProduct+=$row->product_qty;
         }
         return  $totalReturnedProduct;
        }


      

      
       public static function total_sell_Count($vdr_id=0){

            $totals=OrdersDetail::select('order_details.product_qty','order_details.product_price')->join('products','products.id','order_details.product_id')
            ->where('products.vendor_id',$vdr_id)
            ->where('order_details.order_status',3)
            ->get();
            $sell=0;
            foreach($totals as $total){
                $sell+=$total->product_qty*$total->product_price;
            }
            return $sell;
      }
      public static function returnOrderAmount(){
   
    return self::calculateOrderAmount(5);
}
public static function cancelOrderAmount(){
   
    return self::calculateOrderAmount(4);
}
      public static function calculateOrderAmount($type){
     
    $orderData=$Orders =OrdersDetail::select('order_details.product_qty','order_details.order_coupon_amount','order_details.product_price','order_details.order_shipping_charges')->join('orders', 'orders.id', '=', 'order_details.order_id')
    ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
    ->join('products', 'products.id', '=', 'order_details.product_id')
    ->join('product_categories','product_categories.product_id','=','products.id')
    ->join('categories','categories.id','=','product_categories.cat_id')
    ->join('customers','orders.customer_id','=','customers.id')
    ->where('order_details.order_status',$type)
    ->where('products.vendor_id',auth()->user()->id)
    ->groupBy('orders.id')->get();
   $totalAmount = 0;
   foreach($orderData as $row){
    $totalAmount+=(($row->product_qty * $row->product_price) + $row->order_shipping_charges) - $row->order_coupon_amount;
   }
   return $totalAmount;
}
    public static function VendorStsInactive($vdr_id){
        
       Vendor::where('id',$vdr_id)->update(
            array('status'=>0)
            );
   
    }
    public static function vdrRating($id){
       $final_rating=0;
	   $ratings=DB::table('vendor_rating')->where('vendor_id',$id)->where('isActive',1)->get();
                $five_staruser=0;
                $four_staruser=0;
                $three_staruser=0;
                $two_staruser=0;
                $one_staruser=0;
	   foreach($ratings as $rating){
	       switch($rating->rating){
	           
                case 1:
                    $one_staruser++;
                break;
                
                case 2:
                    $two_staruser++;
                break;
                
                
                case 3:
                    $three_staruser++;
                break;
                
                
                case 4:
                    $four_staruser++;
                break;
                
                
                case 5:
                    $five_staruser++;
                break;
            
	       }
	   }
	   $allusers=($five_staruser+$four_staruser+$three_staruser+$two_staruser+$one_staruser);
	   if($allusers!=0){
	       $final_rating=round((5*$five_staruser + 4*$four_staruser + 3*$three_staruser + 2*$two_staruser + 1*$one_staruser) /  $allusers); 
	   } 
	   return $final_rating;
    }
	public static function getVendorRating($id){
	    $final_rating=0;
	   $ratings=DB::table('vendor_rating')->where('vendor_id',$id)->where('isActive',1)->get();
                $five_staruser=0;
                $four_staruser=0;
                $three_staruser=0;
                $two_staruser=0;
                $one_staruser=0;
	   foreach($ratings as $rating){
	       switch($rating->rating){
	           
                case 1:
                    $one_staruser++;
                break;
                
                case 2:
                    $two_staruser++;
                break;
                
                
                case 3:
                    $three_staruser++;
                break;
                
                
                case 4:
                    $four_staruser++;
                break;
                
                
                case 5:
                    $five_staruser++;
                break;
            
	       }
	   }
	   $allusers=($five_staruser+$four_staruser+$three_staruser+$two_staruser+$one_staruser);
	   if($allusers!=0){
	       $final_rating=round((5*$five_staruser + 4*$four_staruser + 3*$three_staruser + 2*$two_staruser + 1*$one_staruser) /  $allusers); 
	   }
	   $html='<div class="rating"><span class="rating-total">';
	   switch($final_rating){
               case 0;
            
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
          
   
               break;
               
               case 1;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
               break;
               
               case 2;
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
               break;
               
               case 3;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
               break;
               
               case 4;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star gray-star"></span>';
               break;
               
               case 5;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
               break;
	       
	   }
	   $html.='</span></div>';
   return $html; 
	} 
	function updateCategories($arr,$vdr_id){
		
	$cats_string='';
	if (array_key_exists("cat",$arr))
				{
				
				$cats=$arr['cat'];
				sort($cats, SORT_NATURAL | SORT_FLAG_CASE);
				$cats_string=implode(",",$cats);
				
			}
				$res=VendorCategory::where('vendor_id', $vdr_id)
								->update([
								   'selected_cats' =>$cats_string
								]);
			return $res;
		
	}
	
	function updateDOcs($fileName,$fieldName,$vdr_id){
		     VendorTaxInfo::where('vendor_id', $vdr_id)
								->update([
								$fieldName=>$fileName
								]);
								
	}
	
	function updateCompanyInfo($arr,$vdr_id){
		$details=VendorCompanyInfo::where('vendor_id', $vdr_id)->first();	
        if (array_key_exists("pancard",$arr)){
            $pancard = $arr['pancard'];
        }else{
                $pancard = $details->pancard;
        }
        if (array_key_exists("adharcard",$arr)){
            $adharcard = $arr['adharcard'];
        }else{
                $adharcard = $details->adharcard;
        }
        if (array_key_exists("address_proof",$arr)){
            $address_proof = $arr['address_proof'];
        }else{
                $address_proof = $details->address_proof;
        }
        if (array_key_exists("certificate",$arr)){
            $certificate = $arr['certificate'];
        }else{
                $certificate = $details->certificate;
        }
        if (array_key_exists("other_documents",$arr)){
            $other_documents = $arr['other_documents'];
        }else{
                $other_documents = $details->other_documents;
        }


			
						if (array_key_exists("cm_logo",$arr))
						{
	                    $res=VendorCompanyInfo::where('vendor_id', $vdr_id)
								->update([
								'name'=>$arr['company_name'],
								'address'=>$arr['company_address'],
                                'state'=>$arr['company_state'],
                                'city'=>$arr['company_city'],
                                'pincode'=>$arr['company_pincode'],
								'about_us'=>$arr['company_about'],
								'logo'=>$arr['cm_logo'],
								'pancard'=>$pancard,
								'other_documents'=>$other_documents,
								'certificate'=>$certificate,
								'adharcard'=>$adharcard,
								'address_proof'=>$address_proof,
								'tax_type'=>$arr['tax_type'],
								'company_type'=>$arr['company_type'],
								'pannumber'=>$arr['pannumber'],
								'tax_rate'=>$arr['tx_rate'],
								]);
								
						} else{
								$res=VendorCompanyInfo::where('vendor_id', $vdr_id)
								->update([
								'name'=>$arr['company_name'],
								'address'=>$arr['company_address'],
								'state'=>$arr['company_state'],
                                'city'=>$arr['company_city'],
                                'pincode'=>$arr['company_pincode'],
                                'certificate'=>$certificate,
                                'pancard'=>$pancard,
                                'other_documents'=>$other_documents,
                                'adharcard'=>$adharcard,
                                'address_proof'=>$address_proof,
								'about_us'=>$arr['company_about'],
								'tax_type'=>$arr['tax_type'],
								'company_type'=>$arr['company_type'],
                                'pannumber'=>$arr['pannumber'],
								'tax_rate'=>$arr['tx_rate'],
								]);
								
						}
			return $res;
		
	}
	
	
	function updateSupportInfo($arr,$vdr_id){
				$res=VendorSupportInfo::where('vendor_id', $vdr_id)
								->update([
								'phone'=>$arr['phone'],
								'email'=>$arr['email'],
								'fb_id'=>$arr['fb_id'],
								'tw_id'=>$arr['tw_id'],
								]);
			return $res;
		
	}
	
	function updateMetaInfo($arr,$vdr_id){
				$res=VendorSeoInfo
								::where('vendor_id', $vdr_id)
								->update([
								'meta_title'=>$arr['meta_title'],
								'meta_keyword'=>$arr['meta_keyword'],
								'meta_description'=>$arr['meta_description'],
								]);
			return $res;
		
	}
	
	function updateBankInfo($arr,$vdr_id){
        $res = new VendorBankInfo();
        if(@$arr['bid'] !== null){
            $res =  VendorBankInfo::find(base64_decode($arr['bid']));
        }else{
            $res->vendor_id = $vdr_id;
        }
            
            
            $res->ac_holder_name = $arr['ac_holder_name'];
            $res->name = $arr['bank_name'];
            $res->account_no = $arr['ac_no'];
            $res->branch = $arr['branch_name'];
            $res->city = $arr['bank_city'];
            $res->ifsc_code = $arr['ifsc_code'];
            $res->save();

				// $res=VendorBankInfo::
				// 				where('vendor_id', $vdr_id)
				// 				->update([
				// 				'ac_holder_name'=>$arr['ac_holder_name'],
				// 				'name'=>$arr['bank_name'],
				// 				'account_no'=>$arr['ac_no'],
				// 				'branch'=>$arr['branch_name'],
				// 				'city'=>$arr['bank_city'],
				// 				'ifsc_code'=>$arr['ifsc_code'],
				// 				]);
			return $res;
		
	}
	
	function updateTaxInfo($arr,$vdr_id){
				$res=VendorTaxInfo::
								where('vendor_id', $vdr_id)
								->update([
								'gst_no'=>$arr['gst_no'],
								'pan_no'=>$arr['pan_no']
								]);
			return $res;
		
	}
	
	function updateInvoiceInfo($arr,$vdr_id){
				$res=VendorCompanyInfo::
				             where('vendor_id', $vdr_id)
								->update([
								'invoice_address'=>$arr['invoice_address'],
								'invoice_logo'=>$arr['invoice_logo']
								]);
			return $res;
		
	}
		public static function getVendorName($vdr_id){
		    	$res =Vendor::select(
						'vendors.username'
						)
				
				
					->where('vendors.id',$vdr_id)
					->first();
			
				return $res->username;
		}
		function vendor_id($id,$method){
	    if($method){
        $id=DB::table('order_details')
        ->select('products.vendor_id')
        ->join('products','order_details.product_id','products.id')
        ->where('order_details.id',$id)
        ->first();
        return $id->vendor_id;
	         
	    } else{
            $id=DB::table('products')
            ->select('products.vendor_id')
            ->where('products.id',$id)
            ->first();
	         return $id->vendor_id;
	    }
	  
	}
	function getVendorDetails($vdr_id){
		
	$res =Vendor::select(
						'vendors.*',
							'vendors.status as vdr_sts',
						'vendor_company_info.name as company_name',
						'vendor_company_info.logo as company_logo',
						'vendor_company_info.address as company_address',
                        'vendor_company_info.state as company_state',
                        'vendor_company_info.city as company_city',
                        'vendor_company_info.pincode as company_pincode',
						'vendor_company_info.about_us as company_about_us',
						'vendor_company_info.invoice_address as invoice_address',
						'vendor_company_info.invoice_logo as invoice_logo',
						'vendor_company_info.tax_type as tax_type',
						'vendor_company_info.tax_rate as tax_rate',
						'vendor_company_info.company_type as company_type',
						'vendor_company_info.pannumber as pannumber',
						'vendor_company_info.pancard as pancard',
						'vendor_company_info.adharcard as adharcard',
						'vendor_company_info.address_proof as address_proof',
						'vendor_company_info.certificate as certificate',
						'vendor_company_info.other_documents as other_documents',
						'vendor_support_info.phone as support_phone',
						'vendor_support_info.email as support_email',
						'vendor_support_info.fb_id as support_fb_id',
						'vendor_support_info.tw_id as support_tw_id',
						'vendor_seo_info.meta_title',
						'vendor_seo_info.meta_keyword',
						'vendor_seo_info.meta_description',
						'vendor_bank_info.name as bank_name',
						'vendor_bank_info.account_no as ac_no',
						'vendor_bank_info.ac_holder_name as ac_holder_name',
						'vendor_bank_info.branch as branh_name',
						'vendor_bank_info.city as bank_city',
						'vendor_bank_info.ifsc_code as ifsc_code',
						'vendor_tax_info.gst_no as gst_no',
						'vendor_tax_info.pan_no as pan_no',
						'vendor_tax_info.gst_file as gst_file',
						'vendor_tax_info.pan_file as pan_file',
                        'vendor_tax_info.cancel_cheque as cancel_cheque_file',
                        'vendor_tax_info.signature as signature_file'
						)
				
					->join('vendor_support_info', 'vendors.id', '=', 'vendor_support_info.vendor_id')
					->join('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
					->join('vendor_seo_info', 'vendors.id', '=', 'vendor_seo_info.vendor_id')
					->leftjoin('vendor_bank_info', 'vendors.id', '=', 'vendor_bank_info.vendor_id')
					->join('vendor_tax_info', 'vendors.id', '=', 'vendor_tax_info.vendor_id')
					->where('vendors.id',$vdr_id)
					->first()
					->toarray();
			
				return $res;
		
	}
}
