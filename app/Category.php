<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use URL;
class Category extends Model
{
	
	
    protected $table = 'categories';
  
    protected $dates = ['created_at', 'updated_at'];

       public function parentCats()
        {
        return $this->hasMany('App\Category', 'id', 'parent_id');
        }
        
         public function AllparentCats()
        {
          return $this->parentCats()->with('AllparentCats');
        }


		public static function getparentCategory($categoryID){
			$parentID = $categoryID;

			$Categories = Category::select('id','name','parent_id')
					->where('id', $categoryID)
					->where('parent_id','!=',1)
					->first();
			if(!empty($Categories)){
				$parentID = $Categories->parent_id;

				//3rd level parent cat check
				$SCategories = Category::select('id','name','parent_id')
					->where('id', $parentID)
					->where('parent_id','!=',1)
					->first();

				if(!empty($SCategories)){
					$parentID = $SCategories->parent_id;
				}
			}

			return $parentID;
		}
        
     public static function list_categories($categories)
        {
          $data = [];
			
          foreach($categories as $category)
          {
            $data = [
                'id' => $category->id,
              'name' => $category->name,
              'children' =>self::list_categories($category->AllparentCats),
            ];
          }
        
          return $data;
        }
        




         public static function list_categories_for_featured($categories)
        {
          $data = [];
        
          foreach($categories as $category)
          {
             
                  $data = [
                'id' => $category->id,
              'name' => $category->name,
              'children' =>self::list_categories_for_featured($category->AllparentCats),
            ];
             
            
          }
        
          return $data;
        }

            

                public static function array_flatten( $arr, $out=array() )  {
                	foreach( $arr as $item ) {
                		if ( is_array( $item ) ) {
                			$out = array_merge( $out, array_flatten( $item ) );
                		} else {
                			$out[] = $item;
                		}
                	}
                	return $out;
                }


        public function childrenCats()
        {
        return $this->hasMany('App\Category', 'parent_id', 'id')->where('isdeleted',0)->where('status',1);
        }
        
        public function allChildrenCats()
        {
        return $this->childrenCats()->with('allChildrenCats');
        }

    
    public function support_image($path,$name)
    {
        $path=Config::get('constants.Url.public_url').$path.'/'.$name;
        return  $image='<img src="'.$path.'" class="img-thumbnail" alt="'.$this->name.'" width="50" height="50">';
    }
	
		
		 public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }
    
    


	
public static	function getParentCategoriesById($parent_category_id,$parent){

if($parent_category_id!=0){
   
    $Categories = Category::select('id','name','parent_id')
					->where('id', $parent_category_id)
					
					->first();
					if($Categories){
					    	array_push($parent,$Categories->name);
				self::getParentCategoriesByNameId($Categories->parent_id,$parent);
					}
				
		
}
return $parent; 

	}
	public static	function getParentCategoriesByNameId($parent_category_id,$parent){

if($parent_category_id!=0){
   
    $Categories = Category::select('id','name','parent_id')
					->where('id', $parent_category_id)
					->first();
					array_push($parent,$Categories->name);
			$parent=self::getParentCategoriesByNameId($Categories->parent_id,$parent);
		return $parent; 
}


	}
	public static function getcats($parent_id){
	$Categories = Category::select('id','name','banner_image','cat_url')
					->where('isdeleted', 0)
					->where('status', 1)
					->orderBy('web_order', 'desc')
					->where('parent_id', $parent_id)
					->get()->toarray();
					return $Categories;
}

public static function getcatsFrontEnd($parent_id){
	$Categories = Category::select('id','name','banner_image','cat_url')
					->where('isdeleted', 0)
					->where('status', 1)
						->where('cat_shows_in_nav', 1)
					->orderBy('web_order', 'desc')
					->where('parent_id', $parent_id)
					->get()->toarray();
					return $Categories;
}

public static function getSubSubNavLinks($parent_id,$catname){
	$Categories =self::getcatsFrontEnd($parent_id);
			$str='';
			foreach ($Categories as $row) {
				$cat_name = preg_replace('/\s+/', '-',strtolower ($row['name']));
				$url=route('cat_wise', [$catname.'/'.$cat_name,base64_encode($row['id'])]);
				$str.='<li><a href="'.$url.'">'.ucwords($row['name']).'</a></li>';	
					
			}

			return $str;
					
}


public static function GetSubcategory($parentID){
	$cat_data = Category::select('categories.*')
	->where('categories.parent_id','=',$parentID)
	->where('categories.status','=',1)
	->where('categories.isdeleted','=',0)
	->get()->toArray();
	return $cat_data; 	
}

public static function getFooterSubSubNavLinks($parent_id,$catname){
	$Categories =self::getcatsFrontEnd($parent_id);
			$str='';
			foreach ($Categories as $row) {
				$cat_name = preg_replace('/\s+/', '-',strtolower ($row['name']));
				$url=route('cat_wise', [$catname.'/'.$cat_name,base64_encode($row['id'])]);
				$str.='<li><a href="'.$url.'">'.ucwords($row['name']).'</a></li>';	
			}

			return $str;
					
}


public static function getSubNavLinks($parent_id,$rcatname){
	$Categories =self::getcatsFrontEnd($parent_id);
					$str='<div class="row justify-content-start">';
					foreach ($Categories as $row) {
					$cat_name = preg_replace('/\s+/', '-',strtolower ($row['name']));
						$url=route('cat_wise', [$rcatname.'/'.$cat_name,base64_encode($row['id'])]);
						$str.='<li class="col-lg-6 col-sm-6 right_line">';
						$str.='<ul>';
						$str.='<li class="dropdown-header"><a href="'.$url.'">'.ucwords($row['name']).'</a></li>';	
						$str.=self::getSubSubNavLinks($row['id'],$rcatname.'/'.$cat_name);
						$str.='</ul>';
					$str.='</li>';							  
			}
                    
			return $str;
			$str='</div>';		
}


public static function getFootNavLinks($parent_id=1){
	$Categories =self::getcatsFrontEnd($parent_id);
					$str='';
	                
					foreach ($Categories as $row) {
						$cat_name = preg_replace('/\s+/', '-',strtolower ($row['name']));
						$url=route('cat_wise', [$cat_name,base64_encode($row['id'])]);
						
						$ss=self::getSubNavLinks($row['id'],$cat_name);
						
						if($ss!='')
						{

							$str.='<div class="col-6 col-sm-6 col-md-6 col-lg-6">';
							$str.='<div class="fotr-media-item">';
							$str.='<h4><a href="'.$url.'">'.ucwords($row['name']).'</a></h4>';							                 
							$str.='<div class="row">';
							$str.='<div class="col-6 col-sm-6 col-md-6 col-lg-6">';
							$str.='<ul class="list-unstyled fotr-menu">';						
							$str.=self::getFooterSubSubNavLinks($row['id'],$cat_name.'/'.$cat_name);                        
							$str.='</ul>';
							$str.='</div>';                           
							$str.='</div>';                   
							$str.='</div>';
							$str.='</div>';

						}else{						
							$str.='<div class="col-6 col-sm-6 col-md-6 col-lg-6">';
							$str.='<div class="fotr-media-item">';
							$str.='<h4><a href="'.$url.'">'.ucwords($row['name']).'</a></h4>';							                 
							$str.='</div>';
							$str.='</div>';
						}
						  
			$str.='</li>';
					
			}
			
			return $str;
}


public static function getNavLinks($parent_id=1){
	
	
		$Categories =self::getcatsFrontEnd($parent_id);
					$str='';
	                
					foreach ($Categories as $row) {
						$cat_name = preg_replace('/\s+/', '-',strtolower ($row['name']));
						$url=route('cat_wise', [$cat_name,base64_encode($row['id'])]);
						
						$ss=self::getSubNavLinks($row['id'],$cat_name);
						
						if($ss!='')
						{
						    $img_url=URL::to('/uploads/category/banner').'/'.$row['banner_image'];
						    
							$str.='<li class="nav-item dropdown position-static">';
							$str.='<a href="'.$url.'" class="nav-link dropdown-toggle rootCat rcat'.$row['id'].'" data-bs-toggle="dropdown">'.ucwords($row['name']).' <span class="caret"></span></a>';			
							$str.='<ul class="dropdown-menu p-4">';
							$str.='<div class="mega_menu">';
							$str.=self::getSubNavLinks($row['id'],$cat_name);
							$str.='</div>';
							
							/*$str.='<div class="col-sm-3">
							<li class="col-sm-12"><a href="'.$row['cat_url'].'">
							<img src="'.$img_url.'" class="img-thumbnail submenu-bnr"></a>
							</li>	
							</div>';
							*/
							$str.='</ul>';			
						}else{
							$str.='<li class="dropdown mega-dropdown">';
							$str.='<a href="'.$url.'" class="rootCat">'.ucwords($row['name']).'</a>';			
						}
						  
			$str.='</li>';
					
			}
			
			return $str;
					
}

public static function getOfferZoneNavLinks(){
	
	
		$Categories =DB::table('offer_categories')->get();
					$str='';
					foreach ($Categories as $row) {
					$cat_name = preg_replace('/\s+/', '-',strtolower ($row->offer_name));
					$cat_name =preg_replace('/[^A-Za-z0-9\-]/', '', $cat_name);
					$url=route('cat_offer_wise', [$cat_name,base64_encode($row->categories_id.'~~~~~'.$row->offer_zone_type.'~~~~~'.$row->offer_discount.'~~~~~'.$row->offer_name.'~~~~~'.$row->offer_below_above)]);
					$str.='<li class="col-sm-3">';
					$str.='<a href="'.$url.'" >'.ucwords($row->offer_name).' </a>';			
						
			$str.='</li>';
					
			}
			
			return $str;
					
}
}
