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
use App\Products;
use App\Category;
use App\ProductRating;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\Brands;
use App\ProductCategories;
use App\ProductExtraDescription;
use App\ProductExtraGeneralDescription;
use App\Filters;
use App\ProductImages;
use App\Slider;
use App\Vendor;
use URL;
use Config;

class ProductController extends Controller 
{
	public $successStatus = 200;
	
	public $site_base_path='https://phaukat.com/';
	
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	
	public function product_search(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
		file_put_contents("prod.txt",json_encode($input));
			    
		$user_id=@$input['fld_user_id'];
		$keyword=@$input['fld_search_txt'];
		 $sitecityname = 'NA';
           $sitecityId = '0';
        if(isset($input['city_id'])){
            $sitecityId= $input['city_id'];
            $sitecityname= $input['city_name'];
        }
		
		/*$data = DB::select("SELECT prd.* FROM products prd where prd.isdeleted=0  && prd.isexisting=0 && prd.status=1 && (prd.name like '%".$keyword."%' or prd.meta_keyword like '%".$keyword."%' or prd.meta_title like '%".$keyword."%'  ) group by prd.name");*/
		
		$data = DB::select("SELECT prd.*,prdcat.cat_id FROM products prd 
		inner join product_categories prdcat 
		on prdcat.product_id=prd.id
                join vendors vdr 
                on vdr.id=prd.vendor_id
                    join vendor_company_info vdr_com 
                    on vdr_com.vendor_id=vdr.id
		where  prd.isdeleted=0 && vdr.status=1 && vdr.isdeleted=0 && vdr_com.city like '%".$sitecityname."%' && prd.status=1 && (prd.name like '%".$keyword."%' or prd.meta_keyword like '%".$keyword."%' or prd.meta_title like '%".$keyword."%'  ) group by prd.name"); 
		
		$data1 = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.parent_id!=0 && cat.status=1 && cat.name like '%".$keyword."%' group by cat.name");
		
		$datalist=array();
		
		for($i=0; $i<count($data); $i++)
		{
			$prd_cat = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.status=1 && cat.id= '".$data[$i]->cat_id."' ");
			
			$p=ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data[$i]->name)));
			$datalist[]=array('search_name'=>$p,'category_id'=>@$data[$i]->cat_id,'category_name'=>@$prd_cat[0]->name,'category_compare'=>@$prd_cat[0]->cat_compare);
		}
		
		for($i=0; $i<count($data1); $i++)
		{
			$p=ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data1[$i]->name)));
			$datalist[]=array('search_name'=>$p,'category_id'=>$data1[$i]->id,'category_name'=>$data1[$i]->name,'category_compare'=>$data1[0]->cat_compare);
			
			$data2 = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.status=1 && cat.parent_id=".$data1[$i]->id);
			
			for($m=0; $m<count($data2); $m++)
			{
				$pp=ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data1[$i]->name))).'<span style="color:red"> in '.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data2[$m]->name))).'</span>';
				$datalist[]=array('search_name'=>$pp,'category_id'=>$data2[$m]->id,'category_name'=>$data2[$m]->name,'category_compare'=>$data2[0]->cat_compare);
				
				$data3 = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.status=1 && cat.parent_id=".$data2[$m]->id);
				
				for($n=0; $n<count($data3); $n++)
				{
					$ppp=ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data2[$m]->name))).'<span style="color:red"> in '.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data3[$n]->name))).'</span>';
					$datalist[]=array('search_name'=>$ppp,'category_id'=>$data3[$n]->id,'category_name'=>$data3[$n]->name,'category_compare'=>$data3[0]->cat_compare);
				}
			}
		}
		
		$allrecord =count($datalist);
		
		$message="Product Search Listing";
		$api_key="product_search_data";
		$grid_view=0; //list=0, grid=1, both=2
		$page=0;
		$record=$datalist;
		
		echo $this->msg1_info($message,$record,$page,$api_key,$grid_view,$allrecord);
		 
	}
	
	 /** 
     * Filters api 
     * 
     * @return \Illuminate\Http\Response 
     * 
     */
     public function coupons(Request $request){
          $coupons=DB::table('coupons')->select(
                        'coupons.coupon_name',
                        'coupons.coupon_type',
                        'coupons.max_discount',
                        'coupons.below_cart_amt',
                        'coupons.above_cart_amt',
                        'coupons.started_date as fld_coupon_validty_start_date',
                        'coupons.end_date as fld_coupon_validty_end_date',
                        'coupons.discount_value as fld_discount_value',
                        'coupons.description',
                        'coupons.id',
                        'coupons.end_date',
                        'coupon_details.coupon_code as fld_coupon_code',
              DB::raw("CONCAT('".$this->site_base_path."uploads/coupon_banner/',banner) AS fld_banner_image")
              )
              ->join('coupon_details','coupon_details.coupon_id','coupons.id')
              ->whereIN('coupons.coupon_type',array(0,1,2,3))
              ->where('coupons.status',1)
               ->where('coupons.show_in_app',1)
              ->where('coupon_details.status',1)
              ->get();
          $coopon_array=array();
          foreach($coupons as $coupon){
              $is_assign=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupon->id)->first();
              $special_for=$coupon->fld_discount_value.'% off';
                $cart_below='';
                $cart_above='';
                $cart_info='';
                  $des='';
              if($is_assign){
                  switch($is_assign->fld_coupon_assign_type){
                            // category 
                             case 1:
                                 $dt=Category::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                            
                            // brand 
                            case 2:
                                  $dt=Brands::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                            
                             // product 
                            case 3:
                                 $dt=Products::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                      
                  }
              } 
            $started_date=$coupon->fld_coupon_validty_start_date;
            $end_date=$coupon->fld_coupon_validty_end_date;
              switch($coupon->coupon_type){
                        
                            // static with cart
                             case 1:
                                    $cart_below=$coupon->below_cart_amt;
                                    $cart_above=$coupon->above_cart_amt;
                                    $cart_info='cart total_should be between ('.$coupon->below_cart_amt.' '.$coupon->above_cart_amt.' )';
                             break;
                            
                              // static with cart and date
                            case 3:
                                $cart_below=$coupon->below_cart_amt;
                                $cart_above=$coupon->above_cart_amt;
                                $cart_info='cart total_should be between ('.$coupon->below_cart_amt.' '.$coupon->above_cart_amt.' )';
                            break;
                      
                  }
                  
              $couponExpired=0;
              switch($coupon->coupon_type){
                        
                            // static with cart
                    case 2:
                    case 3:
                    case 6:
                    case 7:
                                    $cart_below=$coupon->fld_coupon_validty_start_date;
                                    $cart_above=$coupon->fld_coupon_validty_end_date;
                                    
                                    $today_date=date('Y-m-d');
                                    $expiry_date=$coupon->fld_coupon_validty_end_date;
                                    
                                    $expiry_date_stamp = strtotime($expiry_date); 
                                    $today_date_stamp = strtotime($today_date); 
                                  
                                    if($today_date_stamp>$expiry_date_stamp){
                                     $couponExpired=1;
                                    } 
                             break;
                             
                    case 1:
                    case 3:
                    case 5:
                    case 7:
                                    $cart_info='cart total should be between ('.$coupon->below_cart_amt.' '.$coupon->above_cart_amt.' )';
                             break;
                            
                             
                            
                          
                      
                  }
				      $des=$coupon->description;
				      
				  
				       
                  if($couponExpired==0){
                        $single_data['fld_coupon_name']=$coupon->coupon_name;
                        $single_data['fld_coupon_attr_name']=$special_for;
                        $single_data['fld_coupon_cart_below']=$cart_below;
                        $single_data['fld_coupon_cart_above']=$cart_above;
                        $single_data['fld_coupon_cart_info']=$cart_info;
                        
                        
                        $single_data['fld_description']='';
                        $single_data['fld_coupon_code']=$coupon->fld_coupon_code;
                        $single_data['fld_coupon_image']=$coupon->fld_banner_image;
                        
                        
                        $middle = strtotime($coupon->end_date);  
                        $new_date = date('d F Y H:i:s', $middle); 
                        
                        
                        $single_data['expiry_date']=($coupon->end_date)?$new_date:'';
                        
                        
                        
                        array_push($coopon_array,$single_data);
                  }
				
			
          }
          
          if(sizeof($coopon_array)>0){
              $res=array(
                                    "status"=>true,
                                    "statusCode"=>201,
                                    "message"=>"Coupon return",
                                    "coupon_return"=>$coopon_array
							);
          } else{
              $res=array(
                                   "status"=>true,
                                    "statusCode"=>404,
                                    "message"=>"no Coupon return",
                                    "coupon_return"=>null
							);
          }
           
            
            echo json_encode($res);

      }
     public function productDesc(Request $request){
         
         $desc='<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/custom.css" />
<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/styles.css" type="text/css" media="all"/>';
         $id=$request->id;
         $type=$request->type;
         $type=0;
         if($type==0){
             $prd_data=Products::where('id',$id)->first();
             $desc.=$prd_data->short_description;
         } else{
              $prd_data=Products::where('id',$id)->first();
              	$prd_extra_description=ProductExtraDescription::getProductExtraDescription($id);
              		$prd_extra_general=ProductExtraGeneralDescription::getProductExtraGeneralDescription($id);
                $html='<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/bootstrap.min.css" />
						<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/custom.css" />
						<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/styles.css" type="text/css" media="all"/>';
                $html_general='
							<style>
									table {border-collapse: collapse;border-spacing: 0;}
									.detailDesrptn tr:nth-of-type(2n+1) {background: #f0f0f0;}
									.detailDesrptn table tr td:first-child { width: 45%; font-weight: 500;}
									.detailDesrptn td { line-height: 20px; padding: 10px; border: 1px solid #eee;font-weight: 300;font-size:13px;}
									#text{display:none;}
									.btn-container{ height:44px; float:right; margin-top:10px;}
									button{ user-select:none; -webkit-user-select:none; -moz-user-select:none; -ms-user-select:none; cursor:pointer; border:none; padding:8px;
									  font-size:12px; border-radius:4px; color:white; background-color:#0c6bd5;
									}
								</style>
								<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
								<script>
									$(document).ready(function() {
									  $("#toggle").click(function() {
										var elem = $("#toggle").text();
										alert(elem);
										if (elem == "Read More") {
										  $("#toggle").text("Read Less");
										  $("#text").slideDown();
										} else {
										  $("#toggle").text("Read More");
										  $("#text").slideUp();
										}
									  });
									});
								</script>';
              	
              	
              foreach($prd_extra_description as $prd_extra){
                 $html.='<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">';
										if($prd_extra->product_descrip_image!=''){
										    $url=URL::to('/uploads/products').'/'.$prd_extra->product_descrip_image;
										      $html.=' <img src="'.$url.'" style="vertical-align:middle" widht="100" height="100">';
										}
                                              
											 $html.='<h2>'.ucwords($prd_extra->product_descrip_title).'</h2>
											<p>'.$prd_extra->product_descrip_content.'</p>
										</div>
									</div>
								</div>'; 
              }
              if(sizeof($prd_extra_general)>0){
                 $html_general.='<div class="detailDesrptn about-spfc-section">
									<div class="row tab-content">
										<div class="col-md-12 col-xs-12">
											<table id="text_xxx">';
											foreach($prd_extra_general as $prd_extra){
											  $html_general.='<tr>
																<td>'.ucwords($prd_extra->product_general_descrip_title).'</td>
																<td>'. $prd_extra->product_general_descrip_content.'</td>
															</tr>'; 
											}
												
											 $html_general.='</table>
												
												<!--<div class="btn-container">
												<button id="toggle">Read More</button>
												</div>-->
										</div>
									</div>
								</div>'; 
              }
              //$desc=$prd_data->long_description;
			  $desc='';
              $desc.=$html;
              $desc.=$html_general;
         }
         
         echo $desc;
     }
     
     public function postsellerRating(Request $request){
        	$input = json_decode(file_get_contents('php://input'), true);
        
        	
        	     $isgiven=DB::table('vendor_rating')
           ->where('user_id',$input['fld_user_id'])
           ->where('product_id',$input['fld_product_id'])
           ->first();
         if(!$isgiven){
             $dt=Products::productDetails($input['fld_product_id']);
             $data=array(
                    'vendor_id'=>$dt->vendor_id,
                    "user_id"=>$input['fld_user_id'],
					"product_id"=>$input['fld_product_id'],
                    "user_name"=>$input['fld_user_name'],
                    "rating"=>$input['fld_rating']

        	    );
             	$res=DB::table('vendor_rating')->insert($data);
        	if($res){
        	    $out=array(
                    "status"=>true,
                    "statusCode"=>201,
                    "message"=>"Review submitted"
        	        );
        	} else{
        	   $out=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>"something went wrong"
        	        ); 
        	}
         } else{
             $out=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>"rating already given"
        	        ); 
         }
        
        echo json_encode($out);
      }
      public function postReview(Request $request){
        	$input = json_decode(file_get_contents('php://input'), true);
       
        	$uploads='';
        	
        	     $isgiven=DB::table('product_rating')
           ->where('user_id',$input['fld_user_id'])
           ->where('product_id',$input['fld_product_id'])
           ->first();
         if(!$isgiven){
             if($input['fld_uploads']!=''){
        	    $img = $input['fld_uploads'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $img_data = base64_decode($img);
                    $name=uniqid() . '.png';
                    $uploads=$name;
                    $file = Config::get('constants.uploads.review_file').'/'.$name ;
                    file_put_contents($file, $img_data);
        	}
                    
                   
        	$data=array(
                    "product_id"=>$input['fld_product_id'],
                    "user_id"=>$input['fld_user_id'],
                    "user_name"=>$input['fld_user_name'],
                    "rating"=>$input['fld_rating'],
                    "review"=>$input['fld_review'],
                    "uploads"=>$uploads,

        	    );
             	$res=DB::table('product_rating')->insert($data);
        	if($res){
        	    $out=array(
                    "status"=>true,
                    "statusCode"=>201,
                    "message"=>"Review submitted"
        	        );
        	} else{
        	   $out=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>"something went wrong"
        	        ); 
        	}
         } else{
             $out=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>"rating already given"
        	        ); 
         }
        
        echo json_encode($out);
      }
 public function myReviews(Request $request){
        	$input = json_decode(file_get_contents('php://input'), true);
        	
        	$fld_page_no=@$input['fld_page_no'];
        	
        	$page=$fld_page_no;
		
	
		if($page!=0){
		 $fld_page_no=$fld_page_no*10;
		}
		
        $ratings=ProductRating::
            select('product_rating.*','products.name',
            	DB::raw("(IF(product_rating.uploads!='', CONCAT('".$this->site_base_path."uploads/review/',product_rating.uploads),null)) as uploads")
            )
            ->join('products','products.id','product_rating.product_id')
            ->where('user_id',$input['fld_user_id'])
                ->offset($fld_page_no)
                ->limit(10)
                ->get()->toarray();
                
            $all_ratings=ProductRating::
            select('product_rating.id')
            ->join('products','products.id','product_rating.product_id')
            ->where('user_id',$input['fld_user_id'])
            ->get()->toarray();
            
             echo $this->msg_info('My Reviews',$ratings,0,'review_data',$all_ratings);
          
    }
	 
	  public function filters2(Request $request){
          	$filter_data=array();
          
			$input = json_decode(file_get_contents('php://input'), true);
           
              
			$brand_id=@$input['fld_brand_id'];
			$cat_id=@$input['fld_cat_id'];
			$prd_name=@$input['fld_search_txt'];
			$flow_type=@$input['fld_flow_type'];
			if($cat_id==0)
				$cat_id='';
			
			if($brand_id==0)
				$brand_id='';
                
			$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
								->where('products.isdeleted','=',0)
								->where('products.status','=',1);
                 
            if($brand_id!=''){
              $max_min= $max_min->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
                $max_min= $max_min->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$max_min=$max_min
							->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $max_min=$max_min
							->join('categories','product_categories.product_id','=','products.id')
							->Where(function($query) use ($prd_name){
										 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
										 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
									 });
             }
		
			$max_min= $max_min->where('products.isdeleted',0)
								->where('products.status','=',1)->get()->first();
		   
			if($max_min->max_price!=''){
				 array_push($filter_data,array(
					 'name'=>strtoupper('price'),
					 'id'=>0
					 ));  
			}
			
			/*$category_data = Category::select(
										'categories.id as fld_cat_id',
										'categories.name as fld_cat_name'
										);
							
			if($brand_id!=''){
				$category_data=$category_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $category_data=$category_data->join('product_categories', 'categories.id', '=', 'product_categories.cat_id')
									->where('categories.parent_id','=',$cat_id);
				$category_data=$category_data
									->where('categories.parent_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$category_data=$category_data->join('product_categories', 'categories.id', '=', 'product_categories.cat_id');
				}
				
                $category_data=$category_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 //$query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
            }
				
			 $category_data=$category_data->groupBy('categories.id')
								->orderBy('categories.id')
								->get()->toArray();
										
			if(sizeof($category_data)>0){
				
			   for($i=0;$i<count(@$category_data); $i++){
					
					array_push($filter_data,array(
						'name'=>strtoupper($category_data[$i]['fld_cat_name']),
						'id'=>$i
						));
				}
			}*/
			
			if($cat_id!=''){
				$category_data = Category::select(
										'categories.id as fld_cat_id',
										'categories.name as fld_cat_name'
										);
				$category_data=$category_data
						->where('categories.status','=',1)
						->where('categories.isdeleted','=',0)
						->where('categories.parent_id','=',$cat_id)
											->groupBy('categories.id')
											->orderBy('categories.id')
											->get()->toArray();
										
				if(sizeof($category_data)>0){
					
				   for($i=0;$i<count(@$category_data); $i++){
						
						array_push($filter_data,array(
							'name'=>strtoupper($category_data[$i]['fld_cat_name']),
							'id'=>$i
							));
					}
				}
				
					if(sizeof($category_data)>0){
                            array_push($filter_data,array(
                            'name'=>'Categories',
                            'id'=>$i
                            ));
				}
				
				
				$brand_data = Products::select(
										'brands.id as fld_brand_id',
										'brands.name as fld_brand_name'
										)
							->join('brands', 'products.product_brand', '=', 'brands.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
			
			if($brand_id!=''){
				$brand_data=$brand_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $brand_data=$brand_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
									->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$brand_data=$brand_data->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $brand_data=$brand_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
            }
				
			 $brand_data=$brand_data->groupBy('brands.id')
								->orderBy('brands.id')
								->get()->toArray();
										
					if(sizeof($brand_data)>0){
						array_push($filter_data,array(
						    'name'=>strtoupper('brand'),
						    'id'=>1
						    ));
					}
					
			$color_data = Products::select(
								'colors.id as fld_color_id',
								'colors.name as fld_color_name',
								'colors.color_code as fld_color_code'
							)
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->join('colors', 'product_attributes.color_id', '=', 'colors.id')
						->where('products.isdeleted','=',0)
						->where('products.status','=',1);
					
			if($brand_id!=''){
				$color_data=$color_data->where('products.product_brand','=',$brand_id);
			}
				
			if($cat_id!=''){
				$color_data=$color_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
									->where('product_categories.cat_id','=',$cat_id);
			}
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$color_data=$color_data->join('product_categories','products.id','=','product_categories.product_id');
				}
			
				$color_data=$color_data
									//->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
			}
			
// 			$color_data=$color_data->where('product_attributes.qty','>',0);
			
			$color_data=$color_data
							->groupBy('colors.id')
							->orderBy('colors.id')
							->get()->toArray();
							
										
				
					
					
			$size_data = Products::select(
										'sizes.id as fld_size_id',
										'sizes.name as fld_size_name'
										)
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
			
			if($brand_id!=''){
				$size_data=$size_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $size_data=$size_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
									->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$size_data=$size_data->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $size_data=$size_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
            }
			
// 			$size_data=$size_data->where('product_attributes.qty','>',0);
				
			 $size_data=$size_data->groupBy('sizes.id')
								->orderBy('sizes.id')
								->get()->toArray();
										
					if(sizeof($size_data)>0){
						array_push($filter_data,array(
						    'name'=>strtoupper('size'),
						    'id'=>2
						    ));
					}
                		if(sizeof($color_data)>0){
						array_push($filter_data,array(
							'name'=>strtoupper('color'),
							'id'=>3
							));
					}
              
                    
			$material_data = Products::select(
										'materials.id as fld_material_id',
										'materials.name as fld_material_name'
										)
							->join('materials', 'products.material', '=', 'materials.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
						
			if($brand_id!=''){
				$material_data=$material_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $material_data=	$material_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					 $material_data=$material_data
									->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $material_data=$material_data
									->join('categories','product_categories.product_id','=','products.id')
                ->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 });
                 	}
                 	
					$material_data=$material_data
					->groupBy('materials.id')
					->orderBy('materials.id')
					->get()->toArray();
										
					if(sizeof($material_data)>0){
				// 		array_push($filter_data,array(
				// 		    'name'=>strtoupper('material'),
				// 		    'id'=>4
				// 		    ));
					}
					
			$filters_data = Products::select(\DB::raw("distinct(product_filters.filters_id) as  filters_id "),\DB::raw("group_concat(product_filters.filters_input_value) as  filters_input_value"))
								->join('product_filters', 'products.id', '=', 'product_filters.product_id')
								->join('product_categories','products.id','=','product_categories.product_id')
								->where('products.isdeleted','=',0)
							    ->where('products.status','=',1);
								//->join('filter_values', 'filter_values.filter_value', '=', 'product_filters.filters_input_value');
			
			if($brand_id!=''){
				$filters_data=$filters_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
				$filters_data=$filters_data
								//->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								//->join('filters_category', 'product_categories.cat_id', '=', 'filters_category.cat_id')
								->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            /*if($prd_name!=''){
				
				if($cat_id==''){
					$filters_data=$filters_data
								->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                /*$filters_data=$filters_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
			}*/
					
					$filters_data=$filters_data->groupBy('product_filters.filters_id')->get()->toArray();
										
					if(sizeof($filters_data)>0){
					    
					   // echo "<pre>";
					   // print_r($filters_data);
					   // die();
					    
						for($i=0;$i<count(@$filters_data); $i++){
							
							$filters_records = Filters::select("filters.name","filters.name")
									->where('filters.id','=',$filters_data[$i]['filters_id'])
									->first();
									
							array_push($filter_data,array(
							    'name'=>($filters_records->name),
							    'id'=>5+$i
							    ));
						}
					}
				
            }
            
			
			

              
              
                echo $this->msg_info('Product Filters',$filter_data,0,'filter_type_listing');
      } 
	  public function filters(Request $request){
          	$filter_data=array();
			$input = json_decode(file_get_contents('php://input'), true);
				 file_put_contents("storage/api_log/"."filter.txt",json_encode($input));
		
           
			$brand_id=@$input['fld_brand_id'];
			$cat_id=@$input['fld_cat_id'];
			$prd_name=@$input['fld_search_txt'];
			
			if($cat_id==0)
				$cat_id='';
			
			if($brand_id==0)
				$brand_id='';
                
			/*$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
								->where('products.isdeleted','=',0)
								->where('products.status','=',1);*/
			
			$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"));
                 
            if($brand_id!=''){
              $max_min= $max_min->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
                $max_min= $max_min->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$max_min=$max_min
							->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $max_min=$max_min
							->join('categories','product_categories.product_id','=','products.id')
							->Where(function($query) use ($prd_name){
										 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
										 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
									 });
             }
		
			$max_min= $max_min->where('products.isdeleted',0)
								->where('products.status','=',1)->get()->first();
		   
			if($max_min->max_price!=''){
				 array_push($filter_data,array(
					 'name'=>strtoupper('price'),
					 'id'=>0
					 ));  
			}
			
			/*$category_data = Category::select(
										'categories.id as fld_cat_id',
										'categories.name as fld_cat_name'
										);
							
			if($brand_id!=''){
				$category_data=$category_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $category_data=$category_data->join('product_categories', 'categories.id', '=', 'product_categories.cat_id')
									->where('categories.parent_id','=',$cat_id);
				$category_data=$category_data
									->where('categories.parent_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$category_data=$category_data->join('product_categories', 'categories.id', '=', 'product_categories.cat_id');
				}
				
                $category_data=$category_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 //$query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
            }
				
			 $category_data=$category_data->groupBy('categories.id')
								->orderBy('categories.id')
								->get()->toArray();
										
			if(sizeof($category_data)>0){
				
			   for($i=0;$i<count(@$category_data); $i++){
					
					array_push($filter_data,array(
						'name'=>strtoupper($category_data[$i]['fld_cat_name']),
						'id'=>$i
						));
				}
			}*/
			
			if($cat_id!=''){
				/*$category_data = Category::select(
										'categories.id as fld_cat_id',
										'categories.name as fld_cat_name'
										);
				$category_data=$category_data
						->where('categories.status','=',1)
						->where('categories.isdeleted','=',0)
						->where('categories.parent_id','=',$cat_id)
											->groupBy('categories.id')
											->orderBy('categories.id')
											->get()->toArray();
										
				if(sizeof($category_data)>0){
					
				   for($i=0;$i<count(@$category_data); $i++){
						
						array_push($filter_data,array(
							'name'=>strtoupper($category_data[$i]['fld_cat_name']),
							'id'=>$i
							));
					}
				}*/
				
				$category_data = Category::select(
										'categories.id as fld_cat_id',
										'categories.name as fld_cat_name'
										);
				$category_data=$category_data
						->where('categories.status','=',1)
						->where('categories.isdeleted','=',0)
						->where('categories.id','=',$cat_id)
											->get()->toArray();
										
				if(sizeof($category_data)>0){
					
				   for($i=0;$i<count(@$category_data); $i++){
						
						array_push($filter_data,array(
							'name'=>strtoupper('Categories'),
							'id'=>$i
							));
					}
				}
				
				$brand_data = Products::select(
										'brands.id as fld_brand_id',
										'brands.name as fld_brand_name'
										)
							->join('brands', 'products.product_brand', '=', 'brands.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
			
			if($brand_id!=''){
				$brand_data=$brand_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $brand_data=$brand_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
									->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$brand_data=$brand_data->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $brand_data=$brand_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
            }
				
			 $brand_data=$brand_data->groupBy('brands.id')
								->orderBy('brands.id')
								->get()->toArray();
										
					if(sizeof($brand_data)>0){
				// 		array_push($filter_data,array(
				// 		    'name'=>strtoupper('brand'),
				// 		    'id'=>1
				// 		    ));
					}
					
			$color_data = Products::select(
								'colors.id as fld_color_id',
								'colors.name as fld_color_name',
								'colors.color_code as fld_color_code'
							)
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->join('colors', 'product_attributes.color_id', '=', 'colors.id')
						->where('product_attributes.color_id','!=',0)
						->where('products.isdeleted','=',0)
						->where('products.status','=',1);
					
			if($brand_id!=''){
				$color_data=$color_data->where('products.product_brand','=',$brand_id);
			}
				
			if($cat_id!=''){
				$color_data=$color_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
									->where('product_categories.cat_id','=',$cat_id);
			}
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$color_data=$color_data->join('product_categories','products.id','=','product_categories.product_id');
				}
			
				$color_data=$color_data
									//->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
			}
			
// 			$color_data=$color_data->where('product_attributes.qty','>',0);
			
			$color_data=$color_data
							->groupBy('colors.id')
							->orderBy('colors.id')
							->get()->toArray();
							
										
				
					
					
			$size_data = Products::select(
										'sizes.id as fld_size_id',
										'sizes.name as fld_size_name'
										)
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
							->where('product_attributes.size_id','!=',0)
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
			
			if($brand_id!=''){
				$size_data=$size_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $size_data=$size_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
									->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					$size_data=$size_data->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $size_data=$size_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
            }
			
// 			$size_data=$size_data->where('product_attributes.qty','>',0);
				
			 $size_data=$size_data->groupBy('sizes.id')
								->orderBy('sizes.id')
								->get()->toArray();
									
										if(sizeof($color_data)>0){
						array_push($filter_data,array(
							'name'=>strtoupper('color'),
							'id'=>2
							));
					}
					
					if(sizeof($size_data)>0){
						array_push($filter_data,array(
						    'name'=>strtoupper('size'),
						    'id'=>3
						    ));
					}
                	
                	
              
                    
			$material_data = Products::select(
										'materials.id as fld_material_id',
										'materials.name as fld_material_name'
										)
							->join('materials', 'products.material', '=', 'materials.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
						
			if($brand_id!=''){
				$material_data=$material_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
               $material_data=	$material_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
            }
                 	
            if($prd_name!=''){
				
				if($cat_id==''){
					 $material_data=$material_data
									->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                $material_data=$material_data
									->join('categories','product_categories.product_id','=','products.id')
                ->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 });
                 	}
                 	
					$material_data=$material_data
					->groupBy('materials.id')
					->orderBy('materials.id')
					->get()->toArray();
										
					if(sizeof($material_data)>0){
				// 		array_push($filter_data,array(
				// 		    'name'=>strtoupper('material'),
				// 		    'id'=>4
				// 		    ));
					}
					
					
                
					
// 			$filters_data = Products::select(\DB::raw("distinct(product_filters.filters_id) as  filters_id "),\DB::raw("group_concat(product_filters.filters_input_value) as  filters_input_value"))
// 								->join('product_filters', 'products.id', '=', 'product_filters.product_id')
// 								->join('product_categories','products.id','=','product_categories.product_id')
// 								->where('products.isdeleted','=',0)
// 							    ->where('products.status','=',1);
								//->join('filter_values', 'filter_values.filter_value', '=', 'product_filters.filters_input_value');
								
		$filters_data =	DB::table('filters')->select(
        	    \DB::raw("distinct(filters.id) as  filters_id ")
        	    )
                ->join('filter_values', 'filter_values.filter_id', '=', 'filters.id')
                 ->join('filters_category', 'filters_category.filter_id', '=', 'filters.id');
                
			
			if($brand_id!=''){
				$filters_data=$filters_data->where('products.product_brand','=',$brand_id);
            }
                 	
            if($cat_id!=''){
				$filters_data=$filters_data
								//->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								//->join('filters_category', 'product_categories.cat_id', '=', 'filters_category.cat_id')
								->where('filters_category.cat_id','=',$cat_id);
            }
                 	
            /*if($prd_name!=''){
				
				if($cat_id==''){
					$filters_data=$filters_data
								->join('product_categories','products.id','=','product_categories.product_id');
				}
				
                /*$filters_data=$filters_data
								//->join('categories','product_categories.product_id','=','products.id')
								->Where(function($query) use ($prd_name){
											 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
											 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
										 });
			}*/
					
					$filters_data=$filters_data
					                 ->where('filters.isdeleted','=',0)
                                     ->where('filters.status','=',1)
                                     ->get()->toArray();
                                     
                                     
										
					if(sizeof($filters_data)>0){
                            for($i=0;$i<count(@$filters_data); $i++){
                            	
                            	$filters_records = Filters::select("filters.name","filters.name")
                                                            ->where('filters.id','=',$filters_data[$i]->filters_id)
                                                            ->first();
                            			
                            	array_push($filter_data,array(
                            	    'name'=>($filters_records->name),
                            	    'id'=>5+$i
                            	    ));
                            }
					}
				
            }
            
			
			

              
              
                echo $this->msg_info('Product Filters',$filter_data,0,'filter_type_listing');
      }
	  
	   /** 
     * Filters Value api 
     * 
     * @return \Illuminate\Http\Response 
     */

	  public function filters_value(Request $request){
			$filter_value_data=array();
			$input = json_decode(file_get_contents('php://input'), true);
            	 file_put_contents("storage/api_log/"."filters_value.txt",json_encode($input));
		  
			$brand_id=@$input['fld_brand_id'];
			$cat_id=@$input['fld_cat_id'];
			$filters_value=@$input['fld_filters_type'];
			$prd_name=@$input['fld_search_txt'];
			
			
			if($brand_id==0 || $brand_id=='0')
				$brand_id='';
			
			if($cat_id==0 || $cat_id=='0')
				$cat_id='';
            	
			if($filters_value=='SIZE'){
					$size_data = Products::select(
									'sizes.id as fld_id',
									'sizes.name as fld_name',
									DB::raw("('SIZE') AS fld_filter_type")
								)
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
						->where('products.isdeleted','=',0)
						->where('products.status','=',1);
					
					if($brand_id!=''){
						$size_data= $size_data->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 	if($cat_id!=''){
						$size_data= $size_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
						$size_data=$size_data->join('product_categories','products.id','=','product_categories.product_id')
											->join('categories','product_categories.product_id','=','products.id')
											->Where(function($query) use ($prd_name){
														 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
														 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
													 });
                 	}
					
				// 	$size_data=$size_data->where('product_attributes.qty','>',0);
					
					$size_data=$size_data
						->groupBy('sizes.id')
						->orderBy('sizes.id','asc')
						->get()->toArray();	
						
						if(sizeof($size_data)>0){
							$filter_value_data=$size_data;
						}
				}else if($filters_value=='COLOR'){
					
					$color_data = Products::select(
							'colors.id as fld_id',
							'colors.name as fld_name',
							'colors.color_code as fld_code',
								DB::raw("('COLOR') AS fld_filter_type")
						)
					->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
					->join('colors', 'product_attributes.color_id', '=', 'colors.id')
					->where('products.isdeleted','=',0)
					->where('products.status','=',1);
					
						if($brand_id!=''){
              $color_data= $color_data->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 		if($cat_id!=''){
$color_data= $color_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')

->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
                $color_data=$color_data
				->join('product_categories','products.id','=','product_categories.product_id')
				->join('categories','product_categories.product_id','=','products.id')
                ->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 });
                 	}
					
				// 	$color_data=$color_data->where('product_attributes.qty','>',0);
					
					$color_data=$color_data->groupBy('colors.id')
											->orderBy('colors.name')
											->get()->toArray();
						
						if(sizeof($color_data)>0){
						    	$filter_value_data=$color_data;
						}
				}else if($filters_value=='BRAND'){
					
					$brand_data = Products::select(
											'brands.id as fld_id',
											'brands.name as fld_name',
											DB::raw("('BRAND') AS fld_filter_type")
											)
										->join('brands', 'products.product_brand', '=', 'brands.id')
										->where('products.isdeleted','=',0)
										->where('products.status','=',1);
				if($brand_id!=''){
					$brand_data= $brand_data->where('products.product_brand','=',$brand_id);
                 }
                 	
                 if($cat_id!=''){
					$brand_data= $brand_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
                 }
                 	
                 if($prd_name!=''){
					$brand_data=$brand_data
									->join('product_categories','products.id','=','product_categories.product_id')
									->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
                 }
				$brand_data=$brand_data->groupBy('brands.id')
									->orderBy('brands.name')
									->get()->toArray();	
					
					if(sizeof($brand_data)>0){
					    $filter_value_data=$brand_data;
						
						}
						
				}else if($filters_value=='PRICE'){
					
					$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"));
					
					if($brand_id!=''){
						$max_min= $max_min->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 	if($cat_id!=''){
						$max_min= $max_min->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
						$max_min=$max_min
									->join('product_categories','products.id','=','product_categories.product_id')
									->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
                 	}
						$data=$max_min->where('products.isdeleted',0)
								->where('products.status','=',1)->get()->first();
								
				   $min_price=!empty($data->min_price)?$data->min_price:'1';
				   $max_price= !empty($data->max_price)?$data->max_price:'1';
				   
				   $price_data=array(
				       "min_price"=>(int)$min_price,
				       "max_price"=>(int)$max_price,
				       "fld_filter_type"=>"PRICE"
				       );
				    $filter_value_data=$price_data;
				 
					
				}else if($filters_value=='MATERIAL'){
					
					$material_data = Products::select(
								'materials.id as fld_id',
								'materials.name as fld_name',
								DB::raw("('MATERIAL') AS fld_filter_type")
								)
							->join('materials', 'products.material', '=', 'materials.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
           
           	if($brand_id!=''){
              $material_data= $material_data->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 		if($cat_id!=''){
$material_data= $material_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')

->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
                $material_data=$material_data
->join('product_categories','products.id','=','product_categories.product_id')
->join('categories','product_categories.product_id','=','products.id')
                ->Where(function($query) use ($prd_name){
								$query->where('products.isdeleted','=',0);
								$query->where('products.status','=',1);
										
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 });
                 	}
				$material_data=	$material_data->groupBy('materials.id')
					->orderBy('materials.name')
					->get()->toArray();
										
					if(sizeof($material_data)>0){
					     $filter_value_data=$material_data;
					}
				}else{
					$filters_records = Filters::select("filters.name","filters.id")
									->where('filters.name','=',$filters_value)
									->first();
					if(@$filters_records)
					{
					
					// 	if($filters_value==$filters_records->name){
					if($filters_records){
							$filters_data = DB::table('filter_values')->select(
																			'filter_values.id as fld_id',
																			'filter_values.filter_value as fld_name',
																			DB::raw("('$filters_value') AS fld_filter_type")
																			);
						
					if($prd_name!=''){
							$filters_data=$filters_data
									->join('product_categories','products.id','=','product_categories.product_id')
									->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												$query->where('products.isdeleted','=',0);
												$query->where('products.status','=',1);
								
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
					}
					 
					$filters_data=$filters_data
									->where('filter_values.filter_id','=',$filters_records->id)
									->get();
									
						

						
							if(sizeof($filters_data)>0){
								 $filter_value_data=$filters_data;
							}
						}
					
				}else{
				    
				   
					$category_data = Category::select(
									'categories.id as fld_id',
									'categories.name as fld_name',
									DB::raw("('$filters_value') AS fld_filter_type")
								)
								->where('categories.status','=',1)
								->where('categories.isdeleted','=',0);
						
					if($brand_id!=''){
						/*$category_data= $category_data->where('products.product_brand','=',$brand_id);*/
                 	}
                 	
                 	if($cat_id!=''){
						/*$category_data= $category_data->join('product_categories', 'categories.id', '=', 'product_categories.cat_id')
												->where('categories.parent_id','=',$cat_id);*/
						$category_data= $category_data
												->where('categories.parent_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
						$category_data=$category_data
									->join('product_categories','categories.id','=','product_categories.product_id')
									//->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 //$query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
                 	}
		
					$category_data=$category_data
										->groupBy('categories.id')
										->orderBy('categories.name')
										->get()->toArray();
										
						
					if(sizeof($category_data)>0){
						$filter_value_data=$category_data;
					}
				}
				
				}
				

			
                
             if($filters_value=='PRICE'){
                    echo $this->msg_info('Product Filters Value',$filter_value_data,0,'filter_price_data');
                } else{
                    echo $this->msg_info1('Product Filters Value',$filter_value_data,0,'filter_type_value_data');
                }
                
      }
      public function msg_info1(
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
			$data_list=[];
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
      
       public function filters_value2(Request $request){
			$filter_value_data=array();
			$input = json_decode(file_get_contents('php://input'), true);
        
       
			
			$brand_id=@$input['fld_brand_id'];
			$cat_id=@$input['fld_cat_id'];
			$filters_value=@$input['fld_filters_type'];
			$prd_name=@$input['fld_search_txt'];
            	
			if($filters_value=='SIZE'){
					$size_data = Products::select(
									'sizes.id as fld_id',
									'sizes.name as fld_name',
									DB::raw("('SIZE') AS fld_filter_type")
								)
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
						->where('products.isdeleted','=',0)
						->where('products.status','=',1);
					
					if($brand_id!=''){
						$size_data= $size_data->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 	if($cat_id!=''){
						$size_data= $size_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
						$size_data=$size_data->join('product_categories','products.id','=','product_categories.product_id')
											->join('categories','product_categories.product_id','=','products.id')
											->Where(function($query) use ($prd_name){
														 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
														 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
													 });
                 	}
					
				// 	$size_data=$size_data->where('product_attributes.qty','>',0);
					
					$size_data=$size_data
						->groupBy('sizes.id')
						->orderBy('sizes.name')
						->get()->toArray();	
						
						if(sizeof($size_data)>0){
							$filter_value_data=$size_data;
						}
				}else if($filters_value=='COLOR'){
					
					$color_data = Products::select(
							'colors.id as fld_id',
							'colors.name as fld_name',
							'colors.color_code as fld_code',
								DB::raw("('COLOR') AS fld_filter_type")
						)
					->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
					->join('colors', 'product_attributes.color_id', '=', 'colors.id')
					->where('products.isdeleted','=',0)
					->where('products.status','=',1);
					
						if($brand_id!=''){
              $color_data= $color_data->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 		if($cat_id!=''){
$color_data= $color_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')

->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
                $color_data=$color_data
				->join('product_categories','products.id','=','product_categories.product_id')
				->join('categories','product_categories.product_id','=','products.id')
                ->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 });
                 	}
					
				// 	$color_data=$color_data->where('product_attributes.qty','>',0);
					
					$color_data=$color_data->groupBy('colors.id')
											->orderBy('colors.name')
											->get()->toArray();
						
						if(sizeof($color_data)>0){
						    	$filter_value_data=$color_data;
						}
				}
				else if($filters_value=='Categories'){
					
			
                         if($cat_id!=''){
                 		    	$category_data = Category::select(
										'categories.id as fld_cat_id',
										'categories.name as fld_cat_name'
										);
				$category_data=$category_data
						->where('categories.status','=',1)
						->where('categories.isdeleted','=',0)
						->where('categories.parent_id','=',$cat_id)
											->groupBy('categories.id')
											->orderBy('categories.id')
											->get()->toArray();
										
				if(sizeof($category_data)>0){
					
				  $filter_value_data=$category_data;
				}
                 		  
						
					
                 	}
                 	
                 	
				
				}else if($filters_value=='BRAND'){
					
					$brand_data = Products::select(
											'brands.id as fld_id',
											'brands.name as fld_name',
											DB::raw("('BRAND') AS fld_filter_type")
											)
										->join('brands', 'products.product_brand', '=', 'brands.id')
										->where('products.isdeleted','=',0)
										->where('products.status','=',1);
				if($brand_id!=''){
					$brand_data= $brand_data->where('products.product_brand','=',$brand_id);
                 }
                 	
                 if($cat_id!=''){
					$brand_data= $brand_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
                 }
                 	
                 if($prd_name!=''){
					$brand_data=$brand_data
									->join('product_categories','products.id','=','product_categories.product_id')
									->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
                 }
				$brand_data=$brand_data->groupBy('brands.id')
									->orderBy('brands.name')
									->get()->toArray();	
					
					if(sizeof($brand_data)>0){
					    $filter_value_data=$brand_data;
						
						}
						
				}else if($filters_value=='PRICE'){
					
					$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
										->where('products.isdeleted','=',0)
										->where('products.status','=',1);
					
					if($brand_id!=''){
						$max_min= $max_min->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 	if($cat_id!=''){
						$max_min= $max_min->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
						$max_min=$max_min
									->join('product_categories','products.id','=','product_categories.product_id')
									->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
                 	}
						$data=$max_min->where('products.isdeleted',0)
								->where('products.status','=',1)->get()->first();
								
				   $min_price=!empty($data->min_price)?$data->min_price:'1';
				   $max_price= !empty($data->max_price)?$data->max_price:'1';
				   
				   $price_data=array(
				       "min_price"=>(int)$min_price,
				       "max_price"=>(int)$max_price,
				       "fld_filter_type"=>"PRICE"
				       );
				    $filter_value_data=$price_data;
				 
					
				}else if($filters_value=='MATERIAL'){
					
					$material_data = Products::select(
								'materials.id as fld_id',
								'materials.name as fld_name',
								DB::raw("('MATERIAL') AS fld_filter_type")
								)
							->join('materials', 'products.material', '=', 'materials.id')
							->where('products.isdeleted','=',0)
							->where('products.status','=',1);
           
           	if($brand_id!=''){
              $material_data= $material_data->where('products.product_brand','=',$brand_id);
                 	}
                 	
                 		if($cat_id!=''){
$material_data= $material_data->join('product_categories', 'products.id', '=', 'product_categories.product_id')

->where('product_categories.cat_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
                $material_data=$material_data
->join('product_categories','products.id','=','product_categories.product_id')
->join('categories','product_categories.product_id','=','products.id')
                ->Where(function($query) use ($prd_name){
								$query->where('products.isdeleted','=',0);
								$query->where('products.status','=',1);
										
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 });
                 	}
				$material_data=	$material_data->groupBy('materials.id')
					->orderBy('materials.name')
					->get()->toArray();
										
					if(sizeof($material_data)>0){
					     $filter_value_data=$material_data;
					}
				}else{
					$filters_records = Filters::select("filters.name","filters.id")
									->where('filters.name','=',$filters_value)
									->first();
					
					if(@$filters_records->id!='')
					{
					
					// 	if($filters_value==$filters_records->name){
					if($filters_records){
							$filters_data = DB::table('filter_values')->select(
																			'filter_values.id as fld_id',
																			'filter_values.filter_value as fld_name',
																			DB::raw("('$filters_value') AS fld_filter_type")
																			)
																	//->join('filters', 'filter_values.filter_id', '=', 'filters.id')
																	->join('product_filters', 'filter_values.id', '=', 'product_filters.filters_input_value')
																	->where('filter_values.filter_id','=',$filters_records->id);


					/*if($brand_id!=''){
							$filters_data= $filters_data->where('products.product_brand','=',$brand_id);
					}
						
					if($cat_id!=''){
							$filters_data=$filters_data->join('filters_category', 'filters.id', '=', 'filters_category.filter_id')
													->where('filters_category.cat_id','=',$cat_id);
					}*/
						
					if($prd_name!=''){
							$filters_data=$filters_data
									->join('product_categories','products.id','=','product_categories.product_id')
									->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												$query->where('products.isdeleted','=',0);
												$query->where('products.status','=',1);
								
												 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
					}
					 
					$filters_data=$filters_data
									->groupBy('filter_values.filter_id')
									->where('filter_values.filter_id','=',$filters_records->id)
									->get();
								
	// 								$filters_data = Products::select(
	// 								'product_filters.filters_id as filters_id',
	// 								'product_filters.filters_input_value as filters_input_value'
	// 								)
	// ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
	// ->join('product_filters', 'products.id', '=', 'product_filters.product_id')
	// ->join('filters', 'filters.id', '=', 'product_filters.filters_id')
	//                             ->where('product_categories.cat_id','=',$cat_id)
	//                             ->where('filters.name','=',$filters_value)
	// 							->groupBy('product_filters.filters_id')
	// 							->orderBy('product_filters.filters_id')
	// 							->get()->toArray();
						
							if(sizeof($filters_data)>0){
								 $filter_value_data=$filters_data;
							}
						}
					
				}else{
					$category_data = Category::select(
									'categories.id as fld_id',
									'categories.name as fld_name',
									DB::raw("('$filters_value') AS fld_filter_type")
								)
								->where('categories.status','=',1)
								->where('categories.isdeleted','=',0);
						
					if($brand_id!=''){
						/*$category_data= $category_data->where('products.product_brand','=',$brand_id);*/
                 	}
                 	
                 	if($cat_id!=''){
						/*$category_data= $category_data->join('product_categories', 'categories.id', '=', 'product_categories.cat_id')
												->where('categories.parent_id','=',$cat_id);*/
						$category_data= $category_data
												->where('categories.parent_id','=',$cat_id);
                 	}
                 	
                 	if($prd_name!=''){
						$category_data=$category_data
									->join('product_categories','categories.id','=','product_categories.product_id')
									//->join('categories','product_categories.product_id','=','products.id')
									->Where(function($query) use ($prd_name){
												 //$query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
												 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
											 });
                 	}
		
					$category_data=$category_data
										->groupBy('categories.id')
										->orderBy('categories.name')
										->get()->toArray();
										
						
					if(sizeof($category_data)>0){
						//$filter_value_data=$category_data;
					}
				}
				
				}
				

			
                
             if($filters_value=='PRICE'){
                    echo $this->msg_info('Product Filters Value',$filter_value_data,0,'filter_price_data');
                } else{
                    echo $this->msg_info('Product Filters Value',$filter_value_data,0,'filter_type_value_data');
                }
                
      }
     
    /* public function product_filter(Request $request){
            $input = json_decode(file_get_contents('php://input'), true);
            $brand_id=@$input['fld_brand_id'];
            $cat_id=@$input['fld_cat_id'];
            $prd_name=@$input['fld_search_txt'];
            	$filter_data=array();
            if($brand_id!=''){
		
						
		}elseif($cat_id!=''){
					
			//Filter Starts
			$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
				->join('product_categories', 'products.id', '=', 'product_categories.product_id')
				->where('product_categories.cat_id','=',$cat_id)
				->where('products.isexisting',0)
				->where('products.isdeleted',0)
				->where('products.status','=',1)->get()->first();
		   
		   $min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
		   $max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
		   
		   $price_data=array("min_price"=>(int)$min_price,"max_price"=>(int)$max_price);
		   
			$brand_data = Products::select('brands.id','brands.name')
								->join('brands', 'products.product_brand', '=', 'brands.id')
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							    ->where('product_categories.cat_id','=',$cat_id)
								->groupBy('brands.id')
								->orderBy('brands.id')
								->get()->toArray();	
	
			$color_data = Products::select(
			   'colors.id as fld_color_id',
                'colors.name as fld_color_name',
				'colors.color_code as fld_color_code'
			    )
										->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
										->join('product_categories', 'products.id', '=', 'product_categories.product_id')
										->join('colors', 'product_attributes.color_id', '=', 'colors.id')
										->where('product_categories.cat_id','=',$cat_id)
										->groupBy('colors.id')
										->orderBy('colors.id')
										->get()->toArray();	
			
			$size_data = Products::select(
			    'sizes.id as fld_size_id',
                'sizes.name as fld_size_name'
			    )
										->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
										->join('product_categories', 'products.id', '=', 'product_categories.product_id')
										->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
										->where('product_categories.cat_id','=',$cat_id)
										->groupBy('sizes.id')
										->orderBy('sizes.id')
										->get()->toArray();
										
			$filter_data=array('brand'=>$brand_data,'color'=>$color_data,'size'=>$size_data,'price'=>$price_data);
			//Filter Ends
		}elseif($prd_name!=''){
		
			//Filter Starts
			$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
	   	             ->join('product_categories','products.id','=','product_categories.product_id')
						->join('categories','product_categories.product_id','=','products.id')
						 ->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status','=',1)
						->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 })
						 ->groupBy('product_categories.product_id')
						 ->get()->first();
	   
			$min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
			$max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
			
			$price_data=array("min_price"=>(int)$min_price,"max_price"=>(int)$max_price);
			
			$filter_data=array('price'=>$price_data);
			//Filter Ends
						
			$allrecord =Products::select('products.id')
						//->join('product_categories','products.id','=','product_categories.product_id')
					     ->where('products.name','LIKE', '' . $prd_name . '%')
						 ->where('products.status',1)
                        ->get()
						->toarray();
		}else{
		
		}
		echo $this->msg_info('Product Filter',$filter_data,0,'filter_data_listing');
		 
     }*/
     
     
      public function product_listing(Request $request) {
				 
		$input = json_decode(file_get_contents('php://input'), true);
	   	//file_put_contents("search.txt",json_encode(	$input));
	         
            $sitecityname = 'NA';
            $sitecityId = '0';
        if(isset($input['city_id'])){
            $sitecityId= $input['city_id'];
            $sitecityname= $input['city_name'];
        }
    
	 

                if (!array_key_exists("fld_sort_by",$input))
                {
               $sortby=0;
                } else{
                      $sortby=$input['fld_sort_by'];
                }
		$brand_id=@$input['fld_brand_id'];
		$cat_id=@$input['fld_cat_id'];
		$user_id=@$input['fld_user_id'];
		$prd_name=@$input['fld_search_txt'];
		$fld_page_no=@$input['fld_page_no'];
		
		$page=$fld_page_no;
		if($page==0){
		    $page=1;
		    
		}
	
		if($page!=0){
		 $fld_page_no=($page-1)*10;
		}
		
		$prd_img=$this->site_base_path.'uploads/products/';
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		if($brand_id!='' && $brand_id!=0){
	
			$record =Products::select(
									'products.id',
									'product_categories.cat_id',
									'product_attributes.unisex_type',
									'product_attributes.color_id',
									'product_attributes.size_id',
									'product_attributes.price as extra_price',
									'products.name',
									'products.price',
									'products.spcl_price',
									'products.qty',
									DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
									DB::raw("CONCAT('$prd_img',zoom_image) AS gif_image"),
									DB::raw("(".Products::productRatings('products.id').") as fld_total_rating"),
									DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
									DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
								)
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				// 		->where('product_attributes.qty','>',0)
				// 		->where('products.isexisting',0)
						->where('products.isdeleted',0)
						->where('products.status',1)
						->where('products.product_brand',$brand_id);
                        
						
			$allrecord =Products::select('products.id')
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				// 		->where('product_attributes.qty','>',0)
				// 		->where('products.isexisting',0)
						->where('products.isdeleted',0)
						->where('products.status',1)
						->where('products.product_brand',$brand_id)
						->get()
						->toarray();
						
		}elseif($cat_id!='' && $cat_id!=0){
		
			//Filter Starts
			$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.price) as max_price"))
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                                ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                                ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                                ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
								->where('product_categories.cat_id','=',$cat_id)
								// ->where('products.isexisting',0)
								->where('products.isdeleted',0)
								->where('products.status','=',1)->get()->first();
								
			 $min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
			 $max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
		
			$record =Products::select(
								'products.id',
								'product_categories.cat_id',
								'product_attributes.unisex_type',
								'product_attributes.color_id',
								'product_attributes.size_id',
								'product_attributes.price as extra_price',
								'products.name',
								'products.price',
								'products.spcl_price',
								'products.qty',
								DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
								DB::raw("CONCAT('$prd_img',zoom_image) AS gif_image"),
								
								DB::raw("(".Products::productRatings('products.id').") as fld_total_rating"),
								DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
								DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
							)
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                        ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                        ->where('vendor_company_info.city','=',$sitecityname)
                        ->where('vendors.isdeleted',0)
                        ->where('vendors.status',1)
                        // ->where('product_attributes.qty','>',0)
                        // ->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status',1)
				        ->where('product_categories.cat_id',$cat_id)
						->where(function($query) use ($min_price,$max_price){
                                        // $query->orwhereBetween('products.spcl_price', array($min_price,$max_price));
                                        // $query->orwhere('products.spcl_price','','');
                                        // $query->orwhereBetween('products.price', array($min_price,$max_price));
								 });
								 
							
            
			$allrecord =Products::select('products.id')
                            ->join('product_categories','products.id','=','product_categories.product_id')
                            ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                            ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                            ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            // ->where('product_attributes.qty','>',0)
                            // ->where('products.isexisting',0)
                            ->where('products.isdeleted',0)
                            ->where('products.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname)
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('product_categories.cat_id',$cat_id)
						->where(function($query) use ($min_price,$max_price){
									 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
								 })
						->get()
						->toarray();
						
		}elseif($prd_name!=''){
		    
			$record =Products::select(
							'products.id',
							'product_attributes.unisex_type',
							'product_attributes.color_id',
							'product_attributes.size_id',
							'product_attributes.price as extra_price',
							'products.name',
							'products.price',
							'products.spcl_price',
							'products.qty',
							DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
								DB::raw("CONCAT('$prd_img',zoom_image) AS gif_image"),
							DB::raw("(".Products::productRatings('products.id').") as fld_total_rating"),
							DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
							DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
						)
                        ->join('product_categories','products.id','=','product_categories.product_id')
                        ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                        ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                        ->where('vendor_company_info.city','=',$sitecityname)
                        ->where('vendors.isdeleted',0)
                        ->where('vendors.status',1)
                         ->where('products.isdeleted',0)
						 ->where('products.status',1)
						 ->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 })
						->groupBy('product_categories.product_id');
                       
			$allrecord =Products::select('products.id')
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
					    ->where('products.name','LIKE', '' . $prd_name . '%')
				// 		->where('product_attributes.qty','>',0)
				// 		->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
						->where('products.status',1)
                        ->get()
						->toarray();
		}else{
		    
		    $prd_name='';
			$record =Products::select(
							'products.id',
							'product_attributes.unisex_type',
							'product_attributes.color_id',
							'product_attributes.size_id',
							'product_attributes.price as extra_price',
							'products.name',
							'products.price',
							'products.spcl_price',
							'products.qty',
							DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
								DB::raw("CONCAT('$prd_img',zoom_image) AS gif_image"),
							DB::raw("(".Products::productRatings('products.id').") as fld_total_rating"),
							DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
							DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
						)
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						//->join('categories','product_categories.product_id','=','products.id')
					   //  ->where('product_attributes.qty','>',0)
				// 		 ->where('products.isexisting',0)
                         ->where('products.isdeleted',0)
						 ->where('products.status',1)
						 /*->Where(function($query) use ($prd_name){
							 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
							 //$query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
						 })*/
						->groupBy('product_categories.product_id');
                       
			$allrecord =Products::select('products.id')
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
					    //->where('products.name','LIKE', '' . $prd_name . '%')
				// 		->where('product_attributes.qty','>',0)
				// 		->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
						->where('products.status',1)
                        ->get()
						->toarray();
		}
		
               //$record=$record->groupBy('products.id'); 
			   
                if($sortby!=''){
					if($sortby==1){
						$record=$record->orderBy("products.spcl_price","asc");
					}elseif($sortby==2){
						$record=$record->orderBy("products.spcl_price","desc");
					}elseif($sortby==3){
						$record=$record->orderBy("products.id","desc");
					} else{
					    $record=$record->orderBy("products.id",'desc');
					}
				}else{
						$record=$record->orderBy("products.id",'desc');
				}
				
			if($prd_setting[0]->product_shows_type==1)
			{
				$record=$record->groupBy("product_attributes.product_id");
			}
			
			  $record=$record ->offset($fld_page_no)
							->limit(10)
							->get()
							->toarray();
                
		$appnededRecord=array();
		foreach($record as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
				
				$extrs_price=0;
				if($row['color_id']!='' && $row['color_id']!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row['id'])
										->where('color_id',$row['color_id'])
										->first();

					$extrs_price=@$color_price->price;
				}
				
                    		$spcl=0;
                    $spcl=$row['price']+$extrs_price;
                    if($row['spcl_price']!=''){
                        $spcl=$row['spcl_price']+$extrs_price;
                    }
            
			
                // $row['default_image']="https://www.phaukat.com/uploads/products/".$row['default_image'];
                 /////$row['default_image']=$prd_img;
                $row['gif_image']=$row['default_image'];
				// $row['price']=$row['price'];
				// $row['spcl_price']=$spcl;
			
				$row['fld_total_rating']=Products::productRatings($row['id']);
				$row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
				array_push($appnededRecord,$row);
		}
		$message="Product Listing";
		$api_key="product_data";
		$grid_view=0; //list=0, grid=1, both=2
		
	
		
		echo $this->msg1_info($message,$appnededRecord,$page,$api_key,$grid_view,$allrecord);
		
	}
	
	/** 
     * Filter Product Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
      public function product_listing_filter(Request $request) {
				    
		$input = $request->all();
	     $sitecityname = 'NA';
           $sitecityId = '0';
        if(isset($input['city_id'])){
            $sitecityId= $input['city_id'];
            $sitecityname= $input['city_name'];
        }
	
		$cat_id=@$input['fld_cat_id'];
		$brand_id=@$input['fld_brand_id'];
		$size_id=@$input['fld_size_id'];
		$color_id=@$input['fld_color_id'];
		$max_price=@$input['fld_max_price'];
		$min_price=@$input['fld_min_price'];
		$fld_page_no=@$input['fld_page_no'];
		$user_id=@$input['fld_user_id'];
        $fld_material_id=@$input['fld_material_id'];
        $fld_other_id=@$input['fld_other_id'];
		$sortby=@$input['fld_sort_by'];//price asc,desc
		$prd_name=@$input['fld_search_txt'];
		
		$fld_scat_id=@$input['fld_scat_id'];
		
		if($fld_scat_id!='')
		{
			$cat_id='';
		}
		
		$page=($fld_page_no=="")?0:$fld_page_no;
		
		$filter_data=array();
		
		if($page!=0){
		 $fld_page_no=$fld_page_no*10;
		}
		
		if($min_price==0)
			$min_price='';
		if($max_price==0)
			$max_price='';
		
        $brands=array();
        $size_array=array();
        $color_array=array();
        $material_array=array();
        $other_filter_array=array();
		$category_filter_array=array();
		
		if($fld_scat_id!=''){
    	    $category_filter= explode(",",$fld_scat_id);
                    
    		foreach($category_filter as $row){
                    if (strpos($row, 'categoryvalue') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($category_filter_array,$id);
                    }
    		}
        }
		
		if($fld_other_id!=''){
    	    $other_filter= explode(",",$fld_other_id);
                    
    		foreach($other_filter as $row){
                    if (strpos($row, 'filtervalue') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($other_filter_array,$id);
                    }
    		}
        }
        
		if($brand_id!=''){
    	    $brand_filter= explode(",",$brand_id);
                    
    		foreach($brand_filter as $row){
                    if (strpos($row, 'brand') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($brands,$id);
                    }
    		}
        }
        
        if($fld_material_id!=''){
    	    $material_filter= explode(",",$fld_material_id);
                    
    		foreach($material_filter as $row){
                    if (strpos($row, 'material') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($material_array,$id);
                    }
    		}
        }
		
		if($color_id!=''){
    	    $color_filter= explode(",",$color_id);
                    
    		foreach($color_filter as $row){
                    if (strpos($row, 'color') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($color_array,$id);
                    }
                    
    		}
        }
		
		if($size_id!=''){
    	    $size_filter= explode(",",$size_id);
                    
    		foreach($size_filter as $row){
                    if (strpos($row, 'size') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($size_array,$id);
                    }
    		}
        }
		
			$prd_setting = DB::select("SELECT * FROM product_setting");
			
		    //$prd_img=$this->site_base_path.'uploads/products/';
			$prd_img='';
			  
		    $data = Products::select(
								'products.id',
								'product_categories.cat_id',
								'product_attributes.unisex_type',
								'product_attributes.color_id',
								'product_attributes.size_id',
								'product_attributes.price as extra_price',
								'products.name',
								'products.price',
								'products.spcl_price',
								'products.qty',
								DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
								DB::raw("(".Products::productRatings('products.id').") as fld_total_rating"),
								DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
								DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
							)
				      	->join('product_categories', 'products.id', '=', 'product_categories.product_id');
            $data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                        ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->join('vendors', 'products.vendor_id', '=', 'vendors.id');
			
             /*if(sizeof($color_array)>0 || sizeof($size_array)>0){
                $data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');
             }*/
             if(sizeof($color_array)>0){
                $data= $data->whereIn('product_attributes.color_id',$color_array); 
			 }
             
             if(sizeof($size_array)>0){
				$data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
             
             if(sizeof($material_array)>0){
                $data= $data->join('materials', 'products.material', '=', 'materials.id');
				$data= $data->whereIn('materials.id',$material_array); 
             }
             
			 if(sizeof($other_filter_array)>0){
                $data= $data->join('product_filters', 'products.id', '=', 'product_filters.product_id');
                $data= $data->whereIn('product_filters.filters_input_value',$other_filter_array); 
             }
			 
			 if(sizeof($category_filter_array)>0){
                $data= $data->whereIn('product_categories.cat_id',$category_filter_array); 
             }
             
			if($cat_id!='' && $cat_id!=0)
			{
			  $data = $data
						//->join('product_categories', 'products.id', '=', 'product_categories.product_id')
						->where('product_categories.cat_id','=',$cat_id);
						
				if($brand_id!=''  && $brand_id!=0){
					$data= $data->whereIn('products.product_brand',$brands);
                }
			}else{
				if($brand_id!=''  && $brand_id!=0){
					$data= $data->whereIn('products.product_brand',$brands);
                }
			}
			
			if($cat_id=='' && $cat_id!=0 && $brand_id!='' && $brand_id!=0){
			  $data= $data->where('products.product_brand','=',$brands);
            }
                 	
            if($prd_name!=''){
                $data=$data
						//->join('product_categories','products.id','=','product_categories.product_id')
						->join('categories','product_categories.product_id','=','products.id')
						->Where(function($query) use ($prd_name){
									 $query->orWhere('products.name','LIKE', '%' . $prd_name . '%');
									 $query->orWhere('categories.name','LIKE', '%' . $prd_name . '%');
								 });
								 
						$data = $data->groupBy('products.sku');
                 	}else{
						
						$data = $data->groupBy('products.id');
					}
            
			$data = $data 
                    ->where('products.isdeleted',0)
                    // ->where('product_attributes.qty','>',0)
                    //  ->where('products.isexisting',0)
                    ->where('vendor_company_info.city','=',$sitecityname)
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('products.status','=',1);
				 
				 /*->where(function($query) use ($min_price,$max_price){
					   $query->whereBetween('products.price', array($min_price,$max_price));
				 })*/
				 if($min_price!='' && $max_price!=''){
				    $data = $data->where(function($query) use ($min_price,$max_price){
					 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
				// 	  $query->orwhereBetween('products.price', array($min_price,$max_price));
				 }); 
				 }
				 	
				  	
				 
				 if(sizeof($brands)>0){
					 $data= $data->whereIn('products.product_brand',$brands); 
				 
				 }
			
			
			if($sortby!=''){
				
				if($sortby==1){
				     $data=$data->orderBy("products.spcl_price","asc");
			
				}elseif($sortby==2){
				      $data=$data->orderBy("products.spcl_price","desc");
				      
				}elseif($sortby==3){
				      $data=$data->orderBy("products.id","desc");
				      
				} else{
				    $data=$data->orderBy("products.id",'desc');
				}
				
			} else{
			    $data=$data->orderBy("products.id",'desc');
			   
			     
			}
			
			if($prd_setting[0]->product_shows_type==1)
			{
				$data=$data->groupBy("product_attributes.product_id");
			}
			
				$extra=$data;
			$allrecord= $extra->get()
						->toarray();
            $record=$data
                      ->offset($fld_page_no)
                        ->limit(10)
                        ->get()
						->toarray();
		
		$appnededRecord=array();
		foreach($record as $row){
			
			$color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);   
			
			if(sizeof($color_image)>0){
				$prd_img=@$color_image[0]->image;
			} else{
				$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
			}
			
			$extrs_price=0;
			if($row['color_id']!='' && $row['color_id']!=0)
			{
				$color_price=DB::table('product_attributes')
									->where('product_id',$row['id'])
									->where('color_id',$row['color_id'])
									 ->first();

				$extrs_price=@$color_price->price;
			}
			
		
				   $spcl=0;
                    $spcl=$row['price']+$extrs_price;
                    if($row['spcl_price']!=''){
                        $spcl=$row['spcl_price']+$extrs_price;
                    }
            
				$row['default_image']=$prd_img;
				// $row['price']=$row['price'];
				// $row['spcl_price']=$spcl;
				
		
				
		    $row['fld_total_rating']=Products::productRatings($row['id']);
		    $row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
		    array_push($appnededRecord,$row);
		}
		$message="More products";
		$api_key="product_data";
		
		
			
		echo $this->msg_info($message,$appnededRecord,$page,$api_key,$allrecord);
		
	}
	
	/** 
     * Product Detail api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
    public function product_detail(Request $request) {
				    
          	$input = $request->all();
          	file_put_contents("details.txt",json_encode($input));
          	   
            $sitecityname = 'NA';
            $sitecityId = '0';
    if(isset($input['city_id'])){
        $sitecityId= $input['city_id'];
        $sitecityname= $input['city_name'];
    }
	
            $product_id=@$input['fld_product_id'];
            $user_id=@$input['fld_user_id'];
            
            $color_id=@$input['fld_color_id'];
            $size_id=@$input['fld_size_id'];
		
		
		$prd_img=$this->site_base_path.'uploads/products/';
			
			$record =Products::select(
						'products.id',
						'products.isdeleted',
						'products.vendor_id',
						'product_categories.cat_id',
						'product_attributes.unisex_type',
						'product_attributes.color_id',
						'product_attributes.size_id',
						'product_attributes.women_size_id',
						'product_attributes.price as extra_price',
						'products.name',
						'products.product_type',
						'products.price',
						'products.spcl_price',
						'products.sku',
						'products.short_description',
						'products.long_description',
						'products.tax_class as size_chart',
						'products.qty',
						'products.delivery_days',
						'products.shipping_charges',
						'products.product_size_chart',
						DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
						DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
						DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count"),
						DB::raw("(SELECT GROUP_CONCAT(product_attributes.unisex_type)  FROM `product_attributes` WHERE product_attributes.qty>0 && `product_id` = products.id) as unisex_type"),
						DB::raw("(SELECT GROUP_CONCAT(product_attributes.size_id)  FROM `product_attributes` WHERE   `product_id` = products.id) as size_id"),
						DB::raw("(SELECT GROUP_CONCAT(product_attributes.women_size_id)  FROM `product_attributes` WHERE product_attributes.qty>0 && `product_id` = products.id) as women_size_id"),
						DB::raw("(SELECT GROUP_CONCAT(product_attributes.color_id)   FROM `product_attributes` WHERE  `product_id` = products.id) as color_id")
					)
				->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				->join('product_categories','products.id','=','product_categories.product_id')
				//->where('product_attributes.qty','>',0)
				//->where('products.isdeleted','1')
				->where('products.status','1')
				->where('products.id',$product_id)
				->first();  
	
		if($record){
		    $url='';
		     
		   
			if($record->product_size_chart){
				$url=URL::to('/uploads/sizechart').'/'.$record->product_size_chart;
			}
		    
			$record->fld_total_rating=Products::productRatings($product_id);
		    $myBagArray = (array)$record; 
			
			$unisextype=explode(',',$record->unisex_type);
			
			$a1=array(1,2);
            $result=array_intersect($unisextype,$a1);

            if($result)
            {
                $sizes=DB::table('product_attributes')->select(
    							DB::raw('group_concat(size_id) as size_id')
    							)
    							->where('qty','>',0)
    							->where('product_id',$record->id)
    							->where('unisex_type',1)
    							->get()
    							->first();
    							
    			$size_id=$sizes->size_id;
    			$size_id=explode(',',$size_id);
    			
    			$women_sizes=DB::table('product_attributes')->select(
    							DB::raw('group_concat(women_size_id) as women_size_id')
    							)
    							->where('qty','>',0)
    							->where('product_id',$record->id)
    							->where('unisex_type',2)
    							->get()
    							->first();
    							
    			$women_size_id=$women_sizes->women_size_id;
    			$women_size_id=explode(',',$women_size_id);
    		    
    		    $size_record=array();
    		    $size_record =DB::table('sizes')->select(
    								'sizes.id as fld_size_id',
    								'sizes.name as fld_size_name'
    							)
    							->whereIn('sizes.id',$size_id)
    							->get()
    							->toArray();
    		   	
    			$women_size_record=array();
    			$women_size_record =DB::table('sizes')->select(
    								'sizes.id as fld_size_id',
    								'sizes.name as fld_size_name'
    							)
    							->whereIn('sizes.id',$women_size_id)
    							->get()
    							->toArray();
            }else{
                
                $size_record=array();
    		    $size_id=explode(',',$record->size_id);
    		    $size_id=(array_unique($size_id));
    		   
    		   
    		    $size_record =DB::table('sizes')->select(
    								'sizes.id as fld_size_id',
    								'sizes.name as fld_size_name'
    							)
    							->whereIn('sizes.id',$size_id)
    							->where('sizes.status',1)
    							->where('sizes.isdeleted',0)
    							->get()
    							->toArray();
    							
    							$women_size_record=array();
            }
			
			
		    
			$color_record=array();
			$image_record=array();
		    $color_id=explode(',',$record->color_id);
		    
		    if($record->product_type==3 || $record->product_type==2){
		       if(sizeof($color_id)>0){
		           $images_record =DB::table('product_configuration_images')->select(
										DB::raw("CONCAT('$prd_img',product_config_image) AS image")
										)
										//->join('categories','categories.id','product_categories.cat_id')
										->where('product_configuration_images.product_id',$product_id)
										->where('product_configuration_images.color_id',$color_id[0])
										->orderBy('id','ASC')
										->get()
										->toArray();
										
										
					if(sizeof($images_record)>0){
						$record->default_image=$images_record[0]->image;
					}
					
		       }
		    } 
            else{
		        	$images_record =DB::table('product_images')->select(
											DB::raw("CONCAT('$prd_img',image) AS image")
											)
											->where('product_images.product_id',$record->id)
											->orderBy('id','ASC')
											->get()
											->toArray();
					$single_image=array("image"=>$record->default_image);
					array_push($images_record,$single_image);
					$images_record=array_reverse($images_record);
		    }
		    
			/*$color_record =DB::table('colors')->select(
										'colors.id as fld_color_id',
										'colors.name as fld_color_name',
										'colors.color_code as fld_color_code',
										 DB::raw("CONCAT('".$this->site_base_path."uploads/color/',color_image) AS fld_color_thumbnail")
									)
									->whereIn('colors.id',$color_id)
									->get()
									->toArray();*/
								
                        $obj=new ProductAttributes();
                        $data=$obj->select('product_attributes.color_id as fld_color_id','colors.name as fld_color_name','colors.color_code as fld_color_code',
                          DB::raw(' "" as fld_color_thumbnail')
                        )
                                   ->join('colors','colors.id','product_attributes.color_id');
                        $data=$data->where('product_attributes.product_id',$record->id);
                        $data=$data->where('product_attributes.color_id','!=',0)
                        ->orderBy('product_attributes.id','asc');
                        $data->groupBy('product_attributes.color_id');
                        
                        $data=$data->get()->toarray();
                        
			$color_record=DB::table('product_configuration_images')
								->select(
										'colors.id as fld_color_id',
										'colors.name as fld_color_name',
										'colors.color_code as fld_color_code',
										 DB::raw("CONCAT('".$this->site_base_path."uploads/products/240-180/',product_configuration_images.product_config_image) AS fld_color_thumbnail")
									)
								->join('colors','colors.id','product_configuration_images.color_id')
								->where('product_configuration_images.product_id',$record->id)
								->whereIn('product_configuration_images.color_id',$color_id)
								// ->groupBy('product_configuration_images.color_id')
								->groupBy('color_id')
								->orderBy('colors.id','ASC')
								->get()
								->toArray();
	
		$attributes=array('sizes'=>$size_record,'women_sizes'=>$women_size_record,'colors'=>($data));
	
                    
        $vdr_data=Vendor::where('id',$record->vendor_id)->first();
         
		$extrs_price=0;
		if($record->color_id!='' && $record->color_id!=0)
		{
			$color_price=DB::table('product_attributes')
								->where('product_id',$record->id)
								->where('color_id',$record->color_id)
								->where('qty','>',0)->first();

			$extrs_price=@$color_price->price;
		}
		
		$product_url=Products::getProductDetailUrl($record->name,$record->id,$record->size_id,$record->color_id);
            $spcl=0;
            $spcl=$record->spcl_price+$extrs_price;
          
          
          
          
          
          $moreSeller = Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price",'vendors.username as seller_name')
							->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
	                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            ->join('vendor_existing_product', 'products.id', '=', 'vendor_existing_product.product_id')
                                    //  ->where('products.isexisting',1)
                            ->Where(function($query) use ($product_id){
                                        $query->orWhere('vendor_existing_product.master_product_id',$product_id);
                                        $query->orWhere('vendor_existing_product.product_id',$product_id);
                                
                                })
                            ->where('products.isdeleted',0)
                            ->where('products.status','=',1);
                            
	      $moreSeller = $moreSeller->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname);
	 
       
        $moreSeller= $moreSeller->groupBy("product_attributes.product_id");
        $moreSeller=$moreSeller ->orderBy("products.id",'desc')->count();
        
		$record=array(
		           "fld_product_type"=>$record->product_type,
					"fld_product_id"=>$record->id,
					"fld_product_url"=>$product_url,
					"fld_product_deleted"=>$record->isdeleted,
					"fld_product_name"=>$record->name,
                    "isInWishlist"=>Customer::productInWishlist($user_id,$product_id),
                    "isInCart"=>Customer::productInCart($user_id,$product_id,$color_id,$size_id),
                    "alreadyInCart"=>Customer::productInCart($user_id,$product_id,$color_id,$size_id),
					"fld_product_price"=>$record->price+$extrs_price,
					"fld_product_spcl_price"=>$spcl,
					"fld_product_sku"=>$record->sku,
					"size_chart"=>$url,
					'attr'=>$data,
					"fld_product_short_description"=>route('productDesc', [0,$record->id]),
					"fld_product_long_description"=>route('productDesc', [1,$record->id]),
					"fld_product_qty"=>$record->qty,
					"fld_product_image"=>$record->default_image,
					"attributes"=>$attributes,
					"thumbnail"=>($images_record),
                    "fld_total_rating"=>$record->fld_total_rating,
                    "fld_rating_count"=>$record->fld_rating_count,
                    "fld_review_count"=>$record->fld_review_count,
					"fld_more_seller"=>($moreSeller)>0?true:false,
                    "fld_seller_info"=>$record->vendor_id!=0>0?
                    array(
                            "fld_seller_name"=>$vdr_data?$vdr_data->public_name:'',
                            "fld_seller_rating"=>3,
                            "fld_return_policy_days"=>$record->return_day?$record->return_days:''
                        )
                    :array(
                        "fld_seller_name"=>'',
                            "fld_seller_rating"=>0,
                            "fld_return_policy_days"=>''
                        ),
                    "fld_delivery_days"=>$record->delivery_days?$record->delivery_days:'',
                    "fld_shipping_charges"=>$record->shipping_charges?$record->shipping_charges:0,
					
				);
			
			$message="Product Details";
			$api_key="product_detail_data";
		} else{
		   	$message="Product Details";
			$api_key="product_detail_data"; 
		}
		
		$page=0;
		
		echo $this->msg_info($message,$record,$page,$api_key);
	}
	
	public function coloredImages(){
	    	$input = json_decode(file_get_contents('php://input'), true);
	    	$prd_data=Products::productDetails($input['fld_product_id']);
	    	if($prd_data->product_type==3){
	    	     $record=DB::table('product_configuration_images')
            ->select(
            DB::raw("CONCAT('".$this->site_base_path."uploads/products/',product_configuration_images.product_config_image) AS image")
            )
               ->where('product_id',$input['fld_product_id'])
                ->where('color_id',$input['fld_color_id'])
                ->get()->toarray();
	    	} else{
	    	    $record=array();
	    	}
	    	$product=Products::productDetails($input['fld_product_id']);
                $old_prc=$product->price;
                if ($product->spcl_price!='' && $product->spcl_price!=0){
                $prc=$product->spcl_price;
                }else{
                $prc=$product->price;
                }
								   if(@$input['fld_color_id']==0 && @$input['size_id']!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['fld_product_id'])
		     ->where('size_id', @$input['size_id'])
			 ->where('qty','>',0)
		    ->first();
		     $prc+=$attr_data->price;
		      $old_prc+=$attr_data->price;
		}
  if(@$input['fld_color_id']!=0 && @$input['size_id']==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$input['fld_product_id'])
		     ->where('color_id',@$input['fld_color_id'])
			 ->where('qty','>',0)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	           if(@$input['fld_color_id']!=0 && @$input['size_id']!=0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['fld_product_id'])
		     ->where('color_id',@$input['fld_color_id'])
		     ->where('size_id', @$input['size_id'])
			 ->where('qty','>',0)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
            $message="color image listing";
            $api_key="color_image_data";
           
            
           	if($record){
		   	$res=array(
                    "status"=>true,
                    "statusCode"=>201,
                    "message"=>$message,
                   "fld_product_price"=>$old_prc,
                    "fld_spcl_price"=>$prc,
					$api_key=>$record
				); 
		} else{
		    	$res=array(
                    "status"=>false,
                    "statusCode"=>404,
                    "message"=>"No data found",
                   "fld_product_price"=>$old_prc,
                    "fld_spcl_price"=>$prc,
                   $api_key=>$record
				);
		}
		
echo json_encode($res);
	
	}
	
	public function get_dependend_color(){
         	$input = json_decode(file_get_contents('php://input'), true);
         	file_put_contents('color.txt',json_encode($input));
         	$obj=new Products();
         	$attr_name="Colors";


		
			
			$message="color listing";
			$api_key="colors_data";
		
		
		
			$product=Products::productDetails($input['fld_product_id']);
                    $old_prc=$product->price;
                   $prc=$product->spcl_price;
              
                
                	  
                
                $attr_data=DB::table('product_attributes')
                ->where('product_id',$input['fld_product_id'])
                ->where('size_id',@$input['fld_size_id'])
                //  ->where('qty','>',0)
                ->first();
                $prc+=$attr_data->price;
                if($old_prc!=0){
		          $old_prc+=$attr_data->price;
		    }
              
               if (strpos($attr_data->qty, '-') === 0)
                    {
                    $attr_dataqty = "0";
                    }
                    else
                    {
                $attr_dataqty = $attr_data->qty;
                    }
        
           
          
		   	$res=array(
                    "status"=>true,
                    "statusCode"=>201,
                    "message"=>$message,
                    "fld_product_price"=>$old_prc,
                    "fld_spcl_price"=>$prc,
                     "qty"=>$attr_dataqty
				); 
	
		
echo json_encode($res);
	}
	
	public function get_dependend_size(){
         	$input = json_decode(file_get_contents('php://input'), true);
         	file_put_contents('size.txt',json_encode($input));
         	$obj=new Products();
         	$attr_name="Sizes";
			$data=$obj->getProductsAttributes($attr_name,$input['fld_color_id'],$input['fld_product_id']);
			$sizes=array();
			$women_sizes=array();
			
			foreach($data as $row){
			    if($row['unisex_type']==1)
			    {
    				$size_data=Products::getsizeNameAndCode('Sizes',$row['size_id']);
    				array_push($sizes,array(
    						"fld_size_id"=>$row['size_id'],
    						"fld_size_name"=>$size_data->name
    					));
			    }elseif($row['unisex_type']==2){
    				$size_data=Products::getsizeNameAndCode('Sizes',$row['size_id']);
    				array_push($women_sizes,array(
    						"fld_size_id"=>$row['size_id'],
    						"fld_size_name"=>$size_data->name
    					));
			    }else{
			        $size_data=Products::getsizeNameAndCode('Sizes',$row['size_id']);
    				array_push($sizes,array(
    						"fld_size_id"=>$row['size_id'],
    						"fld_size_name"=>$size_data->name
    					));
			    }
			}
			
			$message="size listing";
			$api_key="sizes_data";
			
			$product=Products::productDetails($input['fld_product_id']);
                        $old_prc=$product->price;
                        $prc=$product->spcl_price;
             
		
		if(@$input['fld_color_id']==0 && @$input['fld_size_id']!=0){
		    
		    $attr_data=DB::table('product_attributes')
							->where('product_id',$input['fld_product_id'])
							->where('size_id',@$input['fld_size_id'])
				// 			->where('qty','>',0)
							->first();
		    $prc+=$attr_data->price;
		  
		    if($old_prc!=0){
		          $old_prc+=$attr_data->price;
		    }
		}
		
		if(@$input['fld_color_id']!=0 && @$input['fld_size_id']==0){
		     $attr_data=DB::table('product_attributes')
							->where('product_id',$input['fld_product_id'])
							->where('color_id',@$input['fld_color_id'])
				// 			->where('qty','>',0)
							->first();
			 $prc+=$attr_data->price;
		     if($old_prc!=0){
		          $old_prc+=$attr_data->price;
		    }
		}
	    
		if(@$input['fld_color_id']!=0 && @$input['fld_size_id']!=0){
		     $attr_data=DB::table('product_attributes')
							->where('product_id',$input['fld_product_id'])
							->where('color_id',@$input['fld_color_id'])
							->where('size_id',@$input['fld_size_id'])
				// 			->where('qty','>',0)
							->first();
		    $prc+=$attr_data->price;
		    if($old_prc!=0){
		          $old_prc+=$attr_data->price;
		    }
		}
            if (strpos($attr_data->qty, '-') === 0)
                    {

                    $attr_dataqty = "0";

                    }
                    else
                    {

                        $attr_dataqty = $attr_data->qty;

                    }
                    
           
        if($sizes){
		   	$res=array(
                    "status"=>true,
                    "statusCode"=>201,
                    "message"=>$message,
                    "fld_product_price"=>$old_prc,
                    "fld_spcl_price"=>$prc,
                     "qty"=>$attr_dataqty,
					$api_key=>$sizes,
					"women_sizes_data"=>$women_sizes
				); 
		} else{
		    	$res=array(
                    "status"=>false,
                    "statusCode"=>404,
                    "message"=>"No data found",
                    "fld_product_price"=>$old_prc,
                    "fld_spcl_price"=>$prc,
                   $api_key=>$sizes,
                   "women_sizes_data"=>$women_sizes
				);
		}
		
		echo json_encode($res);
	}
	
	public function more_seller(Request $request){
                $input = $request->all();
                $sitecityname = 'NA';
                $sitecityId = '0';
    if(isset($input['city_id'])){
        $sitecityId= $input['city_id'];
        $sitecityname= $input['city_name'];
    }

            $fld_page_no=@$input['fld_page_no'];
            $page=$fld_page_no;
            	
            	if($page!=0){
            	 $fld_page_no=$fld_page_no*10;
            	}
            	
            	
            $product_id=@$input['fld_product_id'];
			$pincode=@$input['fld_pincode'];
			
			
			$prd_img=$this->site_base_path.'uploads/products/';
            
            $first_pro =Products::select(
									'products.vendor_id',
									'product_attributes.color_id',
									'product_attributes.size_id',
									'product_attributes.price as extra_price',
									'products.name',
										'vendors.username as seller_name',
									'products.price',
									'products.spcl_price',
									'products.sku',
									'products.short_description',
										'products.product_type',
									DB::raw("CONCAT('$prd_img',default_image) AS default_image")
								)
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				     	->join('vendors','products.vendor_id','vendors.id')
						->where('products.status','1')
						->where('products.id',$product_id)
						->first()->toarray();
						
           $datas = Products::select(
                    'products.vendor_id',
                    'products.product_type',
                    'products.id as fld_product_id',
                    'product_attributes.color_id as fld_color_id',
                    'product_attributes.size_id as fld_size_id',
                    'product_attributes.price as extra_price',
                    'products.name as fld_product_name',
                    'products.spcl_price as fld_spcl_price',
                    'products.price as fld_product_price',
                    'products.shipping_charges as fld_shipping_charges',
                    'vendors.username as fld_seller_name',
                    'vendors.status as fld_seller_rating'
                    )
							->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
	                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            ->join('vendor_existing_product', 'products.id', '=', 'vendor_existing_product.product_id')
                                    //  ->where('products.isexisting',1)
                            ->Where(function($query) use ($product_id){
                                        $query->orWhere('vendor_existing_product.master_product_id',$product_id);
                                        $query->orWhere('vendor_existing_product.product_id',$product_id);
                                
                                })
                            ->where('products.isdeleted',0)
                            ->where('products.status','=',1);
                            
	      $datas = $datas->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname);
	 
       
        $datas= $datas->groupBy("product_attributes.product_id");
        $datas=$datas ->orderBy("products.id",'desc')
                                ->offset($fld_page_no)
                                ->limit(10)
                                ->get()
                                ->toarray();
		
			
		

		
             
            $allrecord = Products::select(
                    'products.vendor_id',
                    'products.product_type',
                    'products.id as fld_product_id',
                    'product_attributes.color_id as fld_color_id',
                    'product_attributes.size_id as fld_size_id',
                    'product_attributes.price as extra_price',
                    'products.name as fld_product_name',
                    'products.spcl_price as fld_spcl_price',
                    'products.price as fld_product_price',
                    'products.shipping_charges as fld_shipping_charges',
                    'vendors.username as fld_seller_name',
                    'vendors.status as fld_seller_rating'
                    )
							->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
	                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            ->join('vendor_existing_product', 'products.id', '=', 'vendor_existing_product.product_id')
                                    //  ->where('products.isexisting',1)
                            ->Where(function($query) use ($product_id){
                                        $query->orWhere('vendor_existing_product.master_product_id',$product_id);
                                        $query->orWhere('vendor_existing_product.product_id',$product_id);
                                
                                })
                            ->where('products.isdeleted',0)
                            ->where('products.status','=',1);
                            
	      $allrecord = $allrecord->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname);
	 
       
        $allrecord= $allrecord->groupBy("product_attributes.product_id");
        $allrecord=$allrecord ->orderBy("products.id",'desc')
                                ->get()
                                ->toarray();
		
             
        $message="more seller Listing";
        $api_key="seller_data";
        $fld_parent_product='fld_parent_product';
		
	echo  $this->msg_info($message,$datas,$page,$api_key,$allrecord,$fld_parent_product,$first_pro);
         
     }
	
	
	public function product_review_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
		$product_id=@$input['fld_product_id'];
		$fld_page_no=@$input['fld_page_no'];
		$page=$fld_page_no;
		if($page!=0){
			$fld_page_no=$fld_page_no*10;
		}
	
		if(@$input['fld_flag']=='All'){
		    	
			$record =Products::select(
							'product_rating.user_name',
							'product_rating.rating',
							'product_rating.review',
							'product_rating.review_date',
							DB::raw("(IF(product_rating.uploads!='', CONCAT('".$this->site_base_path."uploads/review/',product_rating.uploads),null)) as uploads")
						)
							->join('product_rating', 'products.id', '=', 'product_rating.product_id')
							->where('products.status',1)
				// 			->where('products.isexisting',0)
							->where('product_rating.product_id',$product_id)
							->offset($fld_page_no)
							->limit(10)
							->get()
							->toarray();
		} else{
		    	
				$record =Products::select(
								'product_rating.user_name',
								'product_rating.rating',
								'product_rating.review',
							DB::raw("(IF(product_rating.uploads!='', CONCAT('".$this->site_base_path."uploads/review/',product_rating.uploads),null)) as uploads"),
								'product_rating.review_date'
							)
								->join('product_rating', 'products.id', '=', 'product_rating.product_id')
								->where('products.status',1)
								// ->where('products.isexisting',0)
								->where('product_rating.product_id',$product_id)
								->offset($fld_page_no)
								->limit(3)
								->get()
								->toarray();			
		}
		
		$allrecord =Products::select(
                        'product_rating.user_name'
    			    )
						->join('product_rating', 'products.id', '=', 'product_rating.product_id')
						->where('products.status',1)
				// 		->where('products.isexisting',0)
						->where('product_rating.product_id',$product_id)
						->get()
						->toarray();
						
		$message="Product Review Listing";
		$api_key="product_review_data";
			
		echo $this->msg_info($message,$record,$page,$api_key,$allrecord);
	}
	
	public function similar_product_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	
		$product_id=$input['fld_product_id'];
		$sitecityname = 'NA';
                $sitecityId = '0';
        if(isset($input['city_id'])){
            $sitecityId= $input['city_id'];
            $sitecityname= $input['city_name'];
        }
        
	
		$obj=new ProductCategories();
	    $cats=$obj->getCategories($product_id);
	    		    
		$cat_id=@$input['fld_cat_id'];
		$fld_page_no=@$input['fld_page_no'];
		$page=$fld_page_no;
			if($page!=0){
			    $fld_page_no=$fld_page_no*10;
			}
		
	   $prd_img=$this->site_base_path.'uploads/products/';
// 		$prd_img='';
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
			$record=ProductCategories::
							   select(
										'products.id',
										'product_categories.cat_id',
										DB::raw(' "" as unisex_type'),
									
										'product_attributes.color_id',
										'product_attributes.size_id',
										'product_attributes.price as extra_price',
										'products.name',
										'products.price',
										'products.spcl_price',
										DB::raw("CONCAT('$prd_img',default_image) AS default_image")
									)
							->join('products', 'product_categories.product_id', '=', 'products.id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                            ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                            ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            	->groupBy('product_categories.product_id')
                            ->where('vendor_company_info.city','=',$sitecityname)
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)

						
							->whereIn('product_categories.cat_id',$cats)
				// 			->where('product_attributes.qty','>',0)
				// 			->where('products.isexisting',0)
							->where('products.isdeleted',0)
							->where('products.status','=',1);
							
							
			if($prd_setting[0]->product_shows_type==1)
			{
				$record=$record->groupBy("product_attributes.product_id");
			}
			
				$record=$record->offset($fld_page_no)
							->limit(10)
							->get()
							->toarray();
						
						
             $allrecord=ProductCategories::
								select(
									'products.id',
									'products.name',
									'products.price',
									'products.spcl_price',
									DB::raw("CONCAT('$prd_img',default_image) AS default_image")
								)
							->join('products', 'product_categories.product_id', '=', 'products.id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->groupBy('product_categories.product_id')
							->whereIn('product_categories.cat_id',$cats)
				// 			->where('product_attributes.qty','>',0)
				// 			->where('products.isexisting',0)
							->where('products.isdeleted',0)
							->where('products.status','=',1)
							->get()
							->toarray();
		
		$appnededRecord=array();
		foreach($record as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);   
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
				
				$extrs_price=0;
				if($row['color_id']!='' && $row['color_id']!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row['id'])
										->where('color_id',$row['color_id'])
										->where('qty','>',0)->first();

					$extrs_price=$color_price->price;
				}
				
				/////$row['default_image']=$prd_img;
				$row['price']=$row['price']+$extrs_price;
				$row['spcl_price']=$row['spcl_price']+$extrs_price;
				//$row['fld_total_rating']=Products::productRatings($row['id']);
				//$row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
				array_push($appnededRecord,$row);
		}
		$message="Similar Product Listing";
		$api_key="similar_product_data";
			
		echo $this->msg_info34($message,$appnededRecord,0,$api_key,$allrecord);
	}
	
	public function frequent_purchase_product_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
		$cust_id=@$input['fld_user_id'];
		$fld_page_no=@$input['fld_page_no'];
		$page=$fld_page_no;
		if($page!=0){
			 $fld_page_no=$fld_page_no*10;
		}
		
		//$prd_img=$this->site_base_path.'uploads/products/';
		$prd_img='';
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$record =Products::select(
							'products.id',
							'product_categories.cat_id',
							'product_attributes.unisex_type',
							'product_attributes.color_id',
							'product_attributes.size_id',
							'product_attributes.price as extra_price',
							'products.name',
							'products.price',
							'products.spcl_price',
							DB::raw("CONCAT('$prd_img',default_image) AS default_image")
						)
						->join('order_details', 'products.id', '=', 'order_details.product_id')
						->join('orders', 'order_details.order_id', '=', 'orders.id')
						
						->join('product_categories', 'products.id', '=', 'product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				// 		->where('product_attributes.qty','>',0)
						//->where('products.status',1)
						//->where('products.isexisting',0)
						//->where('products.isdeleted',0);
						->where('orders.customer_id',$cust_id);
		
		if($prd_setting[0]->product_shows_type==1)
		{
			$record=$record->groupBy("product_attributes.product_id");
		}
						//->groupBy('products.id')
			$record=$record->offset($fld_page_no)
                        ->limit(10)
						->get()
						->toarray();
            	
			$allrecord =Products::select(
							'products.id'
						)
						->join('order_details', 'products.id', '=', 'order_details.product_id')
						->join('orders', 'order_details.order_id', '=', 'orders.id')
						
						->join('product_categories', 'products.id', '=', 'product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				// 		->where('product_attributes.qty','>',0)
						//->where('products.status',1)
						//->where('products.isexisting',0)
						//->where('products.isdeleted',0)
						->where('orders.customer_id',$cust_id)
						//->groupBy('products.id')
						->get()
						->toarray();
	
		$appnededRecord=array();
		foreach($record as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);   
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
				
				$extrs_price=0;
				if($row['color_id']!='' && $row['color_id']!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row['id'])
										->where('color_id',$row['color_id'])
								// 		->where('qty','>',0)
										->first();

					$extrs_price=$color_price->price;
				}
				
				/////$row['default_image']=$prd_img;
				$row['price']=$row['price']+$extrs_price;
				$row['spcl_price']=$row['spcl_price']+$extrs_price;
				//$row['fld_total_rating']=Products::productRatings($row['id']);
				//$row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
				array_push($appnededRecord,$row);
		}
		
		$message="Frequent Purchased Product Listing";
		$api_key="frequent_purchased_product_data";
			
		echo $this->msg_info($message,$appnededRecord,$page,$api_key,$allrecord);
	}
	
	public function dummycheckPinCode(){
	    $input = json_decode(file_get_contents('php://input'), true);
	   
	   
		$record=DB::table('logistic_vendor_pincode')->where('pincode',$input['fld_pincode'])->first();
      if($record){
              $message="Delivery not available in this area";
      } else{
               
				 $message="Delivery not available in this area";
      }
    
	 $message="Delivery available in this area";
	 $api_key="pincode_data";
	 $page=0;
		
	 echo $this->msg_info($message,$record,$page,$api_key);
	}
	public function checkPinCode(){
	  
	    
	        $message="";
 	    $input = json_decode(file_get_contents('php://input'), true);
 	    
 	    $data_inputs=array(
                    "pincode"=>$input['fld_pincode'],
                    "price"=>299,
                    "product_name"=>"apple",
                    "qty"=>1,
                    "weight"=>250,
                    "height"=>20,
                    "length"=>20,
                    "width"=>20
                    );
 	    
 	     $back_response=CommonHelper::checkDelivery($data_inputs);
 	
        $output = (array)json_decode($back_response);
           if (array_key_exists("delivery_details",$output))
            {
                 $days=$output['couriers'][0]->service_types[0]->expected_delivery_days;
                         $date=date('d M Y');
                         $dt=date('d M Y', strtotime($date. ' + '.$days.' days'));
                         
                $date=$output['couriers'][0]->exp_pickup_date;
                $message="Delivery available in this area";
                
                $res=array(
	        "status"=>true,
	        "statusCode"=>201,
	        "message"=>'Delivery available in this area',
	          "data"=>"<ul><li>Expected Delivery Date: $dt</li><li>Cash/Card on Delivery Available</li><li>Easy 7 Days Return Available</li></ul>",
	        );
	          echo json_encode($res);
	          die();
	          
            } else{
                  
                	
                	$res=array(
	        "status"=>false,
	        "statusCode"=>404,
	        "message"=>'Delivery not available in this area',
	          "data"=>"<ul><li>No Expected Delivery</li><li>Cash/Card on Delivery Available</li><li>Easy 7 Days Return Available</li></ul>",
	        );
	          echo json_encode($res);
	          die();
            }
    
	}
	
	
	
	/** 
     * Brand Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
      public function brand_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
		$fld_page_no=@$input['fld_page_no'];
		$page=$fld_page_no;
		
		if($page!=0){
		 $fld_page_no=$fld_page_no*10;
		}
		
			$record =Brands::select(
                'brands.id',
				'brands.name',
	DB::raw("(IF(brands.logo!='', CONCAT('".$this->site_base_path."uploads/brand/logo/',brands.logo),null)) as logo"),
   	DB::raw("(IF(brands.banner_image!='', CONCAT('".$this->site_base_path."uploads/brand/banner/',brands.banner_image),null)) as banner_image")
			)
						->where('brands.isdeleted',0)
						->offset($fld_page_no)
                        ->limit(10)
						->get()
						->toarray();
						
			$allrecord =Brands::select(
							'brands.id'
						)
						->where('brands.isdeleted',0)
						->get()
						->toarray();
		
		
		$message="Brand Listing";
		$api_key="brand_data";
		
		echo $this->msg_info($message,$record,$page,$api_key,$allrecord);
		
	}
	
	/** 
     * Home Product Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function home_product_listing(Request $request) {
				    
           	$input = $request->all();
            $prd_item_type=$input['fld_product_type'];
            $fld_page_no=$input['fld_page_no'];
            
            $sitecityname = 'NA';
            $sitecityId = '0';
    if(isset($input['city_id'])){
        $sitecityId= $input['city_id'];
        $sitecityname= $input['city_name'];
    }
		
		$page=$fld_page_no;
		if($page!=0){
			$fld_page_no=$fld_page_no*10;
		}
		$prd_key='1';
            if($prd_item_type==='feature_products')
            {
                 $prd_key='feature_products';
                  $prd_type=0; //deal of the day
            }elseif($prd_item_type==='best_selling'){
                 $prd_key='selling_data';
                 $prd_type=1; //best selling
            }elseif($prd_item_type==='offer_going'){
                 $prd_key='offer_going_data';
                 $prd_type=4; //offer going on
            }
            
            
            else{   
				$prd_type=2; //also bought\
                $prd_key='bought_data';
            }
             
			$prd_img='';
				$prd_img=$this->site_base_path.'uploads/products/';
			$prd_setting = DB::select("SELECT * FROM product_setting");
		
	
			$record =Products::select(
								'products.id',
								'product_categories.cat_id',
								'product_attributes.unisex_type',
								'product_attributes.color_id',
								'product_attributes.size_id',
								'product_attributes.price as extra_price',
								'products.name',
								'products.price',
								'products.spcl_price',
								'products.qty',
								DB::raw("CONCAT('$prd_img',default_image) AS default_image")
							)
                    ->join('product_home_slider','products.id','=','product_home_slider.product_id')
                    ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                    ->join('product_categories','products.id','=','product_categories.product_id')
                    ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                    ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                    // ->where('product_attributes.qty','>',0)
                    ->where('products.status',1)
                    ->where('products.isdeleted',0)
                    ->where('product_home_slider.slider_type',$prd_type)
                    ->where('vendor_company_info.city','=',$sitecityname)
                    ->where('vendors.isdeleted',0)
                    ->where('vendors.status',1)
						->orderBy('product_home_slider.id','desc');
						
			if($prd_setting[0]->product_shows_type==1)
			{
				$record->groupBy("product_attributes.product_id");
			}
			
               $record= $record ->offset($fld_page_no)
								->limit(10)
								->get()
								->toarray();
							
						
			$allrecord =Products::select(
							'products.id'
						)
                        ->join('product_home_slider','products.id','=','product_home_slider.product_id')
                        ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                        ->join('product_categories','products.id','=','product_categories.product_id')
                        ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                        // ->where('product_attributes.qty','>',0)
                        ->where('products.status',1)
                        ->where('products.isdeleted',0)
                        ->where('product_home_slider.slider_type',$prd_type)
                        ->where('vendor_company_info.city','=',$sitecityname)
                        ->where('vendors.isdeleted',0)
                        ->where('vendors.status',1)
                        ->get()
						->toarray();
						
		
	
		$appnededRecord=array();
		foreach($record as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);   
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
				
				$extrs_price=0;
				if($row['color_id']!='' && $row['color_id']!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row['id'])
										->where('color_id',$row['color_id'])
								// 		->where('qty','>',0)
										->first();

					$extrs_price=@$color_price->price;
				}
				
				$spcl=0;
                    $spcl=$row['price']+$extrs_price;
                    if($row['spcl_price']!=''){
                        $spcl=$row['spcl_price']+$extrs_price;
                    }
                    
                /////$row['default_image']=$prd_img;
                    $row['price']=$row['price']+$extrs_price;
                    $row['spcl_price']=$spcl+$extrs_price;
                //$row['fld_total_rating']=Products::productRatings($row['id']);
                //$row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
                array_push($appnededRecord,$row);
		}
		$message="Product Listing";
		$api_key=$prd_key;
		
		echo $this->msg_info($message,$appnededRecord,$page+1,$api_key,$allrecord);
	}
	
	 public function recentlyViewd(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
		
                $sitecityname = 'NA';
                $sitecityId = '0';
        if(isset($input['city_id'])){
            $sitecityId= $input['city_id'];
            $sitecityname= $input['city_name'];
        }
        
	
		
		$products_id=explode(',',$input['fld_product_id']); 
		$fld_page_no=@$input['fld_page_no'];
        $page=$fld_page_no;
            	
		if($page!=0){
		 $fld_page_no=$fld_page_no*10;
		}
				$prd_img=$this->site_base_path.'uploads/products/';
			$prd_setting = DB::select("SELECT * FROM product_setting");
		
			$record =Products::select(
							'products.id',
							'product_categories.cat_id',
							DB::raw(' "" as unisex_type'),
							
							'product_attributes.color_id',
							'product_attributes.size_id',
							'product_attributes.price as extra_price',
							'products.name',
							'products.price',
							'products.spcl_price',
							DB::raw("CONCAT('$prd_img',default_image) AS default_image")
						)
                    ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                    ->join('product_categories','products.id','=','product_categories.product_id')
                    ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                    ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                    ->where('vendor_company_info.city','=',$sitecityname)
                    ->where('vendors.isdeleted',0)
                    ->where('vendors.status',1)
                    // ->where('product_attributes.qty','>',0)
                    // ->where('products.isexisting',0)
                    ->where('products.isdeleted',0)
                    ->where('products.status',1);
			            
            if($prd_setting[0]->product_shows_type==1)
			{
				$record=$record->groupBy("product_attributes.product_id");
			}
			
		if(count($products_id)==1)
	    {
	         $record=$record->where('products.id',$products_id);
		}elseif(count($products_id)>1){
			$record=$record->whereIn('products.id',$products_id);
		}
            $record=$record;
            $allrecord=$record;
		 $record=$record->offset($fld_page_no)
                        ->limit(10)
                        ->get()
						->toarray();
						
			$allrecord =$allrecord
                        ->get()
						->toarray();
	
						
		$appnededRecord=array();
		foreach($record as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);   
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
				
				$extrs_price=0;
				if($row['color_id']!='' && $row['color_id']!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row['id'])
										->where('color_id',$row['color_id'])
								// 		->where('qty','>',0)
										->first();

					$extrs_price=$color_price->price;
				}
				
				$spcl=0;
                    $spcl=$row['price']+$extrs_price;
                    if($row['spcl_price']!=''){
                        $spcl=$row['spcl_price']+$extrs_price;
                    }
				/////$row['default_image']=$prd_img;
				$row['price']=$row['price']+$extrs_price;
				$row['spcl_price']=$spcl;
				
				// 	$row['price']=0;
				// $row['spcl_price']=0;
			//$row['fld_total_rating']=Products::productRatings($row['id']);
		    //$row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
		    array_push($appnededRecord,$row);
		}
		$message="Recent view Product Listing";
		$api_key='Recent_view_Product_data';
		
		echo $this->msg_info34($message,$appnededRecord,0,$api_key,$allrecord);
      }
	public function msg_info34(
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
			$data_list=[];
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
	
	public function msg1_info(
                    $msg,
                    $data,
                    $page_no,
                    $api_key,
					$grid_view,
                    $Allrecord=array(),
                    $extra_keys='',
                    $extra_data=array()
	          )
	{
	    if($data){
			$status=true;
			$statusCode=201;
			$message=$msg;
			$grid_list_view=$grid_view;
			$data_list=$data;
			$return_page=($page_no+1);
		}else{
			$status=false;
			$statusCode=404;
				$message='No '.$msg.' Found';
			if($api_key=='product_data'){
			    	$message='No more products';
			}
		
			$grid_list_view=$grid_view;
			$data_list=[];
			$return_page=$page_no;
		}
		if($extra_keys!=''){
				$res=array(
						"status"=>$status,
						"statusCode"=>$statusCode,
						"fld_total_page"=>ceil(sizeof($Allrecord)/10)-1,
						$extra_keys=>$extra_data,
						"message"=>$message,
						"grid_list_view"=>$grid_list_view,
						"next_page"=>$return_page,
						$api_key=>$data_list
					); 
		} else{
		    	$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					 "fld_total_page"=>ceil(sizeof($Allrecord)/10),
					"message"=>$message,
					"grid_list_view"=>$grid_list_view,
					"next_page"=>$return_page,
					$api_key=>$data_list
				);
		}
	
		
		return json_encode($res);
	}
	
	public function msg2_info(
                    $msg,
                    $data,
                    $page_no,
                    $api_key,
                    $count
	          )
	{
	    if($data){
			$status=true;
			$statusCode=201;
			$message=$msg;
			$count=$count;
			$data_list=$data;
			
		}else{
			$status=false;
			$statusCode=404;
			$message='No '.$msg.' Found';
			$data_list=null;
			$count=$count;
			$return_page=$page_no;
		}
		
		
		    	$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"total_seller_count"=>$count,
					$api_key=>$data_list
				);
		
	
		
		return json_encode($res);
	}
	

}