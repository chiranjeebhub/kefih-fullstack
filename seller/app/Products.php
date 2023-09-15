<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use App\ProductImages;
use App\Brands;
use App\Vendor;
use App\ProductAttributes;
use App\ProductCategories;
use App\Category;
use DB;
class Products extends Model
{
	
	
    protected $table = 'products';
  
    protected $dates = ['created_at', 'updated_at'];
    
    public static function getproductImageUrl($type=0,$name){
         $path=Config::get('constants.Url.public_url');
        if($type=0){
        $path.=Config::get('constants.uploads.product_images').'/'.$name;
          } else{
     $path.=Config::get('constants.uploads.product_thumb_image').'/'.$name;
        }
        return $path;
    }
    public static function getproductImageUrlAPI($type=0,$name){
          $path=Config::get('constants.Url.public_url');
          $path.=Config::get('constants.uploads.product_images').'/'.$name;
           return $path;
    }
   public static function updateSku($prd_id){
       
            $prd_data=Products::where('id', '=',$prd_id)->first();
            $sku=$prd_data->sku;
         
        $brand_name='';
        $vendor_name='';
        $brand_data=Brands::where('id', '=',$prd_data->product_brand)->first();
        $Vendor_data=Vendor::where('id', '=',$prd_data->vendor_id)->first();
        
        	$res=ProductCategories::select('cat_id')->where('product_id',$prd_id)->get()->toarray();
			$cat_name='';
		foreach($res as $row){
		    $cat_data=Category::where('id',	$row['cat_id'])->first();
		$cat_name.=substr($cat_data->name, 0, 3);
		}
       
       if($brand_data){
         $brand_name= substr($brand_data->name, 0, 3); 
       }
        if($Vendor_data){
           $vendor_name=substr($Vendor_data->public_name, 0, 3);
       }
       
       $sku_code=$prd_id.$brand_name.$vendor_name.$cat_name;
        $res=Products::where('id', '=',$prd_id)
		->update(
		[
        'sku' => strtolower($sku_code),
		]);
		
		
    }
    
    public static function decreaseProductQty($prd_id,$size_id,$color_id,$qty){
         
        $product = Products::find($prd_id);
        $product->decrement('qty',$qty);
   
        
          DB::table('product_attributes')
                ->where('product_id',$prd_id)
                ->where('size_id',$size_id)
                ->where('color_id',$color_id)
                ->decrement('qty',$qty);
    }
    public static function prdStsInactive($prd_id){
        
        DB::table('products')->where('id',$prd_id)->update(
            array('status'=>0)
            );
   
    }
    
     public static function increaseProductQty($prd_id,$size_id,$color_id,$qty){
        
		
        $product = Products::find($prd_id);
        $product->increment('qty',$qty);
        
        
          ProductAttributes::
                where('product_id',$prd_id)
                ->where('size_id',$size_id)
                ->where('color_id',$color_id)
                ->increment('qty',$qty);
        
    }
    	function updateReason($arr,$id){
		
			if (array_key_exists("cat",$arr))
		{    
				$data=array();
			DB::table('reason_category')->where('reason_id',$id)->delete();
			foreach($arr['cat'] as $row){
				array_push($data,array('reason_id'=>$id, 'category_id'=> $row));
			}
			$res=DB::table('reason_category')->insert($data);
			return $res;
		} else{
			DB::table('reason_category')->where('reason_id',$id)->delete();
			return 1;
		}
		
	}
	
	function getReasonCat($id){
		
		$res=DB::table('reason_category')->select('category_id')->where('reason_id',$id)->get()->toarray();
			$data = array();
		foreach($res as $row){
			$data[]=$row->category_id;
		}
		return $data;
	}
	
    public static function productDetails($prd_id){
        	$res=Products::where('id', '=',$prd_id)->first();
        	return $res;
    }
    
    public static function productsFirstCatData($prd_id){
        	$res=ProductCategories::
        	    select('categories.*')
                ->where('product_categories.product_id', '=',$prd_id)
                ->join('categories','product_categories.cat_id','categories.id')
                ->first();
        	return $res;
    }
    
        public function ratings()
        {
        $data=$this->hasMany("App\ProductRating","product_id");
        return $data;
        }
   public static function meterialname($id){
      
           $dd=DB::table('materials')->where('id',$id)->first();
           if($dd){
               return $dd->name;
           }
           
    
	   
	}
   
   public static function brandDescription($brand,$type){
       if($type==0){
           $description=Brands::select('description')
            ->where('isdeleted',0)
           ->where('id',$brand)
           ->first();
           if($description){
               return $description->description;
           }
           
       } else{
          $description=Brands::select('description')
            ->where('isdeleted',0)
           ->where('name',$brand)
           ->first();
           if($description){
               return $description->description;
           } 
       }
	   
	}
	
	 public static function categoryDescription($cat_id){
       if($cat_id!=''){
           $description=Category::select('description')->where('id',$cat_id)->first();
           if($description){
               return $description->description;
           }
           
       }
	   
	}
	
	public static function productcategoryDescription($prd_id){
	    $cats=ProductCategories::select('categories.description')
	    ->join('categories','categories.id','product_categories.cat_id')
	    ->where('product_categories.product_id',$prd_id)
	    ->where('categories.description','!=','')
	    ->first();
	    if($cats){
	         return $cats->description;
	    }
	  
	}
	public static function ofr_per($price,$spcl_price){
	   	$default_price=intval($price);
		$newPrice=intval($spcl_price);
		$offer_percentage=ceil(($newPrice*100)/$default_price);
		return $offer_percentage;
	}
	
		public static function Iscolorrequires($prd_id){
		$obj=new ProductAttributes();
		$data=$obj->where('product_id',$prd_id);
	$data=$data->where('color_id','!=',0);
		$data=$data->get()->toarray();
		if(sizeof($data)>0){
			return 1;
		} else{
			return 0;
		}
	}
		public static function Issize_requires($prd_id){
		$obj=new ProductAttributes();
		$data=$obj->where('product_id',$prd_id);
			$data=$data->where('size_id','!=',0);
		
		$data=$data->get()->toarray();
		if(sizeof($data)>0){
			return 1;
		} else{
			return 0;
		}
	}
	public static function Iscolor_size_requires($prd_id){
		$obj=new ProductAttributes();
		$data=$obj->where('product_id',$prd_id);
		$data=$data->get()->toarray();
		if(sizeof($data)>0){
			return 1;
		} else{
			return 0;
		}
	}
	
	public static function getFirstattrId($table,$prd_id){
		$data=(new self)->getProductsAttributes($table,0,$prd_id);
		if($table=='Sizes'){
			if(sizeof($data)==1){
			return $data[0]['size_id'];
		} else{
			return 0;
		}
		} else{
			if(sizeof($data)==1){
			return $data[0]['color_id'];
		} else{
			return 0;
		}
		}
		
		
	}
		public static function getcolorNameAndCode($table='Colors',$id=0){
		    	$name=Colors::select('name','color_code','color_image')->where('id',$id)->first();
			if($name){
			   	return $name; 
			}
		}
		public static function getSizeName($id=0){
		    	$name=Sizes::select('name')->where('id',$id)->first();
			if($name){
			   	return $name->name; 
			}
		}
		public static function getcolorName($id=0){
		    	$name=Colors::select('name')->where('id',$id)->first();
			if($name){
			   	return $name->name; 
			}
		}
	public static function getAttrName($table='Colors',$id=0){
		if($table=='Colors'){
			$name=Colors::select('name','color_code')->where('id',$id)->first();
			if($name){
			   	return $name->name; 
			}
	
		} else{
			$name=Sizes::select('name')->where('id',$id)->first();
            if($name){
            	   	return $name->name; 
            	}
		}
		
	}
	
	public static function productBrand($id){
		if($id!=''){
			$name=Brands::select('name')->where('id',$id)->first();
			if($name){
			   	return $name->name; 
			}
	
		} else{
			return "Unkonwn";
		}
		
	}
	
	public static function userRatingOnproduct($rating){
	     $html='<div class="rating"><span class="rating-total">';
           switch($rating){
               
               
               case 1;
               
                    $html.='<span class="fa fa-star-o checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
               break;
               
               case 2;
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
               break;
               
               case 3;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
               break;
               
               case 4;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
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
	public static function productRatings($id){
	      $final_rating=0;
	   $ratings=DB::table('product_rating')
	   ->where('product_id',$id)
	   ->where('isActive',1)->get();
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
		public static function productRvws($id){
	      $final_rating=0;
	   $ratings=DB::table('product_rating')
	   ->where('product_id',$id)
	   ->where('review','!=','')
	   ->where('isActive',1)->get();
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
	public static function productRatingCounter($id){
	  $final_rating=self::productRatings($id);
	   $html='<div class="rating"><span class="rating-total">';
	   switch($final_rating){
               case 0;
            
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
          
   
               break;
               
               case 1;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
               break;
               
               case 2;
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
               break;
               
               case 3;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
               break;
               
               case 4;
               
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star checked"></span>';
                    $html.='<span class="fa fa-star-o"></span>';
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
	public static function productRating($id){
	   $res=DB::table('product_rating')
	   ->where('product_id',$id)
	   ->where('isActive',1)
	   ->get();
	return sizeof($res);
	}
	
	public static function getAllReview($id,$limit){
	  $res=DB::table('product_rating')->where('product_id',$id)->orderBy('id','desc')->where('isActive',1)->paginate($limit);
      return $res;
	     
	}
	
	public static function productReviews($id){
        $res=DB::table('product_rating')->where('isActive',1)->where('product_id',$id)->get();
        return sizeof($res);
	}
	
public static function offerPercentage($price,$spcl_price){
		$default_price=intval($price);
		$newPrice=intval($spcl_price);
		$cutPrice=$price-$newPrice;
		$offer_percentage=ceil(($cutPrice*100)/$default_price);
		return $offer_percentage;
	}
	

	function getProductsAttributes($table='Colors',$attr_id,$prd_id){
		$obj=new ProductAttributes();
		
			if($table=='Sizes'){
			$data=$obj->select('size_id','unisex_type');
			       
				if($attr_id!=0){
				$data=$data->where('color_id',$attr_id);
				 $data=$data->where('size_id','!=',0);
				}
				 else{
				     $data=$data->where('size_id','!=',0);
				     $data->where('qty','>',0);
					$data->groupBy('size_id');
			}
				
		} else{
			$data=$obj->select('color_id');
			 if($attr_id!=0){
				  $data=$data->where('size_id',$attr_id);
				  $data=$data->where('color_id','!=',0);
			} else{
			    $data=$data->where('color_id','!=',0);
			    $data->where('qty','>',0);
				$data->groupBy('color_id');
			}
				
		}
		
		if($prd_id!=0){
			$data=$data->where('product_id',$prd_id);
		} else{
			 $data=$data->where('product_id',$this->id);
		}
		
		 $data=$data->get()->toarray();
		
	return $data;
	}
	
public static function getProductsAttributesWsize($attr_id,$prd_id){
		$obj=new ProductAttributes();
		
		$data=$obj->select('women_size_id');
			       
				if($attr_id!=0){
				$data=$data->where('color_id',$attr_id);
				}
				 else{
				      $data=$data->where('women_size_id','!=',0);
					$data->groupBy('women_size_id');
			}
	
		
		 $data=$data->get()->toarray();
		
	return $data;
	}
		public static function getProductsAttributes2($table='Colors',$attr_id,$prd_id){
		$obj=new ProductAttributes();
		
			if($table=='Sizes'){
			$data=$obj->select('size_id','unisex_type');
			       
				if($attr_id!=0){
				$data=$data->where('color_id',$attr_id);
				}
				 else{
				      $data=$data->where('size_id','!=',0);
					$data->groupBy('size_id');
			}
				
		} else{
			$data=$obj->select('color_id');
			 if($attr_id!=0){
				  $data=$data->where('size_id',$attr_id);
			} else{
			     $data=$data->where('color_id','!=',0)->orderBy('id','asc');
				$data->groupBy('color_id');
			}
				
		}
		
		if($prd_id!=0){
			$data=$data->where('product_id',$prd_id);
		} else{
			 $data=$data->where('product_id',$this->id);
		}
		
		 $data=$data->get()->toarray();
		
	return $data;
	}
	function getProductsVendorInfo(){
		$name=Vendor::select('vendors.*','vendor_tax_info.*','vendor_company_info.name as company_name','vendor_company_info.address','vendor_company_info.state','vendor_company_info.city as company_city','vendor_company_info.pincode','vendor_company_info.invoice_address','vendor_company_info.invoice_logo')
		->join('vendor_tax_info','vendor_tax_info.vendor_id','vendors.id')
		->join('vendor_company_info','vendor_company_info.vendor_id','vendors.id')
		->where('vendors.id',$this->vendor_id)->first();
		return $name;
	}
	function getProductsVendor(){
		$name=Vendor::select('public_name')->where('id',$this->vendor_id)->first();
		return $name->public_name;
	}
	

public static function getProductDetailUrl($name,$id,$size_id=0,$color_id=0){
	
	
	//DB::table('product_categories')->where('product_id',$id);
	
		$res=ProductCategories::select('cat_id')->where('product_id',$id)->orderBy('cat_id','asc')->limit(3)->get()->toarray();
		
		$rcat_name=$cat_name=$scat_name='';
		$i=1;
		foreach($res as $row){
		    $cat_data=Category::where('id',	$row['cat_id'])->first();
			
			if($i==1)
				$rcat_name=strtolower($cat_data->name);
			if($i==2)
				$cat_name=strtolower($cat_data->name);
			if($i==3)
				$scat_name=strtolower($cat_data->name);
			
			
			$i++;
		}
		$cat_name= preg_replace('/\s+/', '-', $cat_name);
		$rcat_name= preg_replace('/\s+/', '-', $rcat_name);
		$scat_name= preg_replace('/\s+/', '-', $scat_name);
		
		$prd_name=strtolower($name);
		$prd_name= preg_replace('/\s+/', '-', $prd_name);
		$prd_name =preg_replace('/[^A-Za-z0-9\-]/', '', $prd_name);
		$prd_id=$id."~~~".$size_id."~~~".$color_id;
// 		echo $rcat_name.$cat_name.$scat_name.$prd_name.base64_encode($prd_id); die;
		switch(count($res))
		{
			case 3:
				return route('scat_p_detail', [$rcat_name,$cat_name,$scat_name,$prd_name,base64_encode($prd_id)]);
				break;
			case 2:
				return route('cat_p_detail', [$rcat_name,$cat_name,$prd_name,base64_encode($prd_id)]);
				break;
			case 1:
				return route('rcat_p_detail', [$rcat_name,$prd_name,base64_encode($prd_id)]);
				break;
			default:
				return route('p_detail', [$prd_name,base64_encode($prd_id)]);
				break;
		}
		
	}
	
	

	
	function updatePrice($arr,$prd_id){
		
		$res=Products::where('id', '=',$prd_id)
		->update(
		[
		'price' =>intval($arr['price']),
		'spcl_price' => $arr['spcl_price'],
		'sample_price' =>intval($arr['sample_price']),
		'spcl_from_date' =>($arr['spcl_from_date']!='')?date("Y-m-d", strtotime($arr['spcl_from_date'])):null,
		'spcl_to_date' =>($arr['spcl_to_date']!='')?date("Y-m-d", strtotime($arr['spcl_to_date'])):null,
		'tax_class' => $arr['tax_class']
		]);
		return $res;
	}
	
function updateInfo($arr,$prd_id){
    
    // if(arr['status']==0){
    //     DB::table('products')->where('id',$prd_id)->update(
    //         array('status',0)
    //         );
    // }
			$data=	[
            'name' => $arr['name'],
            'short_description' => $arr['short_description'],
            'long_description' => $arr['long_description'],
            'sku' => $arr['sku'],
            //'gtin' => $arr['gtin'],
            'product_code' => $arr['product_code'],
            'weight' => $arr['weight'],
            'height' => $arr['height'],
            'length' => $arr['length'],
            'width' => $arr['width'],
            'prd_sts' => $arr['status'],
            'visibility' => $arr['visibility']
		];
		if (array_key_exists("default_image",$arr))
				{
				$data=[
		'default_image' => $arr['default_image']
		];	
                
			}
			
			if (array_key_exists("zoom_image",$arr))
				{
				$data=[
		'zoom_image' => $arr['zoom_image']
		];	
                
			}
		$res=Products::where('id', '=',$prd_id)
                ->update(
                $data
                );
		return $res;
	}
	
	function updatemetaInfo($arr,$prd_id){
		$res=Products::where('id', '=',$prd_id)
		->update(
		[
		'meta_title' => $arr['meta_title'],
		'meta_description' => $arr['meta_description'],
		'meta_keyword' => $arr['meta_keyword']
		]);
		return $res;
	}
	
	function updateStock($arr,$prd_id){
	    $prd_data=(new self)->productDetails($prd_id);
	    if($prd_data->product_type==1){
	               ProductAttributes::where('product_id',$prd_id)->delete();
	        	$res=ProductAttributes::insert(array(
					'product_id'=>$prd_id,
                    'size_id'=> 0,
                    'color_id'=>0,
                    'qty'=> $arr['qty'],
                    'price'=> 0,
				));
	    }
	    
	   
			
				
		$res=Products::where('id', '=',$prd_id)
		->update(
		[
		'manage_stock' => $arr['manage_stock'],
		'qty' => $arr['qty'],
		'qty_out' => $arr['qty_out'],
		'stock_availability' => $arr['stock_availability']
		]);
		return $res;
	}
	
	
	
	function updateFilters($arr,$prd_id){
	DB::table('product_filters')
      ->where('product_id',$prd_id)->delete();
      	if (array_key_exists("filter",$arr))
				{
				    $data=array();
					foreach($arr['filter'] as $key=>$value){
					    if($value!=''){
					        array_push($data,
					        array(
                                "product_id"=>$prd_id,
                                "filters_id"=>$key,
                                "filters_input_value"=>$value
					            )
					        );
					    }
					}
					DB::table('product_filters')
                      ->insert($data);
				}
      
	}
	
	function updateExtras($arr,$prd_id){
	    DB::table('product_reward_points')->where('product_id', '=',$prd_id)
		->update(
		[
		'reward_points' => @$arr['rewards_point']
		]);
		
	
				unset($arr["rewards_point"]);
			
		
		$res=Products::where('id', '=',$prd_id)
		->update($arr);
		return $res;
	}
	public static function prdImages($id){
		$obj=new ProductImages();
		$images=$obj->getImages($id);
		return $images;
	
	}
	
	public static function getsizeNameAndCode($table='Sizes',$id=0){
		    	$name=Sizes::select('name','id')->where('id',$id)->first();
			if($name){
			   	return $name; 
			}
		}
	
	
}
