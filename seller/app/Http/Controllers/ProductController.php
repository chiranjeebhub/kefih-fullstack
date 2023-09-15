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
use App\Helpers\CommonHelper;
use App\Colors;
use URL;
use App\Sizes;
use App\Brands;
use App\Filters;
use App\ProductExtraDescription;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use Cookie;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
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
        $html=$html1='';
        $thumbnails=array(
                    "main_image"=>'',
                    "thumbnails"=>'',
                    "isColorImagesSet"=>0
                 );
        $images=DB::table('product_configuration_images')
				->select(
						DB::raw("CONCAT('".$this->site_base_path."uploads/products/',product_configuration_images.product_config_image) AS image")
					)
				->where('product_id',$input['prd_id'])
                ->where('color_id',$input['color_id'])
                ->get();
                
		$images_data=DB::table('product_configuration_images')
			->select(
					DB::raw("CONCAT('".$this->site_base_path."uploads/products/',product_configuration_images.product_config_image) AS image")
				)
			->where('product_id',$input['prd_id'])
			->where('color_id',$input['color_id'])
			->first();
                
    //             $i=2;
				
				// $html1.='<div class="tab-pane fade show active" id="product-slide1" role="tabpanel" aria-labelledby="product-slide-tab-1">
				// 				<div class="single-product-img easyzoom img-full">
				// 					<a href="'.$images[0]->image.'" id="img_zoom_extra"><img src="'.$images[0]->image.'" class="img-fluid" alt="" id="img_zoom"></a>
				// 				</div>
				// 			</div>';
				
				// $html.='<div class="single-small-image img-full">
				// 			<a data-toggle="tab" id="product-slide-tab-1" href="#product-slide1"><img src="'.$images[0]->image.'" class="img-fluid" alt=""></a>
				// 		</div>';
				
				
    //             foreach($images as $image){
                    	
				// 	if($i!=2)
				// 	{
				// 		$html1.='<div class="item">
				// 					<div class="tab-pane fade" id="product-slide'.$i.'" role="tabpanel" aria-labelledby="product-slide-tab-'.$i.'">
				// 						<div class="single-product-img easyzoom img-full">
				// 						<a href="'.$image->image.'"><img src="'.$image->image.'" class="img-fluid" alt=""></a>
				// 						</div>
				// 					</div>
				// 				</div>';
								
				// 		$html.='<div class="item"><div class="single-small-image img-full">
				// 					<a data-toggle="tab" id="product-slide-tab-'.$i.'" href="#product-slide'.$i.'"><img src="'.$image->image.'" class="img-fluid" alt=""></a>
				// 				</div></div>';
							
				// 	}
					
    //                 $i++;
    //             }
				
					$html.='';
               foreach($images as $image){
                    $html.='<a href="javascript:void(0)" data-image="'.$image->image.'" data-zoom-image="'.$image->image.'" class="thumbnailSmall">
                            <img src="'.$image->image.'" data-large-image="'.$image->image.'" 
                            alt="">

					</a>';
                    
                }
			
				
                if(sizeof($images)>0){
                     $thumbnails=array(
                    "main_image"=>$images_data->image,
                    "thumbnails"=>$html,
					"large_thumbnails"=>$html1,
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
		$prd_data=Products::productDetails($input['prd_id']);
		$old=$prd_data->price;
		$new=$prd_data->price;
		if($prd_data->spcl_price!='' && $prd_data->spcl_price!=0){
		    	$new=$prd_data->spcl_price;
		}
	
		
		if($input['color_id']==0 && $input['size_id']!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('size_id',$input['size_id'])
		      ->where('qty','>',0)
		    ->first();
		    
		    
		    $old+=$attr_data->price;
		    $new+=$attr_data->price;
		}
		if($input['color_id']!=0 && $input['size_id']==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('color_id',$input['color_id'])
		      ->where('qty','>',0)
		    ->first();
		    $old+=$attr_data->price;
		    $new+=$attr_data->price;
		}
		if($input['color_id']!=0 && $input['size_id']!=0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$input['prd_id'])
		     ->where('color_id',$input['color_id'])
		     ->where('size_id',$input['size_id'])
		      ->where('qty','>',0)
		    ->first();
		    $old+=$attr_data->price;
		    $new+=$attr_data->price;
		}
			$response=array(
                "old_price"=>$old,
                "new_price"=>$new,
                "percent"=>Products::offerPercentage($old,$new)
				);
				
				echo json_encode($response);
	 }
    public function get_attr_dependend(Request $request){
		$input=$request->all();
		
		$obj=new Products();
		$prd_data=Products::productDetails($input['prd_id']);
		$data=$obj->getProductsAttributes($input['attr_name'],$input['size_id'],$input['prd_id']); /*Size_id mai color id aa rhaa hai*/
		
		$html='<div class="sizeoptn">';
		foreach($data as $row){
			if($input['attr_name']=='Sizes'){
				$appendto='sizes_html';
				$name=$obj->getAttrName('Sizes',$row['size_id']);
				$html.='<span title="small"><a href="javascript:void(0)" class="badge badge-primary sizeClass" prd_id='.$input['prd_id'].' size_id="'.$row['size_id'].'" >'.$name.'</a></span>';
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
				title="'.$color_data->name.'"><img src="'.$url.'" height="40" width="40" alt="'.$color_data->name.'"></span>';
				//$html.='<span title="small"><a href="javascript:void(0)" class="badge badge-primary colorClass" prd_id='.$input['prd_id'].' color_id='.$row['color_id'].'>'.$name.'</a></span>';
			}
		}
		
	$html.='</div>';
		$color_id=(sizeof($data)==1)?$data[0]['size_id']:0;
			    $appendto='colors_html';
			if($input['attr_name']=='Sizes'){
			    $appendto='sizes_html';
			} 
		$response=array(
				"html"=>$html,
				"color_id"=>$color_id,
				"print_to"=>$appendto
				);
				
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
    public function add_to_cart(Request $request){
                        
		$input=$request->all();
		
		 $Products = Products::select('stock_availability','qty_out')
        ->where('id','=',$input['prd_id'])
        ->first();
        
    //     if($Products->stock_availability==0){
    //         $response=array(
				// "status"=>false,
				// "method"=>3
				// );
				// echo json_encode($response);
				// die();
    //     }
        
    //     if($Products->qty_out<$input['qty']){
    //         $response=array(
				// "status"=>false,
				// "method"=>3
				// );
				// echo json_encode($response);
				// die();
    //     }
      
		
		$prd_in_cart=array(
                'product_id'=>$input['prd_id'],
                'size_id'=>$input['size_id'],
                'color_id'=>$input['color_id'],
                'qty'=>$input['qty'],
		          
		    );
		
       

		$cust_id='';
		if(Auth::guard('customer')->check())
		{
			$cust_id=auth()->guard('customer')->user()->id ;
		}
	
		
		
			$stock = ProductAttributes::select('qty')
        ->where('size_id','=',$input['size_id'])
         ->where('color_id','=',$input['color_id'])
        ->where('product_id','=',$input['prd_id'])
        ->first();
	   //}
        
       
      
         $quantity = $stock ? $stock->qty : 0;
     
         if($quantity >= $input['qty']) {
            $return=app(\App\Http\Controllers\CookieController::class)->setcustomCartCookie($prd_in_cart); 
          
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
	}
	
	/*public function products(Request $request) {
	    $cat_id=base64_decode($request->cat);
		$data = Products::select('products.*')
				->where('isdeleted', 0)
				->where('prd_sts', 1)
				->orderBy('id', 'DESC')
				->paginate(9);
	    return view('fronted.product-listing',["products"=>$data,'cat_id'=>$cat_id]);
    }*/
	
    public function productDetails(Request $request)
    {
       
         $decodeInput=explode("~~~",base64_decode($request->id));
		$id=$decodeInput[0];
	
      
	  
	   $prd_detail = Products::select('products.*')
							   ->where('products.id','=',$id)
							   ->first();
		
    app(\App\Http\Controllers\CookieController::class)->setCookie($id); 
    // app(\App\Http\Controllers\CookieController::class)->setProductCookie(array()); 
		
		
        return view('fronted.mod_product.product-detail',['prd_detail'=>$prd_detail,"decodeInput"=>$decodeInput]);
    } 
	
	public function SearchProduct(Request $request)
    {
		$input= $request->search;
		
		$uc=explode(" in ",$input);
		
		if(@$uc[1]!=''){
			$search=$uc[1];
			$type='cat';
		}elseif(@$uc[0]!=''){
			$search=$uc[0];
		}
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('categories','product_categories.product_id','=','products.id')
						
						->where('products.isexisting',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status','=',1)
                        ->where('products.isblocked','=',0)
						->Where(function($query) use ($search){
							 $query->orWhere('products.name','LIKE', '%' . $search . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $search . '%');
						 })->get()->first();
	   
	   
	    $min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
		$max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
		
		$cat_data = DB::select('SELECT cat.* FROM categories cat where cat.status=1 && cat.name = "'.$search.'"');
		
		if(@$cat_data[0]->id!='')
		{
			$data =Products::select('products.*',"product_attributes.color_id","product_attributes.price as extra_price")
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				 		->join('product_categories','products.id','=','product_categories.product_id');
				 		
						
			if($prd_setting[0]->product_shows_type==1)
			{
				$data =$data->groupBy("product_attributes.product_id");
			}

                  $data =$data->where('products.isexisting',0)
						->where('product_attributes.qty','>',0)
                        ->where('products.isdeleted',0)
                        ->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->where('product_categories.cat_id','=',$cat_data[0]->id)
						->paginate(9);
						
			
		}else{
			
			$data =Products::select('products.*',"product_attributes.color_id","product_attributes.price as extra_price")
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
				 		->join('product_categories','products.id','=','product_categories.product_id')
				 		->join('categories','product_categories.product_id','=','products.id');
						
			if($prd_setting[0]->product_shows_type==1)
			{
				$data =$data->groupBy("product_attributes.product_id");
			}

                $data =$data->where('products.isexisting',0)
                        ->where('product_attributes.qty','>',0)
						->where('products.isdeleted',0)
                        ->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->Where(function($query) use ($search){
							 $query->orWhere('products.name','LIKE', '%' . $search . '%');
							 //$query->orWhere('categories.name','LIKE', '%' . $search . '%');
							 $query->orWhere('categories.name','=', $search);
						 })
						->groupBy('product_categories.product_id')
						->paginate(9);
		}
	   
		

	    /*$data = Products::select('products.*')
        ->where('products.name','LIKE', '%' . $input . '%')
	   ->orWhere('products.short_description','LIKE', '%' . $input . '%')
	   ->orWhere('products.long_description','LIKE', '%' . $input . '%')
	   ->orWhere('products.sku','LIKE', '%' . $input . '%')
	   ->orWhere('products.weight','LIKE', '%' . $input . '%')
	   ->orWhere('products.hsn_code','LIKE', '%' . $input . '%')
	   ->orWhere('products.prd_sts','LIKE', '%' . $input . '%')
	   ->orWhere('products.price','LIKE', '%' . $input . '%')
	   ->orWhere('products.spcl_price','LIKE', '%' . $input . '%')
	   ->orWhere('products.qty','LIKE', '%' . $input . '%')
	   ->orWhere('products.manage_stock','LIKE', '%' . $input . '%')
	   ->orWhere('products.qty_out','LIKE', '%' . $input . '%')
	   ->orWhere('products.stock_availability','LIKE', '%' . $input . '%')
	   ->orWhere('products.product_brand','LIKE', '%' . $input . '%')
	   ->orWhere('products.material','LIKE', '%' . $input . '%')
       ->paginate(9);*/
	   
         return view('fronted.mod_product.product-listing',["products"=>$data,'searchterm'=>$input,'cat_id'=>1,'min_price'=>$min_price,'max_price'=>$max_price]);
         
    } 
   
   	public function cat_wise(Request $request)
	{
		$id=base64_decode($request->id);
		$input='';
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"))
						->join('product_categories', 'products.id', '=', 'product_categories.product_id')
						->where('product_categories.cat_id','=',$id)
						->where('products.isexisting',0)
						->where('products.isdeleted',0)
						->where('products.isblocked','=',0)
						->where('products.status','=',1)->get()->first();
	   
	   /*$spcl_max = Products::select("products.id",\DB::raw("min(products.spcl_price) as max_price"))
	   ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
	   ->where('product_categories.cat_id','=',$id)
	   ->where('products.isexisting',0)
	    ->where('products.isdeleted',0)
	   ->where('products.status','=',1)->max('spcl_price');*/
	   
	   $min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
	   $max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
	   
	   $data = Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
							->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							->where('product_categories.cat_id','=',$id)
							//->whereNotIn('product_attributes.color_id',[0])
							//->where('product_attributes.qty','>',0)
							->where('products.isexisting',0)
							->where('products.isdeleted',0)
							->where('products.status','=',1)
							->where(function($query) use ($min_price,$max_price){
									 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
								 });
								 
		if($prd_setting[0]->product_shows_type==1)
		{
			$data->groupBy("product_attributes.product_id");
		}
							
			$data=$data ->orderBy("products.spcl_price")->paginate(21);
	   
	    if ($request->ajax()) {
			return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$id,'cat_name'=>'test'])->render();
		}
	  
		 return view('fronted.mod_product.product-listing',["products"=>$data,'cat_id'=>$id,'min_price'=>$min_price,'max_price'=>$max_price]);
	}
	public function offer(Request $request)
	{
	    	$slider_type=base64_decode($request->type);
	    $products=ProductSlider::select('product_home_slider.product_id','product_home_slider.slider_name','product_home_slider.url','products.*')
			->join('products', 'product_home_slider.product_id', '=', 'products.id')
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
	
	public function listing_filter(Request $request)
	{
		//$page_no=$request->page*2;
		$page_no=21;
		 $cat_id=@$request->cat_id;
		 $sortby=@$request->sortby; 
		
		if($cat_id!='' || $cat_id!=0){
		    $cat_data=Category::where('id',$cat_id)->first();
		    $cat_name=@$cat_data->name;
		}
		
		
		$id=@$request->id;
		$min_price=$request->min_price;
		$max_price=$request->max_price;
		
		$brands=array();
        $size_array=array();
        $color_array=array();
		$material_array=array();
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
					
					if (strpos($row, 'material') !== false) {
                            $id = (int) filter_var($row, FILTER_SANITIZE_NUMBER_INT);
                            array_push($material_array,$id);
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
		
	   	if($cat_id!='' && $cat_id!=0){
		   $data =Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id');
            
			$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');
			
			 if(sizeof($color_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.color_id',$color_array); 
             }
             
             if(sizeof($size_array)>0){
                 /*$data= $data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');*/
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
			 
			 if(sizeof($material_array)>0){
                $data= $data->whereIn('products.material',$material_array); 
             }
			 
			  if(sizeof($discount_array)>0){
                 $data= $data->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">=",$discount_array);
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
            
			->where('product_attributes.qty','>',0)
			->where('products.status','=',1)
            ->where('products.isblocked','=',0)
            ->where('products.isexisting',0)
            ->where('products.isdeleted',0)
             
			 /*->where(function($query) use ($min_price,$max_price){
                   $query->whereBetween('products.price', array($min_price,$max_price));
             })*/
             ->where(function($query) use ($min_price,$max_price){
                 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
             });
              /*->groupBy('products.id'); */
             
			 if(sizeof($brands)>0){
                 $data= $data->whereIn('products.product_brand',$brands); 
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
				
			}else{
				$data=$data->orderBy("products.spcl_price")->paginate($page_no);
				//echo $data=$data->orderBy("products.spcl_price")->toSql();
			}
		
		} else{
		 
		
		    //brand listing page
		      
		    $brand_id=Brands::select('id')->where('name',$request->brand)->first();
		   
		    $data =Products::select("products.*","product_attributes.color_id","product_attributes.size_id","product_attributes.price as extra_price")
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');
            
            if(sizeof($color_array)>0){
                
                 $data= $data->whereIn('product_attributes.color_id',$color_array); 
             
             }
             
              if(sizeof($size_array)>0){
                
                 $data= $data->whereIn('product_attributes.size_id',$size_array); 
             }
            
            $data = $data->where('products.product_brand','=',$brand_id->id)
            ->where('product_attributes.qty','>',0)
			->where('products.isexisting',0)
            ->where('products.isdeleted',0)
            ->where('products.status','=',1)
            ->where('products.isblocked','=',0)
             
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
             
            $data=$data->orderBy("products.spcl_price")->paginate(21);
            
		
		}
	   
		if ($request->ajax()) {
			return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$id,'cat_name'=>@$cat_name])->render();
		}
		
		return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$cat_id,'cat_name'=>@$cat_name]);; 
	}
	
	public function brand_filter(Request $request)
	{
		$id=$request->brand_id;
		$cat_id=$request->cat_id;
		
		//$data = DB::select("SELECT brd.* FROM products prd inner join brands brd on brd.id=prd.product_brand where prd.status=1 && brd.name like '%".$id."%' group by brd.name");
		
		$data= Products::select('brands.*')
								->join('brands', 'products.product_brand', '=', 'brands.id')
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							    ->where('product_categories.cat_id','=',$cat_id)
								->where('brands.name','like','%'.$id.'%')
							    ->where('products.isdeleted','=',0)
								->where('products.status','=',1)
								->groupBy('brands.id')
								->orderBy('brands.name')
								->get();	
		
		
		return view('fronted.mod_product.brand-filter-listing',["brand_data"=>$data]);; 
	}
	
	public function material_filter(Request $request)
	{
		$id=$request->material_id;
		$cat_id=$request->cat_id;
		
		/*$data = DB::select("SELECT mat.* FROM products prd inner join materials mat on mat.id=prd.material where prd.status=1 && mat.name like '%".$id."%' group by mat.name");*/
		
		$data= Products::select('materials.*')
								->join('materials', 'products.material', '=', 'materials.id')
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							    ->where('product_categories.cat_id','=',$cat_id)
								->where('materials.name','like','%'.$id.'%')
							    ->where('products.isdeleted','=',0)
								->where('products.status','=',1)
								->groupBy('materials.id')
								->orderBy('materials.name')
								->get();
		
		return view('fronted.mod_product.material-filter-listing',["material_data"=>$data]);; 
	}
	
	public function size_filter(Request $request)
	{
		$id=$request->size_id;
		$cat_id=$request->cat_id;
		
		/*$data = DB::select("SELECT size.* FROM products prd inner join product_attributes attr on attr.product_id=prd.id inner join sizes size on size.id=attr.size_id where prd.status=1 && size.name like '%".$id."%' group by size.name");*/
		
		 $data =Products::select('sizes.*')
								->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
								->where('product_categories.cat_id','=',$cat_id)
								->where('sizes.name','like','%'.$id.'%')
								->where('products.isdeleted','=',0)
								->where('products.status','=',1)
								->groupBy('sizes.id')
								->orderBy('sizes.name')
								->get();
		
		return view('fronted.mod_product.size-filter-listing',["size_data"=>$data]);; 
	}
	
	public function color_filter(Request $request)
	{
		$id=$request->color_id;
		$cat_id=$request->cat_id;
		
		/*$data = DB::select("SELECT color.* FROM products prd inner join product_attributes attr on attr.product_id=prd.id inner join colors color on color.id=attr.color_id where prd.status=1 && color.name like '%".$id."%' group by color.name");*/
		
		$data = Products::select('colors.*')
								->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->join('colors', 'product_attributes.color_id', '=', 'colors.id')
								->where('product_categories.cat_id','=',$cat_id)
								->where('colors.name','like','%'.$id.'%')
								->where('products.isdeleted','=',0)
								->where('products.status','=',1)
								->groupBy('colors.id')
								->orderBy('colors.name')
								->get();	
		
		return view('fronted.mod_product.color-filter-listing',["color_data"=>$data]);; 
	}
	
	public function search_filter(Request $request)
	{
		$keyword=$request->keyword;
		
		$data = DB::select("SELECT prd.* FROM products prd where prd.isdeleted=0  && prd.isexisting=0 && prd.status=1 && (prd.name like '%".$keyword."%' or prd.meta_keyword like '%".$keyword."%' or prd.meta_title like '%".$keyword."%'  ) group by prd.name");
		
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
	
	

 
}
