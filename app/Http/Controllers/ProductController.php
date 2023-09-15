<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Products;
use App\ProductAttributes;
use App\ProductSlider;
use App\Category;
use App\Colors;
use App\Vendor;
use URL;
use App\Sizes;
use App\Brands;
use App\Filters;
use App\Sizechart;
use App\ProductExtraDescription;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\MsgHelper;
use App\Helpers\CommonHelper;
use App\Helpers\HomeProductSliderHelper;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    	public $site_base_path='http://aptechbangalore.com/test/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    /* public function index()
    {
        return view('home');
    }
    */
    public function customerQyeryforCustomized(Request $request){
         $request->validate([
                        'name' => 'required|max:255',
                        'email' => 'required|max:255|email',
                        'image' => 'required|mimes:jpeg,bmp,png,jpg',
                        'mobile' => 'required|max:10',
                        'message' => 'required|max:10000'
                
            ]);
            	$input=$request->all();
            
            $insert_data=array(
                "product_id"=>$request->id,
                "name"=>$input['name'],
                "email"=>$input['email'],
                "mobile"=>$input['mobile'],
                "message"=>$input['message'],
            );
            
            if ($request->hasFile('image')) {
			$banner_image = $request->file('image');
			$destinationPath2 =  Config::get('constants.uploads.customerCustomizedimage');
			$file_name2=$banner_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,0,0,false);
			  if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
			  $insert_data['image']=$file_name2;
		}
		
		$res=DB::table('customized_customer_query')->insert($insert_data);
   if($res){
	   MsgHelper::save_session_message('success','We have Received your customization',$request);
   } else{
      MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
   }
  
   return Redirect::back();
    }
    	public function customized(){
		
		 $customized_products=DB::table('customized_products')->paginate(52);
		 
	
		    
	 return view('fronted.mod_pages.customized',['products'=>$customized_products]); 
	}
	public function customizedform(Request $request){
		$product_id=base64_decode($request->product_id);
		$product_info=DB::table('customized_products')->where('id',$product_id)->first();
		
		  return view('fronted.mod_pages.customizedform',['product_id'=>$product_id,'product_info'=>$product_info]);
	}
    public function testtttt(){
        	$filters_data =	DB::table('filters')->select(
        	    \DB::raw("distinct(filters.id) as  filters_id ")
        	    )
                ->join('filter_values', 'filter_values.filter_id', '=', 'filters.id')
                 ->join('filters_category', 'filters_category.filter_id', '=', 'filters.id')
                ->where('filters_category.cat_id','=',250)
                ->where('filters.isdeleted','=',0)
                ->where('filters.status','=',1)
                ->get()->toArray();
                
                
                	$filters_data2 =	Products::select(\DB::raw("distinct(product_filters.filters_id) as  filters_id "),\DB::raw("group_concat(product_filters.filters_input_value) as  filters_input_value "))
                ->join('product_filters', 'products.id', '=', 'product_filters.product_id')
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_categories.cat_id','=',250)
                ->where('products.isdeleted','=',0)
                ->where('products.status','=',1)
                ->groupBy('product_filters.filters_id')
                ->get()->toArray();
                
                echo "<pre>";
                print_r($filters_data);
                 print_r($filters_data2);
    }
    public function brandSelection(Request $request){
       $input=$request->all(); 
       
       $prd_id=array();
            $user_compare_product=DB::table('compare_product')
            ->select('product_id')
            ->where('user_ip','=',$request->ip())
            ->get();
            foreach($user_compare_product as $prd){
                array_push($prd_id,$prd->product_id);
            }
            
        $prds=DB::table('products')
        ->select('id','name')
        ->where('isdeleted',0)
        ->where('status',1)
        ->where('products.isblocked','=',0)
        ->whereNotIn('id',$prd_id)
        ->where('product_brand',$input['brand'])
        ->get();
        $html='';
        foreach($prds as $prd){
            $html.='<option value="'.$prd->id.'">'.$prd->name.'</option>';
        }
        echo json_encode(array(
            "html"=>$html
            ));
        
    }
    public function setColoredImages(Request $request){
        $input=$request->all();
		$productdetails=Products::where('id',$input['prd_id'])->first();
		$productskuid=DB::table('product_attributes')->where('product_id',$input['prd_id'])->where('color_id',$input['color_id'])->first();
		$imagespathfolder='uploads/products/'.$productdetails->vendor_id.'/'.$productskuid->sku;
        $html=$html1='';
        $thumbnails=array(
                    "main_image"=>'',
                    "thumbnails"=>'',
                    "isColorImagesSet"=>0
                 );
        $images=DB::table('product_configuration_images')
				->select(
						DB::raw("CONCAT('".$this->site_base_path.'/'.$imagespathfolder."/',product_configuration_images.product_config_image) AS image")
					)
				->where('product_id',$input['prd_id'])
                ->where('color_id',$input['color_id'])
                ->get();
                
		$images_data=DB::table('product_configuration_images')
			->select(
					DB::raw("CONCAT('".$this->site_base_path.'/'.$imagespathfolder."/',product_configuration_images.product_config_image) AS image")
				)
			->where('product_id',$input['prd_id'])
			->where('color_id',$input['color_id'])
			->first();


 				
					$html.='';
					$_isactive=0;
					//$_isactive = 1;
					$fancyBox_image="";
               foreach($images as $image){
                   
					$pp=($_isactive == 0)?'active':'';
						$class=($_isactive==0)?'fancyZoom':'dnoneD'; 
					//$html.='<div class="product-dec-small '.$pp.'"><img src="'.$image->image.'" alt=""></div>';
					$html.='<li><a href="javascript:void(0)" data-image="'.$image->image.'" data-zoom-image="'.$image->image.'" class="'.$pp.'"> <img  src="'.$image->image.'" class="customThumnnail" data-large-image="'.$image->image.'" /></a></li>';
					
				// 	if($_isactive==0){
					   $fancyBox_image.='	<a class="'.$class.'" data-fancybox="gallery" href="'.$image->image.'">
												 <img id="img_zoom" src="'.$image->image.'" data-zoom-image='.$image->image.'" alt=""/>
											</a>';
				// 	}
                    
                    $_isactive++;
					 }
				
                
                $html1.='';
				
                foreach($images as $image){
                   
					//$html1.='<div class="easyzoom-style"><div class="easyzoom easyzoom--overlay"><a href="'.$image->image.'"><img class="img-responsive" src="'.$image->image.'" alt=""></a></div><a class="easyzoom-pop-up img-popup" href="'.$image->image.'"><i class="fa fa-arrows-alt"></i></a></div>';
               $html1.='<div class="easyzoom-style"><div class="easyzoom easyzoom--overlay"><a href="'.$image->image.'"><img class="img-responsive" src="'.$image->image.'" alt=""></a></div><a class="easyzoom-pop-up img-popup" href="'.$image->image.'"><i class="fa fa-arrows-alt"></i></a></div>';   
                }
			
				
                if(sizeof($images)>0){
                     $thumbnails=array(
                    "main_image"=>$images_data->image,
                    "thumbnails"=>$html,
					"large_thumbnails"=>$html1,
					"fancyBox_image"=>$fancyBox_image,
                    "size"=>sizeof($images)
                 );
                }
       echo json_encode($thumbnails);
    }
     public function brands_product(Request $request){
                $brand=str_replace('-',' ',$request->brands);
                $brand_id=Brands::select('id')->where('name',$brand)->first();
         	$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
	   	    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
	   ->where('products.isexisting',0)
	    ->where('products.isdeleted',0)
	    	->where('products.isblocked','=',0)
	    ->where('products.product_brand','=',$brand_id->id)
	   ->where('products.status','=',1)->get()->first();
	   
	   
	   $min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
	   $max_price=!empty($max_min->max_price)?$max_min->max_price:'1';
	   
         
		$input='';
		$data = Products::select('products.*',"product_attributes.color_id","product_attributes.price as extra_price")
		->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
		->join('brands', 'products.product_brand', '=', 'brands.id')
		->where('brands.name','=',$brand)
		->where('products.isexisting',0)
	    ->where('products.isdeleted',0)
		->where('products.status','=',1)->paginate(9);
	   //->toSql();
	  
		 return view('fronted.mod_product.product-listing',["products"=>$data,'cat_id'=>0,'min_price'=>$min_price,'max_price'=>$max_price]);
        
     }
     
     public function product_review(Request $request){
         $prd_id=base64_decode($request->id);
         $prd_detail = Products::select('products.*')
							   ->where('products.id','=',$prd_id)
							   ->first();
        $ratings=Products::getAllReview($prd_id,9);
  return view('fronted.mod_review.reviews-comment',["ratings"=>$ratings,'prd_detail'=>$prd_detail]);
     }
    
    
    public function quickView(Request $request){
		$input=$request->all();
	   $prd_detail = Products::select('products.*')
       ->where('products.id','=',$input['prd_id'])
       ->first();
		$response=array(
				 "product"=>view("fronted.mod_product.sub_views.quick_view",array('prd_detail'=>$prd_detail) )->render()
				);
				
				echo json_encode($response);
	}
	
    public function remove_cart_item(Request $request){
		$input=$request->all();
			app(\App\Http\Controllers\CookieController::class)->deleteCartItem($input); 

	$response=array(
				"error"=>false,
				);
			
				echo json_encode($response);					
	}
	 public function getAttPrice(Request $request){
		$input=$request->all();
		$prd_data=Products::productDetails(@$input['prd_id']);
		$old=$prd_data->price;
		$new=$prd_data->price;
		$qty=0;
		if($prd_data->spcl_price!='' && $prd_data->spcl_price!=0){
		    	$new=$prd_data->spcl_price;
		}
	
		
		if($input['color_id']==0 && $input['size_id']!=0){
		    
    	    $attr_data=DB::table('product_attributes')
    	    ->where('product_id',$input['prd_id'])
    	     ->where('size_id',$input['size_id'])
    	    ->first();
		    
	        $old+=$attr_data->price;
            $new+=$attr_data->price;
            $qty=$attr_data->qty;
		}
		if($input['color_id']!=0 && $input['size_id']==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('color_id',$input['color_id'])
		    ->first();
		    $old+=$attr_data->price;
		    $new+=$attr_data->price;
		    $qty=$attr_data->qty;
		}
		if($input['color_id']!=0 && $input['size_id']!=0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('color_id',$input['color_id'])
		     ->where('size_id',$input['size_id'])
		    ->first();
		    
		    $old+=$attr_data->price;
		    $new+=$attr_data->price;
		    $qty=$attr_data->qty;
		}
			$saveAmount = $old - $new; 

			$response=array(
                "old_price"=>$old,
                "new_price"=>$new,
				"saveAmount" => ($saveAmount>0)?$saveAmount:0,
                "qty"=>$qty,
                "percent"=>Products::offerPercentage($old,$new)
				);				
				echo json_encode($response);
	 }
	 
	 public function sizeStock(Request $request){
		$input=$request->all();
		$prd_data=Products::productDetails($input['prd_id']);
		$qty=0;
	
		if($input['prd_type']==3){
		    
		    if($input['color_id']==0){
		        $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('size_id',$input['size_id'])
		    ->first();
		    	$qty=$attr_data->qty; 
		    } else{
                    $attr_data=DB::table('product_attributes')
                    ->where('product_id',$input['prd_id'])
                    ->where('size_id',$input['size_id'])
                     ->where('color_id',$input['color_id'])
                    ->first();
                    $qty=$attr_data->qty;
		    }
		   
		}
		
			if($input['prd_type']==2){
		        if($input['color_id']==0){
		        $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('size_id',$input['size_id'])
		    ->first();
		    	$qty=$attr_data->qty; 
		    } else{
                    $attr_data=DB::table('product_attributes')
                    ->where('product_id',$input['prd_id'])
                    ->where('size_id',$input['size_id'])
                     ->where('color_id',$input['color_id'])
                    ->first();
                    $qty=$attr_data->qty;
		    }
		}
			$response=array(
                  "qty"=>$qty
				);
				
				echo json_encode($response);
	 }
    public function get_attr_dependend(Request $request){
		$input=$request->all();
		
		$obj=new Products();
		$prd_data=Products::productDetails($input['prd_id']);
		$data=$obj->getProductsAttributes($input['attr_name'],$input['size_id'],$input['prd_id']);
	     	$html='';
            $whtml='';
          
            $size_name='';
            if(sizeof($data)>0){
                	$size_name=$obj->getAttrName('Sizes',$data[0]['size_id']);
            }
		foreach($data as $row){
			if($input['attr_name']=='Sizes'){
				$appendto='sizes_html';
				$name=$obj->getAttrName('Sizes',$row['size_id']);
				
			
    				
				if($row['unisex_type']==1 || $row['unisex_type']=='' || $row['unisex_type']==0){
                    $html.= '<li>
                    <label class="size1 sizeClass"  size_id="'.$row['size_id'].'" prd_id='.$input['prd_id'].' size_name="'.$name.'">
                    '.$name.'</label>
                    </li>';
                                        
    			
				}
				
				// if($row['unisex_type']==2){
    // 				$whtml.='<span title="small"> <a href="javascript:void(0)" class="badge badge-danger   wsizeClass" 
    // 				prd_id='.$input['prd_id'].'
    // 				 w_size_id="'.$row['size_id'].'"
    // 				>'.$name.'</a></span>';
				// }
				
			} else{
                $colorwise_images=DB::table('product_configuration_images')
                ->where('product_id',$input['prd_id'])
                ->where('color_id',$row['color_id'])
                ->first();
				$appendto='colors_html';
				$color_data=Products::getcolorNameAndCode('Colors',$row['color_id']);
				  if($colorwise_images){
				      	$url=URL::to("/uploads/products/240-180").'/'.$colorwise_images->product_config_image;
				  } else{
				      	$url=URL::to("/uploads/color").'/'.$color_data->color_image;
				  }
			
				$name=$color_data->name;
				$html.='<span class="colorClass" 
				color_id="'.$row['color_id'].'" 
				prd_id='.$input['prd_id'].' 
				prd_type='.$prd_data->product_type.'
				 style="background-color:'.$color_data->color_code.' "
				title="'.$color_data->name.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			}
		}
	
		$color_id=(sizeof($data)==1)?$data[0]['size_id']:0;
			    $appendto='colors_html';
			if($input['attr_name']=='Sizes'){
			    $appendto='sizes_html';
			} 
		$response=array(
				"html"=>$html,
				"whtml"=>$whtml,
				'name'=>$size_name,
				"color_id"=>$color_id,
				"print_to"=>$appendto
				);
				// print_r($response);
				// die();
				echo json_encode($response);
	}
	
		public function RemovecompareProduct(Request $request){
		$input=$request->all();
		
		$res=DB::table('compare_product')
                        ->where('user_ip','=',$request->ip())
                        ->where('product_id','=',$input['prd_id'])
                        ->delete();
                if($res){
                $prd_id=array();
                $user_compare_product=DB::table('compare_product')
                ->select('product_id')
                ->where('user_ip','=',$request->ip())
                ->get();
                    foreach($user_compare_product as $prd){
                    array_push($prd_id,$prd->product_id);
                    }
            
            
                    $products=Products::
                    whereIn('id',$prd_id)->get();
                    $response=array(
                    "msg"=>'Product Removed',
                     "compareProductResponse"=>view("fronted.mod_compare.ajax.backResponse",array("products"=>$products))->render()
                    );
               
                } else{
                    $prd_id=array();
                    $user_compare_product=DB::table('compare_product')
                    ->select('product_id')
                    ->where('user_ip','=',$request->ip())
                    ->get();
            foreach($user_compare_product as $prd){
                array_push($prd_id,$prd->product_id);
            }
            
            
                    $products=Products::
                    whereIn('id',$prd_id)->get();
		            
                    $response=array(
                    "msg"=>'Something went wrong',
                    "compareProductResponse"=>view("fronted.mod_compare.ajax.backResponse",array("products"=>$products))->render()
                    );
                }
                
               
				echo json_encode($response);
               
		}
	public function addProductToCompare(Request $request){
		$input=$request->all();
		$cats=Products::productsFirstCatData($input['prd_id']);
		$cat_id=0;
		if($cats){
		  $cat_id =$cats->id;
		}
		
		$user_compare_product=DB::table('compare_product')
		->select('id')
		->where('user_ip','=',$request->ip())
		->first();
		if($user_compare_product){
		    
		    
                        $total_product=DB::table('compare_product')
                        ->select('id')
                        ->where('user_ip','=',$request->ip())
                        ->get();
                        if(sizeof($total_product)==3){
                                $response=array(
                                "msg"=>'Compare list full.'
                                );
                    	    echo json_encode($response);
                    	    die();
                        }
		
		     $same_cat_product=DB::table('compare_product')
            ->select('id')
            ->where('cat_id','=',$cat_id)
            ->where('user_ip','=',$request->ip())
            ->first();
            
            if($same_cat_product){
                $is_exist=DB::table('compare_product')
                ->select('id')
                ->where('product_id','=',$input['prd_id'])
                ->where('cat_id','=',$cat_id)
                ->where('user_ip','=',$request->ip())
                ->first();
        
	
		if($is_exist){
		    $response=array(
                    "msg"=>'Product already in compare list'
                    );
	
		} else{
		    	$inputs=array(
                'product_id' =>$input['prd_id'],
                'cat_id' =>$cat_id,
                'user_ip' =>$request->ip()
			);
			    DB::table('compare_product')->insert($inputs);
			    $response=array(
                    "msg"=>'Product added for compare'
                    );
		}
                
            } else{
                    $response=array(
                    "msg"=>'Compare Product only  of same category'
                    );
            }
		} else{
           	$inputs=array(
                'product_id' =>$input['prd_id'],
                'cat_id' =>$cat_id,
                'user_ip' =>$request->ip()
			);
				DB::table('compare_product')->insert($inputs);
				
				$response=array(
                    "msg"=>'Product added for compare'
                    );
		}
		
		echo json_encode($response);
	}
	public function cookieSetReset(Request $request){
            setcookie("recent_views_products", "", time() - 3600);
            setcookie("recent_views_products",null, 0);
            setcookie("productsInCart",null, 0);
            setcookie("test",null, 0);
            $minutes=(86400 * 30);
            setcookie('sitecity', $request->city, time() + ($minutes));
               
	}
     public function add_to_cart(Request $request){
            
            
           
		$input=$request->all();
	   
		$sitecityname='NA';
	
		 if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	    }
	 
	    
 $stock =Products::select('products.qty','products.moq')
                ->where('products.id',$input['prd_id'])
                /*->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                ->where('vendor_company_info.city','=',$sitecityname)*/
                 ->where('products.spcl_price','>',0)
                /*->where('vendors.isdeleted',0)
                ->where('vendors.status',1)*/
                ->where('products.isdeleted',0)
                ->where('products.status',1)
                ->first();
      
                
               /* if(!$stock){
                      	$response=array(
                      	    "msg"=>"Product not available in your city",
                            "status"=>false,
                            "method"=>4
				);
					echo json_encode($response);
					die();
                }*/
            
                  if($stock){
                           if (!array_key_exists("w_size_id",$input))
                    {
                     $input['w_size_id']=0;
                    }
	
		$prd_in_cart=array();
		// if($stock->moq>0)

		// {
           
		$prd_in_cart=array(
                'product_id'=>$input['prd_id'],
                'size_id'=>$input['size_id'],
                'color_id'=>$input['color_id'],
                'qty'=>$input['qty'],
				/*'qty'=>$stock->moq,*/
                'w_size_id'=>$input['w_size_id']
		          
		    );
		// }
	
      
       if($input['prd_type']==1){
           
           	$stock =Products::select('qty')
           ->where('id',$input['prd_id'])
           ->first();
      }
      
      if($input['prd_type']==2){
          	$stock = ProductAttributes::select('qty')
        ->where('size_id','=',$input['size_id'])
         ->where('color_id','=',$input['color_id'])
        ->where('product_id','=',$input['prd_id'])
        ->first();
      }
      
      if($input['prd_type']==3){
           	$stock = ProductAttributes::select('qty')
        ->where('size_id','=',$input['size_id'])
         ->where('color_id','=',$input['color_id'])
        ->where('product_id','=',$input['prd_id'])
        ->first();
      }
    
       

         $quantity = $stock ? $stock->qty : 0;
     
         if($quantity >= $input['qty']) {
             
            $return=app(\App\Http\Controllers\CookieController::class)->setcustomCartCookie($prd_in_cart); 
          	// dd($prd_in_cart);
                $response=array(
                "status"=>true,
                "method"=>$return
                );
		
         }
         else{
		
	$response=array(
				"status"=>false,
				"method"=>3
				);
	}
		echo json_encode($response);
                      
                  } else{
                      	$response=array(
                      	    "msg"=>"Product Not available",
                            "status"=>false,
                            "method"=>4
				);
					echo json_encode($response);
                  }
		
		
               
	}

	public function otherSeller(Request $request){
	      $sitecityname = 'NA';
	    if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	    }
	    $id=$request->prd_id;
	      $data = Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price",'vendors.username as seller_name')
							->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
	                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            ->join('vendor_existing_product', 'products.id', '=', 'vendor_existing_product.product_id')
                                    //  ->where('products.isexisting',1)
                            ->Where(function($query) use ($id){
                                        $query->orWhere('vendor_existing_product.master_product_id',$id);
                                        $query->orWhere('vendor_existing_product.product_id',$id);
                                
                                })
                            ->where('products.isdeleted',0)
                            ->where('products.status','=',1);
                            
	      $data = $data->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname);
	 
       
       $data= $data->groupBy("product_attributes.product_id");
        $datas=$data ->orderBy("products.id",'desc')->paginate(36);
	    $response = array(
            "products" => view("fronted.mod_product.seller-product", array(
                'datas' => $datas
            ))->render()
        );
        echo json_encode($response);
	}
    public function productDetails(Request $request)
    {
       
         $decodeInput=explode("~~~",base64_decode($request->id));
         
		$id=$decodeInput[0];
	
         $sitecityname = 'NA';
	    if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	    }
	  
	  /* $prd_detail = Products::select('products.*','vendors.username as seller_name','brands.name as brand_name')
	                            ->join('vendors','vendors.id','products.vendor_id')
	                             ->join('brands','brands.id','products.product_brand')
							   ->where('products.id','=',$id)
							   ->first();*/
							   
							    $prd_detail = Products::select('products.*','product_categories.cat_id','vendors.username as seller_name','vendor_company_info.name as companyName')
	                            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->join('vendors','vendors.id','products.vendor_id')
								->join('vendor_company_info','vendors.id','vendor_company_info.vendor_id')
	                           //  ->join('brands','brands.id','products.product_brand')
							   ->where('products.id','=',$id)
							   ->first();
							   $extra_price=0;
        	   if($prd_detail){
        	       	 $sizes=Products::getProductsAttributes2('Sizes',0,$id);
        	     
        	       	 if(sizeof($sizes)>0){
        	       	           	
        	       	                 $price=DB::table('product_attributes')
        	       	                       ->where('product_id',$id)
        	       	                        ->where('size_id',$sizes[0]['size_id'])
        	       	                       ->first();
        	       	                         
        	       	                       if($price){
        	       	                           $extra_price=$price->price;
        	       	                       }
        	       	 }
        	          $orher_offers=DB::table('vendor_existing_product')
                                    ->join('vendor_company_info', 'vendor_existing_product.vendor_id', '=', 'vendor_company_info.vendor_id')
                                      ->join('vendors', 'vendor_existing_product.vendor_id', '=', 'vendors.id')
                                    ->where('vendor_company_info.city','=',$sitecityname)
                                    ->where('vendors.isdeleted',0)
                                    ->where('vendors.status',1)
                                        ->Where(function($query) use ($id){
                                                $query->orWhere('vendor_existing_product.master_product_id',$id);
                                                $query->orWhere('vendor_existing_product.product_id',$id);
                                        
                                        })
                                   ->count();
        	       $prd_detail->other_seller=$orher_offers;
        	       
        	   } else{
        	        return redirect()->route('index');
        	   }
           
		app(\App\Http\Controllers\CookieController::class)->setCookie($id); 
		
		//$ss=app->call('App\Http\Controllers\CookieController@getCookie');
		//$ss=app('App\Http\Controllers\CookieController@getCookie');
		
		//$controllerq = new CookieController;
		//$ss=$controllerq->setCookie($id);
  
		//echo Cookie::queue('name', $id, 60);
		// $countryData = DB::table('countries')->get();
		$countryData =[];

		/**
		 * Product Size Chart based on vendors category
		 */
			   $AssignedCategory = DB::table('product_categories')->where('product_id', $id)->orderBy('id','DESC')->first(); 
 

			   $cat_id = $AssignedCategory->cat_id;
			   $vendorID = $prd_detail->vendor_id;
			   $sizeChartData = Sizechart::where(['vendor_id'=>$vendorID, 'category_id'=> $cat_id])->first(); 
			   $sizeChartURL = '';
			 
			   if(!empty($sizeChartData)){
				$sizeChartURL = asset('uploads/category/size_chart/'.$sizeChartData->sizechart);
			   }
			 
               return view('fronted.mod_product.product-detail',['prd_detail'=>$prd_detail,"decodeInput"=>$decodeInput,'extra_price'=>$extra_price,'countryData'=>$countryData,'sizeChartURL'=> $sizeChartURL]);
    } 
	
	public function SearchProduct(Request $request)
    {
        //try{
           
            $sortby=($request->sortby)?$request->sortby:4;
       
          $page_no=9;
          
          $inputs=$request->all();
                $min_price1='';
                $max_price1='';
				
				if (array_key_exists("page",$inputs))
				{
					$page=$inputs['page'];
				}

				
			
        $sitecityname = '';
	    if(isset($_COOKIE['sitecity'])){
	        $sitecityname = DB::table('cities')->select('name')->where('id',$_COOKIE['sitecity'])->where(['isdeleted'=>0,'status'=>1])->first();
	        $sitecityname = $sitecityname->name;
	    }
	    
		$input= $request->search;
		
		$uc=explode(" in ",$input);
		
		if(@$uc[1]!=''){
			$search=$uc[1];
			$type='cat';
		}elseif(@$uc[0]!=''){
			$search=$uc[0];
		}
		
		//$cat_id=base64_decode($request->cat_id);
		if(!empty($search))
		{
			$categoryid=DB::table('categories')->Where('name',$search)->first();
			if(empty($categoryid->id))
			{ 
				$categoryid=DB::table('categories')->Where('name','LIKE', '%' . $search . '%')->first();

			}
			session()->put('categoryid',$categoryid->id);
       		 session()->save();
		}

		if(!empty($categoryid->id))
		{
			$cat_id=$categoryid->id;
		}else{
			$datasession = session()->all();
			$cat_id=$datasession['categoryid'];
		}
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
						->leftjoin('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->leftjoin('product_categories','products.id','=','product_categories.product_id')
						->leftjoin('categories','product_categories.product_id','=','products.id')
                        // ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->leftjoin('vendors', 'products.vendor_id', '=', 'vendors.id')
						->where('product_categories.cat_id','=',$cat_id)
						->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status','=',1)
                        ->where('products.isblocked','=',0);
                      /* ->where('vendor_company_info.city','=',$sitecityname);*/
       
	//print_r($search);
					
	    if(!empty($cat_id))
	{
		/*Where(function($query) use ($search){
			$query->orWhere('products.name','LIKE', 
			'%' . $search . '%');
			$query->orWhere('products.meta_title','LIKE', '%' . $search . '%');
			$query->orWhere('products.meta_keyword','LIKE', '%' . $search . '%');
		   // $query->orWhere('categories.name','LIKE', '%' . $search . '%');
		   $query->orWhere('products.sku','LIKE', '%' . $search . '%');
		   $query->orWhere('product_attributes.sku','LIKE', '%' . $search . '%');
		   $query->orWhere('categories.name','=', $search);

		})*/
		$max_min =  $max_min->where('product_categories.cat_id','=',$cat_id)->get()->first();
		$min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
		$max_price= !empty($max_min->max_price)?$max_min->max_price:'1';

		if (array_key_exists("min_price",$inputs))
					{
						$min_price1=$inputs['min_price'];
					}
					else
					{
						$max_price1=$min_price;
					}

			if (array_key_exists("max_price",$inputs))
			{
				$max_price1=$inputs['max_price'];
			}
			else
			{
				$max_price1=$max_price;
			}
	}else{
		
		$max_min =  $max_min->get()->first();
		$min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
		$max_price= !empty($max_min->max_price)?$max_min->max_price:'1';

		if (array_key_exists("min_price",$inputs))
					{
						$min_price1=$inputs['min_price'];
					}
					else
					{
						$max_price1=$min_price;
					}

			if (array_key_exists("max_price",$inputs))
			{
				$max_price1=$inputs['max_price'];
			}
			else
			{
				$max_price1=$max_price;
			}
	}
			
			
		
	
			
			$data =Products::select('products.*',"product_attributes.color_id","product_attributes.price as extra_price")
						->leftjoin('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				 		->leftjoin('product_categories','products.id','=','product_categories.product_id')
				 		->leftjoin('categories','product_categories.product_id','=','products.id')
				 		// ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->leftjoin('vendors', 'products.vendor_id', '=', 'vendors.id')
						->where('vendors.isdeleted',0)
                        ->where('vendors.status',1);
			
			
						      
						
	    //   $data = $data->where('vendor_company_info.city','=',$sitecityname)
        //                     ->where('vendors.isdeleted',0)
        //                     ->where('vendors.status',1);
	   
	    
	    
	    
			if($prd_setting[0]->product_shows_type==1)
			{
				$data =$data->groupBy("product_attributes.product_id");
			}
				if(!empty($cat_id))
				{
					/*->Where(function($query) use ($search){
							$query->Where('products.name','LIKE', '%' . $search . '%');
							 $query->orWhere('products.meta_title','LIKE', '%' . $search . '%');
	                         $query->orWhere('products.meta_keyword','LIKE', '%' . $search . '%');
							 //$query->orWhere('categories.name','LIKE', '%' . $search . '%');
							 //$query->orWhere('categories.name','=', $search);
							$query->orWhere('products.sku','LIKE', '%' . $search . '%');
							$query->orWhere('product_attributes.sku','LIKE', '%' . $search . '%');
							$query->orWhere('categories.name','=', $search);
							 
						 })*/
					$data =$data
                         ->where('products.isdeleted',0) 
                        // ->where('products.isexisting',0)

                        ->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->where('product_categories.cat_id','=',$cat_id)
						
						->groupBy('product_categories.product_id');
				}else{
					$data =$data
                         ->where('products.isdeleted',0) 
                        // ->where('products.isexisting',0)

                        ->where('products.status','=',1)
						->where('products.isblocked','=',0)
						
						->groupBy('product_categories.product_id');	
				}
				
				
						 if($sortby!=''){
                        
                       
                    	if($sortby==1){
                    		$data=$data->orderBy("products.spcl_price","asc")->paginate($page_no);
                    	}elseif($sortby==2){
                    		$data=$data->orderBy("products.spcl_price","desc")->paginate($page_no);
                    	}elseif($sortby==3){
                    		$data=$data->orderBy("products.id","desc")->paginate($page_no);
                    	}
                    	elseif($sortby==4){
							$page_no1='1';
							$data=$data->orderBy("products.id","desc")->paginate($page_no);
                    		//$data=$data->orderBy("products.id","desc")->paginate($page_no);
							//dd($data);
                    	}
                    	
                    }else{
                    	$data=$data->orderBy("products.spcl_price",'asc')->paginate($page_no);
                    }
		
					  
		  

	if ($request->ajax()) {
				return view('fronted.mod_product.product-filter-listing',["products"=>$data,
                                                            'type_filter'=>'',
                                                            'sortby'=>$sortby,
                                                            'searchterm'=>$input,
                                                            'cat_id'=>0,
                                                            'min_price'=>$min_price,
                                                            'max_price'=>$max_price,
                                                            'min_price1'=>$min_price1,
                                                            'max_price1'=>$max_price1,
                                                            'size_array'=>'',
                                                            'color_array'=>'',
                                                            'discount_array'=>'',
                                                            'rating_array'=>'',
                                                            'filtervalue_array'=>'',
                                                            ]
				    )->render();
			}
	   
         return view('fronted.mod_product.product-listing',["products"=>$data,
                                                            'type_filter'=>'',
                                                            'sortby'=>$sortby,
                                                            'searchterm'=>$input,
                                                            'cat_id'=>0,
                                                            'min_price'=>$min_price,
                                                            'max_price'=>$max_price,
                                                            'min_price1'=>$min_price,
                                                            'max_price1'=>$max_price,
                                                            'size_array'=>'',
                                                            'color_array'=>'',
                                                            'discount_array'=>'',
                                                            'rating_array'=>'',
                                                            'filtervalue_array'=>'',
                                                            ]);
               
              
                // 'brands'=>$brands,
                
        /*}
        catch(\Exception $e)
		{
			return redirect()->to('/');;
		}*/
         
    } 


	public function AllProductView(Request $request){
		$type = $request->type;
		$prdID = $request->id;
		/**
		 * @type -> Related = view all related product
		 */
		if($type == 'Related'){
			/**
			 * View all Related Product
			 */
			
			$allProducts = HomeProductSliderHelper::getAllSimilarProduct($prdID);

		}else{
			/**
			 * View all Sliders Product
			 */
			$allProducts = HomeProductSliderHelper::getSliderAll($prdID);
		}

		return view('fronted.mod_product.product-listing-all',["products"=>$allProducts,]);
	}






   
   	public function cat_wise(Request $request)
	{
	   
	     $cust_id=0;
         if(Auth::guard('customer')->check()){
                $cust_id=auth()->guard('customer')->user()->id;
         }
        //  echo $cust_id;
        //  die();
	    $sitecityname = 'NA';
	    if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	    }

        
	    $sortby=@$request->sortby; 
	    $type_filter=@$request->type; 
	
		$cat_id=base64_decode($request->cat_id);
		$selectedPCat = Category::getparentCategory($cat_id);
	
		$id=@$request->id;
		$input='';
		$min_price1=@$request->min_price;
		$max_price1=@$request->max_price;
		
		$brands=array();
        $size_array=array();
        $color_array=array();
		$discount_array=array();
		$rating_array=array();
		$filtervalue_array=array();
		
		 if($id!=''){
    	    $custom_filter= explode(",",$id);
                    
    		foreach($custom_filter as $row){
                    if (strpos($row, 'brand') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($brands,$id);
                    }
                    if (strpos($row, 'color') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($color_array,$id);
                    }
                    
                    if (strpos($row, 'size') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($size_array,$id);
                    }
					
					if (strpos($row, 'discount') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($discount_array,$id);
                    }
					
					if (strpos($row, 'rating') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($rating_array,$id);
                    }
					
					if (strpos($row, 'filtervalue') !== false) {
					     $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($filtervalue_array,$id);
                    }
    		}
        }
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$min = Products::join('product_categories', 'products.id', '=', 'product_categories.product_id')
						->join('vendors', 'products.vendor_id', '=', 'vendors.id')
						->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
						->where('product_categories.cat_id','=',$cat_id)
						->where('products.isexisting',0)
						->where('products.isdeleted',0)
						->where('vendors.isdeleted',0)
						->where('vendors.status',1)
						->where('products.isblocked','=',0);
						
		/*if(!empty($sitecityname)){
		   
	       $min =  $min->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
	                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname);
	    }
	   */
                $min =  $min->where('products.status','=',1)->min('spcl_price');
             
                
                
                $max = Products::join('product_categories', 'products.id', '=', 'product_categories.product_id')
				->join('vendors', 'products.vendor_id', '=', 'vendors.id')
				->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                ->where('product_categories.cat_id','=',$cat_id)
                // ->where('products.isexisting',0)
                ->where('products.isdeleted',0)
				->where('vendors.isdeleted',0)
				->where('vendors.status',1)
                ->where('products.isblocked','=',0);
                /*	
                if(!empty($sitecityname)){
                
                $max =  $max->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                        ->where('vendors.isdeleted',0)
                        ->where('vendors.status',1)
                        ->where('vendor_company_info.city','=',$sitecityname);
                }
                */
                $max =  $max->where('products.status','=',1)->max('spcl_price');
    
	   
	 
	   
                $min_price=!empty($min)?$min:'1';
                $max_price= !empty($max)?$max:'1';

	  
	   
	   $data = Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
                    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                    ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
					->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
					//  ->join('brands', 'brands.id', '=', 'products.product_brand')
                    ->where('product_categories.cat_id','=',$cat_id)
                    //->whereNotIn('product_attributes.color_id',[0])
                    // ->where('products.isexisting',0)
                    ->where('products.isdeleted',0)
					->where('vendors.isdeleted',0)
					->where('vendors.status',1)
                    ->where('products.status','=',1);
	    /* ADDING city wise vendor's product */
	    
        /*
	    if(!empty($sitecityname)){
	      $data =  $data->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
	                       ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
	                    
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('vendor_company_info.city','=',$sitecityname);
	    }
        */
	    
	    	
                		 if(sizeof($color_array)>0){
                /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                $data= $data->whereIn('product_attributes.color_id',$color_array); 
                }
             
             if(sizeof($size_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
			 
			 if(sizeof($discount_array)>0){
                 //$data= $data->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">=",$discount_array);
             }
			 
			 if(sizeof($rating_array)>0){
                 $data= $data->join('product_rating', 'products.id', '=', 'product_rating.product_id');
				 $data= $data->whereIn(\DB::raw("round((product_rating.rating))"),$rating_array)->groupBy("product_rating.rating");
             }
			 
			
			 if(sizeof($filtervalue_array)>0){
			     $data= $data->join('product_filters', 'products.id', '=', 'product_filters.product_id');
				 $data= $data->whereIn('product_filters.filters_input_value',$filtervalue_array); 
             }
             if(sizeof($brands)>0){
                 $data= $data->whereIn('products.product_brand',$brands); 
             }
            
							 $data= $data->where(function($query) use ($min_price,$max_price,$type_filter,$min_price1,$max_price1){
							    if($type_filter!=''){
							         $query->where('products.spcl_price','>=',$min_price1);
                                    $query->where('products.spcl_price','<=',$max_price1);
                                    
                                               // $query->orwhereBetween('products.spcl_price', array($min_price1,$max_price1));
                                                //  $query->orwhereBetween('products.price', array($min_price1,$max_price1));
							    } else{
							          $query->where('products.spcl_price','>=',$min_price);
                                    $query->where('products.spcl_price','<=',$max_price);
							       	//$query->orwhereBetween('products.spcl_price', array($min_price,$max_price));
                                //   $query->orwhereBetween('products.price', array($min_price,$max_price));
							    }
							
								
								 });
								 
		if($prd_setting[0]->product_shows_type==1)
		{
			$data->groupBy("product_attributes.product_id");
		}
		if($sortby!=''){
			    
			   
				if($sortby==1){
					$data=$data->orderBy("products.spcl_price","asc");
				}elseif($sortby==2){
					$data=$data->orderBy("products.spcl_price","desc");
				}elseif($sortby==3){
					$data=$data->orderBy("products.id","desc");
				}
				elseif($sortby==4){
					$data=$data->orderBy("products.id","desc");
				}
				
			}else{
				$data=$data->orderBy("products.id",'desc');
			}
				
			$data=$data ->orderBy("products.spcl_price",'asc')->paginate(36);
			
        
	   
	    if ($request->ajax()) {
			return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$cat_id,'cat_name'=>'test'])->render();
		}
		
// 		 return $data;
		 return view('fronted.mod_product.product-listing',[
                "products"=>$data,
                'cat_id'=>$cat_id,
                'min_price'=>$min_price,
                'max_price'=>$max_price,
                'min_price1'=>$min_price1,
                'max_price1'=>$max_price1,
                'brands'=>$brands,
                'size_array'=>$size_array,
                'color_array'=>$color_array,
                'discount_array'=>$discount_array,
                'rating_array'=>$rating_array,
                'filtervalue_array'=>$filtervalue_array,
                'type_filter'=>$type_filter,
                'sortby'=>$sortby,
				'selectedPCat'=>$selectedPCat
		     ]);
		 
		
	
	}



	public function vendorProducts(Request $request){	

	   $vendorID = base64_decode($request->vendor_id);	
	   $sortby=@$request->sortby; 
	   $type_filter=@$request->type;    
	   $cat_id=base64_decode($request->cat_id);   
	   $id=@$request->id;
	   $input='';
	   $min_price1=@$request->min_price;
	   $max_price1=@$request->max_price;	   
	   $brands=array();
	   $size_array=array();
	   $color_array=array();
	   $discount_array=array();
	   $rating_array=array();
	   $filtervalue_array=array();
	  
	   


	   $vendorsdetail = DB::table('vendors')               
	  					->select('vendors.id',
						'vendor_company_info.logo',
						'vendor_company_info.name',
						'vendor_company_info.about_us',
						'vendor_company_info.city',
						'vendor_company_info.state',
						'vendor_company_info.vendor_id',
						'vendors.status',
						'vendors.isdeleted'
						)
						->join('vendor_company_info','vendor_company_info.vendor_id','vendors.id')
						->where([
							'vendors.status'=>'1',
							'vendors.id'=>$vendorID,
							'vendors.isdeleted'=>'0'
							])
						->first();
					

		$totalProduct = Products::select("products.id")
				   ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
				   ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				   ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
				   ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
				   ->where('products.isdeleted',0)
				   ->where('vendors.isdeleted',0)
				   ->where('vendors.status',1)
				   ->where('products.vendor_id',$vendorID)
				   ->where('products.status','=',1)
				   ->groupBy('products.id')
				   ->get();

		if($id!=''){
		   $custom_filter= explode(",",$id);
				   
		   foreach($custom_filter as $row){
				   if (strpos($row, 'brand') !== false) {
						   $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
						   array_push($brands,$id);
				   }
				   if (strpos($row, 'color') !== false) {
						   $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
						   array_push($color_array,$id);
				   }
				   
				   if (strpos($row, 'size') !== false) {
						   $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
						   array_push($size_array,$id);
				   }
				   
				   if (strpos($row, 'discount') !== false) {
						   $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
						   array_push($discount_array,$id);
				   }
				   
				   if (strpos($row, 'rating') !== false) {
						   $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
						   array_push($rating_array,$id);
				   }
				   
				   if (strpos($row, 'filtervalue') !== false) {
						$id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
						   array_push($filtervalue_array,$id);
				   }
		   }
	   }
	   
	   $prd_setting = DB::select("SELECT * FROM product_setting");	 

	   $min = Products::join('product_categories', 'products.id', '=', 'product_categories.product_id')
					   ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
					   ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id');
				
					   if(!empty($cat_id)){
						$min = $min->where('product_categories.cat_id','=',$cat_id);
					   }
					   	  
					$min = $min->where('products.isdeleted',0)
					   ->where('vendors.isdeleted',0)
					   ->where('vendors.status',1)
					   ->where('products.vendor_id',$vendorID)
					   ->where('products.isblocked','=',0);				   
	 
			   $min =  $min->where('products.status','=',1)->min('spcl_price');			   
			   $max = Products::join('product_categories', 'products.id', '=', 'product_categories.product_id')
			   ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
			   ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
			   ->where('products.isdeleted',0);
			   if(!empty($cat_id)){
				$max = $max->where('product_categories.cat_id','=',$cat_id);
			   }
			   $max = $max->where('vendors.isdeleted',0)
			   ->where('vendors.status',1)
			   ->where('products.vendor_id',$vendorID)
			   ->where('products.isblocked','=',0);
			   
			   $max =  $max->where('products.status','=',1)->max('spcl_price'); 	  
			   $min_price=!empty($min)?$min:'1';
			   $max_price= !empty($max)?$max:'1'; 
	  
	  $data = Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
				   ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
				   ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				   ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
				   ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id');
				  
				   if(!empty($cat_id)){
					$data = $data->where('product_categories.cat_id','=',$cat_id);
				   }

				   $data = $data->where('products.isdeleted',0)
				   ->where('vendors.isdeleted',0)
				   ->where('vendors.status',1)
				   ->where('products.vendor_id',$vendorID)
				   ->where('products.status','=',1);
	  
	   
		   
						if(sizeof($color_array)>0){
			   /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
			   $data= $data->whereIn('product_attributes.color_id',$color_array); 
			   }
			
			if(sizeof($size_array)>0){
				/*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
				$data= $data->whereIn('product_attributes.size_id',$size_array); 
			}
			
			if(sizeof($discount_array)>0){
				//$data= $data->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">=",$discount_array);
			}
			
			if(sizeof($rating_array)>0){
				$data= $data->join('product_rating', 'products.id', '=', 'product_rating.product_id');
				$data= $data->whereIn(\DB::raw("round((product_rating.rating))"),$rating_array)->groupBy("product_rating.rating");
			}
			
		   
			if(sizeof($filtervalue_array)>0){
				$data= $data->join('product_filters', 'products.id', '=', 'product_filters.product_id');
				$data= $data->whereIn('product_filters.filters_input_value',$filtervalue_array); 
			}
			if(sizeof($brands)>0){
				$data= $data->whereIn('products.product_brand',$brands); 
			}
		   
							$data= $data->where(function($query) use ($min_price,$max_price,$type_filter,$min_price1,$max_price1){
							   if($type_filter!=''){
									$query->where('products.spcl_price','>=',$min_price1);
								   $query->where('products.spcl_price','<=',$max_price1);
							 } else{
									 $query->where('products.spcl_price','>=',$min_price);
								   $query->where('products.spcl_price','<=',$max_price);
							   }
						   
							   
								});
								
	   if($prd_setting[0]->product_shows_type==1)
	   {
		   $data->groupBy("product_attributes.product_id");
	   }
	   if($sortby!=''){
			   
			  
			   if($sortby==1){
				   $data=$data->orderBy("products.spcl_price","asc");
			   }elseif($sortby==2){
				   $data=$data->orderBy("products.spcl_price","desc");
			   }elseif($sortby==3){
				   $data=$data->orderBy("products.id","desc");
			   }
			   elseif($sortby==4){
				   $data=$data->orderBy("products.id","desc");
			   }
			   
		   }else{
			   $data=$data->orderBy("products.id",'desc');
		   }
			   
		   $data=$data ->orderBy("products.spcl_price",'asc')->paginate(36);


		
		    return view('fronted.mod_product.brand-product-listing',[
			   "products"=>$data,
			   'vendorsdetail' =>$vendorsdetail,
			   'totalProduct' =>count($totalProduct),
			   'cat_id'=>$cat_id,
			   'vendorID' =>$vendorID,
			   'min_price'=>$min_price,
			   'max_price'=>$max_price,
			   'min_price1'=>$min_price1,
			   'max_price1'=>$max_price1,
			   'brands'=>$brands,
			   'size_array'=>$size_array,
			   'color_array'=>$color_array,
			   'discount_array'=>$discount_array,
			   'rating_array'=>$rating_array,
			   'filtervalue_array'=>$filtervalue_array,
			   'type_filter'=>$type_filter,
			   'sortby'=>$sortby,
			   'pagename'=>'brandlisting'
			]);		

	}






	public function offer(Request $request)
	{
	    $sitecityname='NA';
		 if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	     }
	    
	    	$slider_type=base64_decode($request->type);
	    $products=ProductSlider::select('product_home_slider.product_id','product_home_slider.slider_name','product_home_slider.url','products.*')
			->join('products', 'product_home_slider.product_id', '=', 'products.id')
			  ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
                ->where('vendor_company_info.city','=',$sitecityname)
                ->where('vendors.isdeleted',0)
                ->where('vendors.status',1)
            ->where('products.status','=',1)
            ->where('products.isblocked','=',0)
            ->where('products.isexisting',0)
            ->where('products.isdeleted',0)
		->where('product_home_slider.slider_type',$slider_type)->paginate(9);
	
        return view('fronted.mod_product.offer-product-listing',['products'=>$products,'slider_type'=>$slider_type]);
	} 
	
	//Filter Code
	function filter_records($id='',$search='')
	{
		if($search!='')
		{
			$searchterm=" && (prd.name like '%".$search."%' || prd.sku like '%".$search."%' ) ";
		}

		if($id!='')
		{
			$id=array($id);
			$id=implode(',',$id);
			$id=explode(',',$id);
			$id=implode('","',$id);

			$myArray=explode('","',$id);

			$arr1 = array();
			$arr2 = array(); 
			$arr3 = array();  
			$arr4 = array();  

			foreach ($myArray as $item) {
				if (strpos($item, "brand") !== false) {
					$arr1[] = $item;
				}elseif (strpos($item, "price") !== false) {
					$arr2[] = $item;
				}elseif (strpos($item, "size") !== false) {
					$arr3[] = $item;
				}elseif (strpos($item, "color") !== false) {
					$arr4[] = $item;
				}
			}
		}else{
			$arr1 = array();
			$arr2 = array();
			$arr3 = array();   
			$arr4 = array();   
		}

		if(count($arr1)>0 && count($arr2)>0 && count($arr3)>0 && count($arr4)>0)
		{
			$brand = str_replace("brand",'',$arr1);
			$brand=implode(',',$brand);
			
			$price = str_replace("price",'',$arr2);
			$price=implode(',',$price);
			
			$size = str_replace("size",'',$arr3);
			$size=implode(',',$size);
			
			$color = str_replace("color",'',$arr4);
			$color=implode(',',$color);

			$price_first=0;
			$price_second=1000;
			
			$filterterm=" prd.status=1 ".@$searchterm." && attr.size_id in (" .$size. ")&& prd.product_brand in (" .$brand. ") && prd.price between " .$price_first. " and ".$price_second." ";
			
		}elseif(count($arr1)>0 && count($arr2)>0){
			
			$brand = str_replace("brand",'',$arr1);
			$brand=implode(',',$brand);

			$price = str_replace("price",'',$arr2);
			$price=implode(',',$price);
			
			$price_first=0;
			$price_second=1000;
			
			$filterterm=" prd.status=1 ".@$searchterm." && prd.product_brand in (" .$brand. ") && prd.price between " .$price_first. " and ".$price_second." ";
		
		}elseif(count($arr1)>0){
		
			$brand = str_replace("brand",'',$arr1);
			$brand=implode(',',$brand);
			$filterterm=" prd.status=1 ".@$searchterm." && prd.product_brand in (" .$brand. ")";
		
		}elseif(count($arr2)>0){
		
			$price = str_replace("price",'',$arr2);
			$price=implode(',',$price);

			$price_first=0;
			$price_second=1000;
			
			$filterterm=" prd.status=1 ".@$searchterm." && prd.price between " .$price_first. " and ".$price_second."";
		
		}elseif(count($arr3)>0){
			
			$size = str_replace("size",'',$arr3);
			
			for($i=0;$i<count($size);$i++)
			{
				$size1[]=$size[$i];
			}
			$size1=implode("','",$size1);
			$filterterm=" prd.status=1 ".@$searchterm." && attr.size_id in ('" .$size1. "')";
		
		}elseif(count($arr4)>0){
			
			$color = str_replace("color",'',$arr4);

			for($i=0;$i<count($color);$i++)
			{
				$color1[]=$color[$i];
			}

			$color1=implode("','",$color1);

			$filterterm=" prd.status=1 ".@$searchterm." && attr.color_id in ('" .$color1. "')";
			
		}else{
			
			$filterterm=" ".@$searchterm;
		}
		return 	$filterterm;
	}



	public function vendorprdlistingfilter(Request $request)
	{
		
		$sitecityname='NA';		    	
		$page_no=36;
		$cat_id=@$request->cat_id;
		$sortby=@$request->sortby; 
		$search=@$request->search; 
		
		if($cat_id!='' && $cat_id!=0){
		    $cat_data=Category::where('id',$cat_id)->first();
		    $cat_name=@$cat_data->name;
		}		
		
		$id=@$request->id;
		$min_price=$request->min_price;
		$max_price=$request->max_price;		
		$brands=array();
        $size_array=array();
        $color_array=array();
		$discount_array=array();
		$rating_array=array();
		$filtervalue_array=array();
        
        if($id!=''){
    	    $custom_filter= explode(",",$id);
                    
    		foreach($custom_filter as $row){
                    if (strpos($row, 'brand') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($brands,$id);
                    }
                    if (strpos($row, 'color') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($color_array,$id);
                    }
                    
                    if (strpos($row, 'size') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($size_array,$id);
                    }
					
					if (strpos($row, 'discount') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($discount_array,$id);
                    }
					
					if (strpos($row, 'rating') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($rating_array,$id);
                    }
					
					if (strpos($row, 'filtervalue') !== false) {
					     $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($filtervalue_array,$id);
                    }
    		}
        }
		
		//print_r($filtervalue_array);die;
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
	   	if($cat_id!='' && $cat_id!=0){
	   	  
		   $data =Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
             ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
             ->join('vendors', 'products.vendor_id', '=', 'vendors.id');
            
			$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');
			
			 if(sizeof($color_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.color_id',$color_array); 
             }
             
             if(sizeof($size_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
			 
			 if(sizeof($discount_array)>0){
                 //$data= $data->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">=",$discount_array);
             }
			 
			 if(sizeof($rating_array)>0){
                 $data= $data->join('product_rating', 'products.id', '=', 'product_rating.product_id');
				 $data= $data->whereIn(\DB::raw("round((product_rating.rating))"),$rating_array)->groupBy("product_rating.rating");
             }
			 
			
			 if(sizeof($filtervalue_array)>0){
			     $data= $data->join('product_filters', 'products.id', '=', 'product_filters.product_id');
				 $data= $data->whereIn('product_filters.filters_input_value',$filtervalue_array); 
             }
            
            $data = $data->where('product_categories.cat_id','=',$cat_id)
                            // ->where('vendor_company_info.city','=',$sitecityname)
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('products.status','=',1)
                            ->where('products.isblocked','=',0)
                            ->where('products.isdeleted',0)
                            ->where('products.spcl_price','>=',$min_price)
                            ->where('products.spcl_price','<=',$max_price);                      
             
			 if(sizeof($brands)>0){
				$data= $data->whereIn('products.vendor_id',$brands); 
             }
			 
			if($prd_setting[0]->product_shows_type==1)
			{
				$data->groupBy("product_attributes.product_id");
			}
            
        
            
			if($sortby!=''){
			    
			   
				if($sortby==1){
					$data=$data->orderBy("products.spcl_price","asc")->paginate($page_no);
				}elseif($sortby==2){
					$data=$data->orderBy("products.spcl_price","desc")->paginate($page_no);
				}elseif($sortby==3){
					$data=$data->orderBy("products.id","desc")->paginate($page_no);
				}
				elseif($sortby==4){
					$data=$data->orderBy("products.id","desc")->paginate($page_no);
				}
				
			}else{
				$data=$data->orderBy("products.id",'desc')->paginate($page_no);
			}
               
               
		} else if($search!=''){
		    file_put_contents("search.txt",json_encode($request->all()));
		   	$data =Products::select('products.*',"product_attributes.color_id","product_attributes.price as extra_price")
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				 		->join('product_categories','products.id','=','product_categories.product_id')
				 		->join('categories','product_categories.product_id','=','products.id')
				 		->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->join('vendors', 'products.vendor_id', '=', 'vendors.id');
			
				$data = $data->where('vendors.isdeleted',0)
						->whereIn('products.vendor_id',$brands)
						->where('vendors.status',1); 	    
	    
			if($prd_setting[0]->product_shows_type==1)
			{
				$data =$data->groupBy("product_attributes.product_id");
			}

                   
                $data =$data->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->Where(function($query) use ($search){
							 $query->Where('products.name','LIKE', '%' . $search . '%');
							 $query->orWhere('products.meta_title','LIKE', '%' . $search . '%');
	                         $query->orWhere('products.meta_keyword','LIKE', '%' . $search . '%');
		
						 })
						->groupBy('product_categories.product_id');
						 if($sortby!=''){
                        
                       
                    	if($sortby==1){
                    		$data=$data->orderBy("products.spcl_price","asc")->paginate($page_no);
                    	}elseif($sortby==2){
                    		$data=$data->orderBy("products.spcl_price","desc")->paginate($page_no);
                    	}elseif($sortby==3){
                    		$data=$data->orderBy("products.id","desc")->paginate($page_no);
                    	}
                    	elseif($sortby==4){
                    		$data=$data->orderBy("products.id","desc")->paginate($page_no);
                    	}
                    	
                    }else{
                    	$data=$data->orderBy("products.id",'desc')->paginate($page_no);
                    }
		} else{		 
		  

		    $data =Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id');  

            if(sizeof($color_array)>0){                
                 $data= $data->whereIn('product_attributes.color_id',$color_array);              
             }
             
              if(sizeof($size_array)>0){                
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
            
            $data = $data->where('products.isdeleted',0)
					->where('products.status','=',1)
					->where('products.isblocked','=',0)
					->where('vendors.isdeleted',0)
					->whereIn('products.vendor_id',$brands)
					->where('vendors.status',1)			
             ->where(function($query) use ($min_price,$max_price){
                 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
             })
              ->groupBy('products.id')
             ->where('products.status','=',1);
			 
			if($prd_setting[0]->product_shows_type==1)
			{
				$data->groupBy("product_attributes.product_id");
			}		
             
            if($sortby!=''){                       
                       
				if($sortby==1){
					$data=$data->orderBy("products.spcl_price","asc")->paginate($page_no);
				}elseif($sortby==2){
					$data=$data->orderBy("products.spcl_price","desc")->paginate($page_no);
				}elseif($sortby==3){
					$data=$data->orderBy("products.id","desc")->paginate($page_no);
				}
				elseif($sortby==4){
					$data=$data->orderBy("products.id","desc")->paginate($page_no);
				}
				
			}else{
				$data=$data->orderBy("products.id",'desc')->paginate($page_no);
			}
            
		
		}
	   
		if ($request->ajax()) {
			return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$id,'cat_name'=>@$cat_name])->render();
		}
		
		return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$cat_id,'cat_name'=>@$cat_name]);; 
	}
	
	public function listing_filter(Request $request)
	{
		
		$sitecityname='NA';
		 if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	    }
	    
		//$page_no=$request->page*2;
            $page_no=36;
            $cat_id=@$request->cat_id;
            $sortby=@$request->sortby; 
            $search=@$request->search; 
		
		if($cat_id!='' && $cat_id!=0){
		    $cat_data=Category::where('id',$cat_id)->first();
		    $cat_name=@$cat_data->name;
		}
		
		
		$id=@$request->id;
		$min_price=$request->min_price;
		$max_price=$request->max_price;
		
		$brands=array();
        $size_array=array();
        $color_array=array();
		$discount_array=array();
		$rating_array=array();
		$filtervalue_array=array();
        
        if($id!=''){
			//echo "if";
    	    $custom_filter= explode(",",$id);
                    
    		foreach($custom_filter as $row){
                    if (strpos($row, 'brand') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($brands,$id);
                    }
                    if (strpos($row, 'color') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($color_array,$id);
                    }
                    
                    if (strpos($row, 'size') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($size_array,$id);
                    }
					
					if (strpos($row, 'discount') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($discount_array,$id);
                    }
					
					if (strpos($row, 'rating') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($rating_array,$id);
                    }
					
					if (strpos($row, 'filtervalue') !== false) {
					     $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($filtervalue_array,$id);
                    }
    		}
        }
		
		//print_r($filtervalue_array);die;
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
	   	if($cat_id!='' && $cat_id!=0){
	   	  
		   $data =Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
             ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
             ->join('vendors', 'products.vendor_id', '=', 'vendors.id');
            
			$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');
			
			 if(sizeof($color_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.color_id',$color_array); 
             }
             
             if(sizeof($size_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
			 
			 if(sizeof($discount_array)>0){
                 //$data= $data->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">=",$discount_array);
             }
			 
			 if(sizeof($rating_array)>0){
                 $data= $data->join('product_rating', 'products.id', '=', 'product_rating.product_id');
				 $data= $data->whereIn(\DB::raw("round((product_rating.rating))"),$rating_array)->groupBy("product_rating.rating");
             }
			 
			
			 if(sizeof($filtervalue_array)>0){
			     $data= $data->join('product_filters', 'products.id', '=', 'product_filters.product_id');
				 $data= $data->whereIn('product_filters.filters_input_value',$filtervalue_array); 
             }
            
            $data = $data->where('product_categories.cat_id','=',$cat_id)
                            // ->where('vendor_company_info.city','=',$sitecityname)
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1)
                            ->where('products.status','=',1)
                            ->where('products.isblocked','=',0)
                            // ->where('products.isexisting',0)
                            ->where('products.isdeleted',0)
                            ->where('products.spcl_price','>=',$min_price)
                            ->where('products.spcl_price','<=',$max_price);
                        //  ->whereBetween('products.spcl_price', array(220,825));
             
			 /*->where(function($query) use ($min_price,$max_price){
                   $query->whereBetween('products.price', array($min_price,$max_price));
             })*/
            //  ->where(function($query) use ($min_price,$max_price){
            //     //  echo $min_price." ".$max_price;
            //     //  die();
            //      $query->whereBetween('products.spcl_price', array($min_price,$max_price));
            //     //  $query->orwhereBetween('products.price', array($min_price,$max_price));
            //  });
              /*->groupBy('products.id'); */
             
			 if(sizeof($brands)>0){
                //  $data= $data->whereIn('products.product_brand',$brands); 
				$data= $data->whereIn('products.vendor_id',$brands); 
             }
			 
			if($prd_setting[0]->product_shows_type==1)
			{
				$data->groupBy("product_attributes.product_id");
			}
            
        
            
			if($sortby!=''){
			    
			   
				if($sortby==1){
					$data=$data->orderBy("products.spcl_price","asc")->paginate($page_no);
				}elseif($sortby==2){
					$data=$data->orderBy("products.spcl_price","desc")->paginate($page_no);
				}elseif($sortby==3){
					$data=$data->orderBy("products.id","desc")->paginate($page_no);
				}
				elseif($sortby==4){
					$data=$data->orderBy("products.id","desc")->paginate($page_no);
				}
				
			}else{
				$data=$data->orderBy("products.id",'desc')->paginate($page_no);
			}
               
               
		} else if($search!=''){
			//echo "else if";
		    file_put_contents("search.txt",json_encode($request->all()));
		   	$data =Products::select('products.*',"product_attributes.color_id","product_attributes.price as extra_price")
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				 		->join('product_categories','products.id','=','product_categories.product_id')
				 		->join('categories','product_categories.product_id','=','products.id')
				 		->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                        ->join('vendors', 'products.vendor_id', '=', 'vendors.id');
			
			
		              
    
	      $data = $data->where('vendor_company_info.city','=',$sitecityname)
                            ->where('vendors.isdeleted',0)
                            ->where('vendors.status',1);
	   
	    
	    
	    
			if($prd_setting[0]->product_shows_type==1)
			{
				$data =$data->groupBy("product_attributes.product_id");
			}

                   
                $data =$data->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->Where(function($query) use ($search){
							 $query->Where('products.name','LIKE', '%' . $search . '%');
							 $query->orWhere('products.meta_title','LIKE', '%' . $search . '%');
	                         $query->orWhere('products.meta_keyword','LIKE', '%' . $search . '%');
							 //$query->orWhere('categories.name','LIKE', '%' . $search . '%');
							 //$query->orWhere('categories.name','=', $search);
						 })
						->groupBy('product_categories.product_id');
						 if($sortby!=''){
                        
                       
                    	if($sortby==1){
                    		$data=$data->orderBy("products.spcl_price","asc")->paginate($page_no);
                    	}elseif($sortby==2){
                    		$data=$data->orderBy("products.spcl_price","desc")->paginate($page_no);
                    	}elseif($sortby==3){
                    		$data=$data->orderBy("products.id","desc")->paginate($page_no);
                    	}
                    	elseif($sortby==4){
                    		$data=$data->orderBy("products.id","desc")->paginate($page_no);
                    	}
                    	
                    }else{
                    	$data=$data->orderBy("products.id",'desc')->paginate($page_no);
                    }
		} else{
		 
		  // echo "else";
		    //brand listing page
		      
		    $brand_id=Brands::select('id')->where('name',$request->brand)->first();
		   
		    $data =Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
                ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                ->join('vendor_company_info', 'products.vendor_id', '=', 'vendor_company_info.vendor_id')
                ->join('vendors', 'products.vendor_id', '=', 'vendors.id');
            
            if(sizeof($color_array)>0){
                
                 $data= $data->whereIn('product_attributes.color_id',$color_array); 
             
             }
             
              if(sizeof($size_array)>0){
                
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
            
            // $data = $data->where('products.product_brand','=',$brand_id->id) //not required
            $data = $data->where('products.isexisting',0)
            ->where('products.isdeleted',0)
            ->where('products.status','=',1)
            ->where('products.isblocked','=',0)
            ->where('vendor_company_info.city','=',$sitecityname)
            ->where('vendors.isdeleted',0)
            ->where('vendors.status',1)
			 /*->where(function($query) use ($min_price,$max_price){
                   $query->whereBetween('products.price', array($min_price,$max_price));
             })*/
             ->where(function($query) use ($min_price,$max_price){
                 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
             })
              ->groupBy('products.id')
             ->where('products.status','=',1);
			 
			if($prd_setting[0]->product_shows_type==1)
			{
				$data->groupBy("product_attributes.product_id");
			}
			
			
             
            $data=$data->orderBy("products.spcl_price")->paginate(9);
            
		
		}
		if ($request->ajax()) {
			return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$id,'cat_name'=>@$cat_name])->render();
		}
		
		return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$cat_id,'cat_name'=>@$cat_name]);; 
	}
	
	public function brand_filter(Request $request)
	{
		$id=$request->brand_id;
		
		$data = DB::select("SELECT brd.* FROM products prd inner join brands brd on brd.id=prd.product_brand where prd.status=1 && brd.name like '%".$id."%' group by brd.name");
		
		return view('fronted.mod_product.brand-filter-listing',["brand_data"=>$data]);; 
	}
	
	public function size_filter(Request $request)
	{
		$id=$request->size_id;
		
		$data = DB::select("SELECT size.* FROM products prd inner join product_attributes attr on attr.product_id=prd.id inner join sizes size on size.id=attr.size_id where prd.status=1 && size.name like '%".$id."%' group by size.name");
		
		return view('fronted.mod_product.size-filter-listing',["size_data"=>$data]);; 
	}
	
	public function color_filter(Request $request)
	{
		$id=$request->color_id;
		
		$data = DB::select("SELECT color.* FROM products prd inner join product_attributes attr on attr.product_id=prd.id inner join colors color on color.id=attr.color_id where prd.status=1 && color.name like '%".$id."%' group by color.name");
		
		return view('fronted.mod_product.color-filter-listing',["color_data"=>$data]);; 
	}
	
	public function search_filter(Request $request)
	{
		$keyword=$request->keyword;
			$sitecityname='NA';
		 if(isset($_COOKIE['sitecity'])){
	       $sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
	    }
// 			inner join product_attributes on prd.id = product_attributes.product_id

		$data = DB::select("SELECT prd.* FROM products prd
	        inner join vendor_company_info on prd.vendor_id = vendor_company_info.vendor_id
            inner join vendors on prd.vendor_id = vendors.id where prd.isdeleted=0 &&  vendors.isdeleted =0 && 
		vendors.status = 1 
		&&  prd.isexisting=0 
		&& prd.status=1 
		&& (
			prd.name like '%".$keyword."%' 
			or prd.sku like '%".$keyword."%' 
			or prd.meta_keyword like '%".$keyword."%' 
			or prd.meta_title like '%".$keyword."%' 
			 ) group by prd.name");
		
		$data1 = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.parent_id!=0 && cat.status=1 && cat.name like '%".$keyword."%' group by cat.name");
		
		for($i=0; $i<count($data); $i++)
		{
			echo '<div class="display_box" >'.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data[$i]->name))).'</div>';
		}
		
		for($i=0; $i<count($data1); $i++)
		{
			echo '<div class="display_box" >'.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data1[$i]->name))).'</div>';
			
			$data2 = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.status=1 && cat.parent_id=".$data1[$i]->id);
			
			for($m=0; $m<count($data2); $m++)
			{
				echo '<div class="display_box" >'.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data1[$i]->name))).'<span style="color:red"> in '.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data2[$m]->name))).'</span></div>';
				
				$data3 = DB::select("SELECT cat.* FROM categories cat where cat.isdeleted=0 && cat.status=1 && cat.parent_id=".$data2[$m]->id);
				
				for($n=0; $n<count($data3); $n++)
				{
					echo '<div class="display_box" >'.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data2[$m]->name))).'<span style="color:red"> in '.ucwords(str_replace($keyword,"<b>".$keyword."</b>",strtolower($data3[$n]->name))).'</span></div>';
				}
			}
		}

		echo'<script>$(".display_box").click(function() {
				var value = $(this).text();
				$("#searchterm").val(value);
				$("#display").hide();
			});</script>';
		}
	public function mendmbrandsshownore(Request $request)
	{
		echo "mendmbrandsshownore";
		exit();
	}
	public function womensbrandsshownore(Request $request)
	{
		echo "womensbrandsshownore";
		exit();
	}
	public function branddetails(Request $request)
	{
		$vendorsdetails=DB::table('vendors')
		->select('vendors.id','vendor_company_info.name','vendor_company_info.logo')
		->Join('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
		->where(['vendors.status'=>'1','isdeleted'=>0])
		->orderBy('vendors.featuremart','desc')
		->get();
		
		return view('fronted.mod_vendors.vendorsdetails',["vendorsdetails"=>$vendorsdetails,'pagename'=>'brandlisting']);
	}
	

 
}
