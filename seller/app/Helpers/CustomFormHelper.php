<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use Config;
use App\Colors;
use App\Sizes;
use App\ProductCategories;
use App\Helpers\CommonHelper;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
class CustomFormHelper
{

public static function getBarcode($string)
    {  
        $code='';
        if($string!=''){
             $code= '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($string, "C39+") . '" alt="'.$string.'"   />';
        }
       
        return  $code;
    }
    public static function form_builder($form_array)
    {

		$field_html='<div class="form-group">';
		$field_html.='<label for="exampleInputEmail1">'.$form_array['label'].'</label>';
			switch($form_array['type']){
				
				case 'color':
				$field_html.='<br><input name="'.$form_array['name'].'"  type="color" value="'.$form_array['value'].'"  id="'.$form_array['id'].'"/>';
				
				break;
				
					case 'text':
				$field_html.=' <input type="text" name="'.$form_array['name'].'"  '.$form_array['disabled'].' value="'.$form_array['value'].'" class="'.$form_array['classes'].'" id="'.$form_array['id'].'" placeholder="'.$form_array['placeholder'].'">';
				
				break;
			
			case 'text_required':
				$field_html.=' <input type="text" name="'.$form_array['name'].'"  '.$form_array['disabled'].' value="'.$form_array['value'].'" class="'.$form_array['classes'].'" id="'.$form_array['id'].'" placeholder="'.$form_array['placeholder'].'" required>';
				
				break;
				
				case 'textarea':
				$field_html.='<textarea name="'.$form_array['name'].'"  '.$form_array['disabled'].' class="'.$form_array['classes'].'" id="'.$form_array['id'].'">'.$form_array['value'].'</textarea>';
				
				break;
				
				case 'select':
						$field_html.='<select class="'.$form_array['classes'].'"  name="'.$form_array['name'].'">';
						$field_html.='<option value="">Select </option>';
							
								foreach($form_array['value'] as $row){
									$selected_id=($row->id==$form_array['selected'])?"selected":"";
												$field_html.='<option value="'.$row->id.'" '.$selected_id.'>'.$row->name.'</option>';
								}
						$field_html.='</select>';
										
									
				break;
				
    					case 'selectcustom':
						$field_html.='<select class="'.$form_array['classes'].'"  name="'.$form_array['name'].'" id="'.$form_array['id'].'" >';
						$field_html.='<option value="">Select </option>';
							
								foreach($form_array['value'] as $row){
									$selected_id=($row->name==$form_array['selected'])?"selected":"";
												$field_html.='<option value="'.$row->name.'" '.$selected_id.' data-id="'.$row->id.'">'.$row->name.'</option>';
								}
						$field_html.='</select>';
										
									
				break;
				
				
				case 'select_with_inner_loop':
						$field_html.='<select class="'.$form_array['classes'].'"  name="'.$form_array['name'].'">';
						$field_html.='<option value="">Select </option>';
						$field_html.=$form_array['value'];
						$field_html.='</select>';
										
									
				break;
				
				case 'select_with_inner_loop_for_filter':
				    	$field_html='<div class="form-group">';
						$field_html.='<select class="'.$form_array['classes'].'"  name="'.$form_array['name'].'" id="'.$form_array['id'].'">';
						$field_html.='<option value="">Select </option>';
						$field_html.=$form_array['value'];
						$field_html.='</select>';
										
									
				break;
				
				case 'radio':
					$field_html='<div class="form-group">';
		$field_html.='<label for="exampleInputEmail1">'.$form_array['label'].'</label>';
								$field_html.='<br>';
								$i=0;
					foreach($form_array['value'] as $row){
									$selected_id=($row->id==$form_array['selected'])?"checked":"";
								$field_html.='<input name="'.$form_array['name'].'" type="radio" class="with-gap '.$form_array['classes'].'" id="radio_'.$form_array['name'].$i.'"  value="'.$row->id.'" '.$selected_id.'><label for="radio_'.$form_array['name'].$i.'">'.$row->name.'</label> &nbsp;  &nbsp; <br>';
										
					    $i++;
					}
					
				
		
				break;
				
				case 'checkbox':
				break;
				
				case 'date':
				$field_html.=' <input type="text" name="'.$form_array['name'].'"  '.$form_array['disabled'].' value="'.$form_array['value'].'" class="'.$form_array['classes'].'" id="'.$form_array['id'].'" placeholder="'.$form_array['placeholder'].'">';
				break;
				
				case 'file':
					$field_html='<div class="boxinputfile">';
		$field_html.='<input type="file" name="'.$form_array['name'].'" id="'.$form_array['id'].'" class="'.$form_array['classes'].'" data-multiple-caption="{count} files selected" />';
					
				 $field_html.='<label for="file-5"><figure><i class="fa fa-upload"></i></figure> <span>Choose a file&hellip;</span></label>';
				break;
				
					case 'file_special':
						 $field_html='<div class="form-group">';
                        $field_html='<label>'.$form_array['label'].'</label><div class="boxinputfile">';
                        $field_html.='<input type="file" name="'.$form_array['name'].'" id="'.$form_array['id'].'" class="'.$form_array['classes'].'" data-multiple-caption="{count} files selected">';
                        $field_html.='<label for="'.$form_array['id'].'"><figure>';
                        $field_html.='<i class="fa fa-upload"></i>';
                        $field_html.='</figure>';
                        $field_html.='<span>Choose a file…</span></label>';
					
				break;

				case 'file_special_imagepreview':
					$field_html='<div class="form-group">';
				   $field_html='<label>'.$form_array['label'].'</label><div class="boxinputfile thumbnilimgPrw">';
				   $field_html.='<input type="file" name="'.$form_array['name'].'" id="'.$form_array['id'].'" onchange="'.$form_array['onchange'].'" class="'.$form_array['classes'].'" data-multiple-caption="{count} files selected">';
				   $field_html.='<label for="'.$form_array['id'].'"><figure>';
				   $field_html.='<i class="fa fa-upload"></i>';
				   $field_html.='</figure>';
				   $field_html.='<span>Choose a file…</span></label>';
				   $field_html.='<span id="'.$form_array['id'].'_preview"></span>';
			   
		   break;
				
				case 'number':
				$field_html.=' <input type="number" name="'.$form_array['name'].'" '.$form_array['disabled'].'  value="'.$form_array['value'].'" class="'.$form_array['classes'].'" id="'.$form_array['id'].'" min="'.@$form_array['min'].'" placeholder="'.$form_array['placeholder'].'">';
				break;
				
				
				case 'password':
				$field_html.=' <input type="password" name="'.$form_array['name'].'"  value="'.$form_array['value'].'" class="'.$form_array['classes'].'" id="'.$form_array['id'].'" placeholder="'.$form_array['placeholder'].'"maxlength="'.@$form_array['maxlength'].'">';
				break;
				
				
				case 'button':
					$field_html.='<input type="button" value="'.$form_array['value'].'" class="'.$form_array['classes'].'"  id="'.@$form_array['id'].'">';
				break;
				
				case 'checkbox_array_dynamic_name':
					$field_html.='<div class="demo-checkbox">';
					foreach($form_array['value'] as $row){
				      $field_html.='<input type="checkbox" '.$form_array['checked'].'  id="basic_checkbox_'.$row->id.'" class="filled-in" name="module_'.$row->id.'">';
				$field_html.='<label for="basic_checkbox_'.$row->id.'">'.$row->name.'</label>';
								}
					$field_html.='</div>';
				break;
				
				
				case 'submit':
				$field_html.='<input type="submit" value="'.$form_array['value'].'" class="'.$form_array['classes'].'"  id="'.@$form_array['id'].'">';
				break;
				
				case 'hidden':
					$field_html.=' <input type="hidden" name="'.$form_array['name'].'"  value="'.$form_array['value'].'" class="'.$form_array['classes'].'" id="'.$form_array['id'].'" placeholder="'.$form_array['placeholder'].'">';
				break;
				
			}
			$field_html.='</div>';
			return $field_html;

    }
	 public static function support_image($path,$name)
    {
		if($name!=''){
			  $path=Config::get('constants.Url.public_url').$path.'/'.$name;
		} else{
			 $path=Config::get('constants.Url.public_url').Config::get('constants.no_images');
		}
      
		
        return  $image='<img src="'.$path.'" class="img-thumbnail" alt="'.$name.'" width="40" height="40">';
    }
	public static function signature_pic($path,$name)
    {
		if($name!=''){
			  $path=Config::get('constants.Url.public_url').$path.'/'.$name;
		} else{
			 $path=Config::get('constants.Url.public_url').Config::get('constants.no_images');
		}
      
		
        return  $image='<img src="'.$path.'" class="img-thumbnail" alt="'.$name.'" width="70" height="60">';
    }
	
	public static function support_docs($path,$name)
    {
		if($name!=''){
			  $path=Config::get('constants.Url.public_url').$path.'/'.$name;
			  return  $file='<a target="_blank" href="'.$path.'" >'.$name.'</a>';
		} 
      
		
        
    }
	public static function getColorHtml($form_array,$selected_id)
    {   $form_array['selected']=$selected_id;
		$field_html=self::form_builder($form_array);
		return $field_html;
    }
	
	public static function getSizeHtml($form_array,$selected_id)
    {  
		$form_array['selected']=$selected_id;
		$field_html=self::form_builder($form_array);
		return $field_html;			
    }
	
	public static function getQtyHtml($form_array,$val)
    {   $form_array['value']=$val;
		$field_html=self::form_builder($form_array);
		return $field_html;			
    }
    
    public static function getPriceHtml($form_array,$val)
    {   $form_array['value']=$val;
		$field_html=self::form_builder($form_array);
		return $field_html;			
    }
	public static function getskuHtml($form_array,$val)
    {   $form_array['value']=$val;
		$field_html=self::form_builder($form_array);
		return $field_html;			
    }
	
	public static function getProductCategory($prd)
    {
		$data=array();
			$ProductCategories = ProductCategories::select('cat_id')->where('product_id',$prd)->get()->toarray();
			foreach($ProductCategories as $row){
				array_push($data,$row['cat_id']);
			}
		return $data;			
    }
	/*******************************/
	
	
	/*******************************/
	
	
}
