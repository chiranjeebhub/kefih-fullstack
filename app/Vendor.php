<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use App\VendorCompanyInfo;
use App\VendorBankInfo;
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
    
    public static function VendorStsInactive($vdr_id){
        
       Vendor::where('id',$vdr_id)->update(
            array('status'=>0)
            );
   
    }
	public static function getVendorRating($id){
	    $final_rating=0;
	   $ratings=DB::table('vendor_rating')->where('vendor_id',$id)->get();
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
				$res=DB::table('vendor_categories')
								->where('vendor_id', $vdr_id)->first();
				if(!empty($res)){
					$res=DB::table('vendor_categories')
								->where('vendor_id', $vdr_id)
								->update([
								   'selected_cats' =>$cats_string
								]);
							}else{
								$res=DB::table('vendor_categories')
								->insert(['vendor_id'=> $vdr_id,
								   'selected_cats' =>$cats_string
								]);
							}
				
								
			return $res;
		
	}
	
	function updateDOcs($fileName,$fieldName,$vdr_id){
		     DB::table('vendor_tax_info')
								->where('vendor_id', $vdr_id)
								->update([
								$fieldName=>$fileName
								]);
								
	}
	
	function updateCompanyInfo($arr,$vdr_id){
				$details=DB::table('vendor_company_info')->where('vendor_id', $vdr_id)->first();
				//	dd($arr);
        if (array_key_exists("pancard",$arr)){
        	//dd(1);
            $pancard = $arr['pancard'];
        }elseif ($details == null) {
        	//dd(2);
        	$pancard = "";
        }
        else{
        	//dd(2);
                $pancard = $details->pancard;
        }
        if (array_key_exists("adharcard",$arr)){
            $adharcard = $arr['adharcard'];
        }elseif ($details == null) {
        	$adharcard = "";
        }else{
                $adharcard = $details->adharcard;
        }
        if (array_key_exists("address_proof",$arr)){
            $address_proof = $arr['address_proof'];
        }elseif ($details == null) {
        	$address_proof = "";
        }
        else{
                $address_proof = $details->address_proof;
        }
        if (array_key_exists("certificate",$arr)){
            $certificate = $arr['certificate'];
        }elseif ($details == null) {
        	$certificate = "";
        }else{
                $certificate = $details->certificate;
        }
        if (array_key_exists("other_documents",$arr)){
            $other_documents = $arr['other_documents'];
        }elseif ($details == null) {
        	$other_documents = "";
        }
        else{
                $other_documents = $details->other_documents;
        }
			
						if (array_key_exists("cm_logo",$arr))
						{
	$res=DB::table('vendor_company_info')
								->where('vendor_id', $vdr_id)
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
								'tax_type'=>$arr['tax_type'],
								'tax_rate'=>$arr['tx_rate'],
								]);
								
						}elseif ($details == null) {
							$res=DB::table('vendor_company_info')
								->insert([
								'vendor_id'=>$vdr_id,
								'name'=>$arr['company_name'],
								'address'=>$arr['company_address'],
								'state'=>$arr['company_state'],
                                'city'=>$arr['company_city'],
                                'pincode'=>$arr['company_pincode'],
								'about_us'=>$arr['company_about'],
								'pancard'=>$pancard,
								'other_documents'=>$other_documents,
								'certificate'=>$certificate,
								'adharcard'=>$adharcard,
								'address_proof'=>$address_proof,
								//'tax_type'=>$arr['tax_type'],
								'company_type'=>$arr['company_type'],
								'pannumber'=>$arr['pannumber'],
								//'tax_type'=>$arr['tax_type'],
								'tax_rate'=>$arr['tx_rate'],
								]);
						} else{
								$res=DB::table('vendor_company_info')
								->where('vendor_id', $vdr_id)
								->update([
								'name'=>$arr['company_name'],
								'address'=>$arr['company_address'],
								'state'=>$arr['company_state'],
                                'city'=>$arr['company_city'],
                                'pincode'=>$arr['company_pincode'],
								'about_us'=>$arr['company_about'],
								'pancard'=>$pancard,
								'other_documents'=>$other_documents,
								'certificate'=>$certificate,
								'adharcard'=>$adharcard,
								'address_proof'=>$address_proof,
								//'tax_type'=>$arr['tax_type'],
								'company_type'=>$arr['company_type'],
								'pannumber'=>$arr['pannumber'],
								//'tax_type'=>$arr['tax_type'],
								'tax_rate'=>$arr['tx_rate'],
								]);
								
						}
			return $res;
		
	}
	
	
	function updateSupportInfo($arr,$vdr_id){

		$res=DB::table('vendor_support_info')
								->where('vendor_id', $vdr_id)->first();
				if(!empty($res)){
					$res=DB::table('vendor_support_info')
								->where('vendor_id', $vdr_id)
								->update([
								'phone'=>$arr['phone'],
								'email'=>$arr['email'],
								'fb_id'=>$arr['fb_id'],
								'tw_id'=>$arr['tw_id'],
								]);
							}else{
								$res=DB::table('vendor_support_info')
								->insert([
								'vendor_id'=>$vdr_id,
								'phone'=>$arr['phone'],
								'email'=>$arr['email'],
								'fb_id'=>$arr['fb_id'],
								'tw_id'=>$arr['tw_id'],
								]);	
							}
				
			return $res;
		
	}
	
	function updateMetaInfo($arr,$vdr_id){
		$res=DB::table('vendor_seo_info')
								->where('vendor_id', $vdr_id)->first();
								if(!empty($res)){
									$res=DB::table('vendor_seo_info')
								->where('vendor_id', $vdr_id)
								->update([
								'meta_title'=>$arr['meta_title'],
								'meta_keyword'=>$arr['meta_description'],
								'meta_description'=>$arr['meta_keyword'],
								]);
							}else{
								$res=DB::table('vendor_seo_info')
								->insert([
								'vendor_id'=>$vdr_id,
								'meta_title'=>$arr['meta_title'],
								'meta_keyword'=>$arr['meta_description'],
								'meta_description'=>$arr['meta_keyword'],
								]);
							}
				
			return $res;
		
	}
	
	function updateBankInfo($arr,$vdr_id){
		   $res = new VendorBankInfo();
            $res->vendor_id = $vdr_id;
            //$res->ac_holder_name = $arr['ac_holder_name'];
            $res->name = $arr['bank_name'];
            $res->account_no = $arr['ac_no'];
            $res->branch = $arr['branch_name'];
            $res->city = $arr['bank_city'];
            $res->ifsc_code = $arr['ifsc_code'];
            $res->save();
				// $res=DB::table('vendor_bank_info')
				// 				->where('vendor_id', $vdr_id)
				// 				->update([
				// 				'name'=>$arr['bank_name'],
				// 				'account_no'=>$arr['ac_no'],
				// 				'branch'=>$arr['branch_name'],
				// 				'city'=>$arr['bank_city'],
				// 				'ifsc_code'=>$arr['ifsc_code'],
				// 				]);
			return $res;
		
	}
	
	function updateTaxInfo($arr,$vdr_id){

		$res=DB::table('vendor_tax_info')
								->where('vendor_id', $vdr_id)->first();
				if(!empty($res)){
					$res=DB::table('vendor_tax_info')
								->where('vendor_id', $vdr_id)
								->update([
								'gst_no'=>$arr['gst_no'],
								'pan_no'=>$arr['pan_no']
								]);
							}else{
								$res=DB::table('vendor_tax_info')
								->insert([
								'vendor_id'=>$vdr_id,
								'gst_no'=>$arr['gst_no'],
								'pan_no'=>$arr['pan_no']
								]);
							}
				
			return $res;
		
	}
	
	function updateInvoiceInfo($arr,$vdr_id){
				$res=DB::table('vendor_company_info')
								->where('vendor_id', $vdr_id)
								->update([
								'invoice_address'=>$arr['invoice_address'],
								'invoice_logo'=>$arr['invoice_logo']
								]);
			return $res;
		
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
	function getVendorDetails1($vdr_id){
		$res = DB::table('vendor_company_info')->where('vendor_id',$vdr_id)->first();
	/*
		if(!empty($res)){
						$res = $res->toarray();
					}*/		
					
			
				return $res;

	}
	function getSuppotVendorDetails($vdr_id){
		$res = DB::table('vendor_support_info')->where('vendor_id',$vdr_id)->first();
		
	/*	if(!empty($res)){
						$res = $res->toarray();
					}*/		
					
			
				return $res;

	}
function getSEOVendorDetails($vdr_id){
		$res = DB::table('vendor_seo_info')->where('vendor_id',$vdr_id)->first();
		
		/*if(!empty($res)){
						$res = $res->toarray();
					}*/
				return $res;

	}
	function getTaxVendorDetails($vdr_id){
		$res = DB::table('vendor_tax_info')->where('vendor_id',$vdr_id)->first();
		
		/*if(!empty($res)){
						$res = $res->toarray();
					}*/
				return $res;

	}
	
	function getVendorDetails($vdr_id){
		
	$res =Vendor::select(
						'vendors.*',
						
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
					->first();
					if(!empty($res)){
						$res = $res->toarray();
					}		
					
			
				return $res;
		
	}
}
