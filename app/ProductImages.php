<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use App\Products;
class ProductImages extends Model
{
	
	
    protected $table = 'product_images';
  
   function updateVDRProductImages($arr,$prd_id,$vdr_id){
		$data=array();
	
		 ProductImages::where('product_id',$prd_id)->delete();
		foreach($arr as $row){
			array_push($data,array('product_id'=>$prd_id, 'image'=> $row));
		}
		
		
$res=ProductImages::insert($data);
		return $res;
	}
	
	function updateImages($arr,$prd_id){
		$data=array();
	
		 ProductImages::where('product_id',$prd_id)->delete();
		foreach($arr as $row){
			array_push($data,array('product_id'=>$prd_id, 'image'=> $row));
		}
		
		
$res=ProductImages::insert($data);
		return $res;
	}
	
	function getImages($prd_id){
		 $res = ProductImages::select('image','id')
					->where('product_id',$prd_id)->orderBy('id', 'DESC')
                     ->get()
					 ->toarray();
		return $res;
	}
	
	
	function getImagesHtml($prd_id){
		$data=$this->getImages($prd_id);
		$html='';
		$i=0;
		foreach($data as $row){
				$path=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$row['image'];
				
			$html.='<div class="imageuploadify-container" style="margin-left: 8px;">';
			$html.='<button type="button" class="btn btn-danger glyphicon glyphicon-remove removeImage" data="'.$i.'"></button>';
			$html.='<img src="'.$path.'">';
			$html.='</div>';
			$i++;
		}
		return $html;
	}
	
	public static function getConfiguredImages($prd_id,$color_id,$type=1){
        if($type){
       $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_thumb_image').'/';
          } else{
                $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/';
        }
            
            $url='';
            
		 $res = DB::table('product_configuration_images')
            ->select(
            DB::raw("CONCAT('$url',product_configuration_images.product_config_image) AS image")
            )
               ->where('product_id',$prd_id)
                ->where('color_id',$color_id)
                ->get()->toArray();
                
        $res1=array();
        $i=0;
        foreach($res as $row){
            
            $res1[]=array('image'=>$row->image,'id'=>$i++);
        }
        
        return $res1;
	}
		public static function getConfiguredImagesAPI($prd_id,$color_id){
        $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/';
            
		 $res = DB::table('product_configuration_images')
            ->select(
            DB::raw("CONCAT('$url',product_configuration_images.product_config_image) AS image")
            )
               ->where('product_id',$prd_id)
                ->where('color_id',$color_id)
                ->get();
		return $res;
	}
}
