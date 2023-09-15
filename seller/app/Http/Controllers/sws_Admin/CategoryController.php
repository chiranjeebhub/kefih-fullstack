<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\Category;
use App\Sizechart;
use Redirect;
use Validator;
use DB;
use Config;
use App\Helpers\MsgHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FIleUploadingHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CatExport;
use URL;
class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth:vendor');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     public function cat_export(Request $request)
    {
			$parameters=$request->str;	
		$status=$request->status;
		return Excel::download(new CatExport($parameters,$status), 'Cats'.date('d-m-Y H:i:s').'.csv');	
    }
    public function lists(Request $request)
    {
        $parameters=$request->str;	
        $status=$request->status;	
        
        $export=URL::to('admin/cat_export');
        if($parameters!=''){
          $export=URL::to('admin/cat_export_search_str').'/'.$parameters;
        }
        if($parameters!='' && $status!=''){
          $export=URL::to('admin/cat_export_search').'/'.$parameters.'/'.$status;
        } 
        
        $page_details=array(
                    "Title"=>"Category List",
                    "Box_Title"=>"Category(s)",
                    "search_route"=>URL::to('admin/cat_search'),
                    "reset_route"=>route('categories'),
                    "export"=>$export,
          );
          $loginuser=auth()->guard('vendor')->user()->id;
          $categoryname=DB::table('vendor_categories')->where('vendor_id',$loginuser)->first(); 
          $categoryarray=explode(',',$categoryname->selected_cats);
          $Categories= Category::WhereIn('id',$categoryarray)->where('isdeleted', 0)->where('parent_id', '!=',0);
       
        if($parameters=='all')
        {
          $parameters='';
        }
        if($parameters!=''){
          $Categories=$Categories
            ->where('categories.name','LIKE',$parameters.'%');
        } 
        
        if($status!=''){
          $Categories=$Categories
            ->where('categories.status','=',$status);
        } 
        
        $Categories=$Categories->orderBy('id', 'DESC')->paginate(100);
        
        
        //$viewTree=$this->viewTree();
        
        
      
        return view('admin.categories.category.old_list',['Categories'=>$Categories,'page_details'=>$page_details,'status'=>$status,'vendor_id'=>$loginuser]);
        //return view('admin.categories.category.list',['viewTree'=>$viewTree,'page_details'=>$page_details,'status'=>$status]);
    }
	
	function viewTree()
    {
		$query= Category::where('isdeleted', 0)->where('parent_id', '!=',0)->get()->toArray();
		
		if (count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
			$this->categories = array();
            $this->categories = $data;
        }
		
        $output = "";
        for ($i = 0; $i < count($this->categories); $i++) {
            if ($this->categories[$i]["parent_id"]!="0")
            {
                $edit_url=route('edit_category', base64_encode($this->categories[$i]["id"]));
				$logo=asset('uploads/category/banner/'.$this->categories[$i]["logo"]);
				$status_url=route('cat_sts',[base64_encode($this->categories[$i]["id"]),base64_encode($this->categories[$i]["status"])] );
				$delete_url=route('delete_category', base64_encode($this->categories[$i]["id"]));
				
				$output .='<li class="dd-item" data-id="r'.$i.$this->categories[$i]["id"].'"><div class="dd-handle">Category Id ('.$this->categories[$i]["id"].') '.ucwords($this->categories[$i]["name"]).'<span class="pull-right set_pos1"><span style="margin:0 10px">';
				
				if($this->categories[$i]["logo"]!='')
				{
					//$output .='<a href="'.$logo.'" data-toggle="lightbox" data-gallery="multiimages" data-title="'.ucwords($this->categories[$i]["name"]).'"><img src="'.$logo.'" alt="gallery" class="all studio _cat_small35" /> </a>';	
        }
        
        if($this->categories[$i]["cat_compare"]!=0)
				{
          $compare="Compare: Enabled";
          $output .=$compare;
        }

        $output .='<a href="javascript:void(0)" class="btn btn-primary btn-xs showcategorySizes" cat_id="'.$this->categories[$i]["id"].'">Size</a>';
				$output .='<a href="javascript:void(0)" class="btn btn-primary btn-xs showcategoryColors" cat_id="'.$this->categories[$i]["id"].'">Color</a>';
								
				
				$output .='<a href="'.$status_url.'" onclick = "if (! confirm("Do you want to change status ?")) { return false; }"><span class="label label-';
				$output .=($this->categories[$i]["status"]==1)?"success":"danger";
				$output .='">';
				$output .=($this->categories[$i]["status"]==1)?"Active":"De-Active";
				$output .='</span> </a><span class="its_valid-'.$this->categories[$i]["id"].'">';
				
				if($this->categories[$i]["status"]==1) {$status= "<i style='color:green' class='fa fa-check'></i>"; }else {$status= "<i style='color:red' class='fa fa-times'></i>";}
							
				$output .=$status.'</span></span> <span>
				<a style="margin:0 10px;" href='.$edit_url.' title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				<a href="'.$delete_url.'" onclick = "if (! confirm("Do you want to delete ?")) { return false; }" title="Delete"><i class="fa fa-trash-o"></i></a></span></span></div><ol class="dd-list">';
                $output .= $this->getAllChildren($this->categories[$i]["id"]);
                $output .='</ol></li>';
            }
        }
        return $output;
    }
	
	function getAllChildren($parent_id)
    {
        $output = "";
        for ($i = 0; $i < count($this->categories); $i++) {
            if ($this->categories[$i]["parent_id"] == $parent_id)
            {
                $edit_url=route('edit_category', base64_encode($this->categories[$i]["id"]));
				$logo=asset('uploads/category/banner/'.$this->categories[$i]["logo"]);
				$status_url=route('cat_sts',[base64_encode($this->categories[$i]["id"]),base64_encode($this->categories[$i]["status"])] );
				$delete_url=route('delete_category', base64_encode($this->categories[$i]["id"]));
				
				$output .='<li class="dd-item" data-id="xr'.$i.'"><div class="dd-handle">Category Id ('.$this->categories[$i]["id"].') '.ucwords($this->categories[$i]["name"]). '<span class="pull-right set_pos1"><span style="margin:0 10px">';
				
				if($this->categories[$i]["logo"]!='')
				{
					//$output .='<a href="'.$logo.'" data-toggle="lightbox" data-gallery="multiimages" data-title="'.ucwords($this->categories[$i]["name"]).'"><img src="'.$logo.'" alt="gallery" class="all studio _cat_small35" /> </a>';	
        }

        if($this->categories[$i]["cat_compare"]!=0)
				{
          $compare="Compare: Enabled";
          $output .=$compare;
        }

        $output .='<a href="javascript:void(0)" class="btn btn-primary btn-xs showcategorySizes" cat_id="'.$this->categories[$i]["id"].'">Size</a>';
				$output .='<a href="javascript:void(0)" class="btn btn-primary btn-xs showcategoryColors" cat_id="'.$this->categories[$i]["id"].'">Color</a>';
				
        
				$output .='<a href="'.$status_url.'" onclick = "if (! confirm("Do you want to change status ?")) { return false; }"><span class="label label-';
				$output .=($this->categories[$i]["status"]==1)?"success":"danger";
				$output .='">';
				$output .=($this->categories[$i]["status"]==1)?"Active":"De-Active";
				$output .='</span> </a><span class="its_valid-'.$this->categories[$i]["id"].'">';
				
				if($this->categories[$i]["status"]==1) {$status= "<i style='color:green' class='fa fa-check'></i>"; }else {$status= "<i style='color:red' class='fa fa-times'></i>";}
						
				$output .=$status.'</span></span> <span>
				<a style="margin:0 10px;" href='.$edit_url.' title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				<a href="'.$delete_url.'" onclick = "if (! confirm("Do you want to delete ?")) { return false; }" title="Delete"><i class="fa fa-trash-o"></i></a></span></span></div><ol class="dd-list">';
                $output .= $this->getAllChildren($this->categories[$i]["id"]);
                $output .='</ol></li>';
            }
        }
        return $output;
    }
	
	public function multi_delete_cat(Request $request)
    {
			$input=$request->all();
			Category::whereIn('id', $input['cat_id'])
    ->update([
        'isdeleted' =>1
    ]);
	   Category::onChangeStsDelete($input['cat_id'],1);
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('categories');
			
    }
	  public function add_sizechart(Request $request){
      $id=base64_decode($request->id);

	    $page_details=array(
       "Title"=>"Add Category",
	     "Method"=>"1",
       "Box_Title"=>"Add size chart",
       "Action_route"=>route('add_sizechart', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           "size_chart_file_field"=>array(
			    'label'=>'Size Chart',
                'type'=>'file_special_imagepreview',
                'name'=>'size_chart',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
           ),
           "submit_button_field"=>array(
            'label'=>'',
                    'type'=>'submit',
                    'name'=>'submit',
                    'id'=>'submit',
                    'classes'=>'btn btn-danger',
                    'placeholder'=>'',
                    'value'=>'Save'
              ),
               
			
         )
       )
     );
	 if ($request->isMethod('post')) {

    
		        $input=$request->all();
              $request->validate([
                   'size_chart' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.size_chart_min').'|max:'.Config::get('constants.size.size_chart_max').''
              ]);
          $file_name='';
          $file_name2='';
            $file_name3='';
              $file_name4='';
        if ($request->hasFile('size_chart')) {
							  $size_chart = $request->file('size_chart');
					$destinationPath3 =  Config::get('constants.uploads.size_chart');
					$file_name3=$size_chart->getClientOriginalName();


					$file_name3= FIleUploadingHelper::UploadImage($destinationPath3,$size_chart,$file_name3);
					if($file_name3==''){
					MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
					return Redirect::back();
					}
				  }
          $uploadsizechart=Sizechart::where('vendor_id',auth()->guard('vendor')->user()->id)->where('category_id',$id)->get()->count();
          if($uploadsizechart>0)
          {
            Sizechart::where('vendor_id',auth()->guard('vendor')->user()->id)->where('category_id',$id)
                    ->update(['sizechart' => $file_name3]);
          }else{
              $sizechart['vendor_id']=auth()->guard('vendor')->user()->id;
              $sizechart['category_id']=$id;
              $sizechart['sizechart']=$file_name3;
              Sizechart::create($sizechart);
          }
          return redirect()->route('categories');
	 }
    return view('admin.categories.category.sizechatform',['page_details'=>$page_details,'categoryid'=>$id]);
    }
		
   public function add(Request $request){
	  $Categories = Category::select('id','name')
					->with('grandchildren')
					->where('isdeleted', 0)
					->where('parent_id', 0)
					->orderBy('id', 'DESC')
					->get();

		
	    $page_details=array(
       "Title"=>"Add Category",
	     "Method"=>"1",
       "Box_Title"=>"Add Category",
       "Action_route"=>route('add_category'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "cat_name"=>array(
				'label'=>'Category Name',
				'type'=>'text',
				'name'=>'name',
				'id'=>'name',
				'classes'=>'form-control',
				'placeholder'=>'Name',
				'value'=>old('name'),
				'disabled'=>''
           ),
		   "select_field"=>array(
              'label'=>'Select Parent',
            'type'=>'select_with_inner_loop',
            'name'=>'parent_id',
            'id'=>'parent_id',
            'classes'=>'custom-select form-control',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChilds(0,'',old('parent_id'))
           ),
		   "select_compare"=>array(
            'label'=>'Compare',
            'type'=>'select',
            'name'=>'cat_compare',
            'id'=>'cat_compare',
            'classes'=>'custom-select form-control',
            'placeholder'=>'Name',
			'selected'=>'2',
            'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"2",
							"name"=>"No",
							)
							)
           ),
		    "cat_url"=>array(
              'label'=>'Category Url',
            'type'=>'text',
            'name'=>'cat_url',
            'id'=>'name',
            'classes'=>'cat_url form-control',
            'placeholder'=>'Url',
            'value'=>old('cat_url'),
				'disabled'=>''
           ),
             "logo_file_field"=>array(
			   'label'=>'Logo Image',
              'type'=>'file_special_imagepreview',
              'name'=>'logo_image',
              'id'=>'file-1',
              'classes'=>'inputfile inputfile-4',
              'placeholder'=>'',
              'value'=>'',
              'onchange'=>'image_preview(event)'
             ),
               "banner_file_field"=>array(
			    'label'=>'Banner Image',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "size_chart_file_field"=>array(
			    'label'=>'Size Chart',
                'type'=>'file_special_imagepreview',
                'name'=>'size_chart',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
                "app_icon_file_field"=>array(
			          'label'=>'App icon',
                'type'=>'file_special_imagepreview',
                'name'=>'app_icon',
                'id'=>'file-5',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
			  "commission_rate"=>array(
				'label'=>'Commission (%)',
				'type'=>'text',
				'name'=>'commission_rate',
				'id'=>'commission_rate',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('commission_rate'),
				'disabled'=>''
           ),
				"tax_rate"=>array(
				'label'=>'Tax (%)',
				'type'=>'text',
				'name'=>'tax_rate',
				'id'=>'tax_rate',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('tax_rate'),
				'disabled'=>''
           ),
                 "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            ),
            "cat_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>old('description'),
							'disabled'=>''
							
			),
			 "cat_return_description_field"=>array(
							'label'=>'Return Description',
							'type'=>'textarea',
							'name'=>'return_description',
							'id'=>'return_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>old('return_description'),
							'disabled'=>''
							
			),
			 "cat_cancel_description_field"=>array(
							'label'=>'Cancel Description',
							'type'=>'textarea',
							'name'=>'cancel_description',
							'id'=>'cancel_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>old('cancel_description'),
							'disabled'=>''
							
			),
			
			 "images"=>array(
				  'logo_image'=>'',
                  'banner_image'=>'',
				    'cats_list'=>$Categories,
				     'app_icon'=>'',
            )
         )
       )
     );
	 if ($request->isMethod('post')) {
		        $input=$request->all();
      
            $request->validate([
            //   'name' => 'required|unique:categories,name,1,isdeleted|max:255',
              'name' => 'required|max:255',
              'cat_url' => 'required|max:255',
			  'tax_rate' => 'required|numeric',
			  'description' => 'max:60000',
            'return_description' => 'max:60000',
            'cancel_description' => 'max:60000',
			  'commission_rate' => 'required|numeric',
        'parent_id' => 'required|max:255',        
             
        'logo_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
        'app_icon' => 'mimes:png|min:'.Config::get('constants.size.app_icon_min').'|max:'.Config::get('constants.size.app_icon_max').'',
        'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').'',
        'size_chart' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.size_chart_min').'|max:'.Config::get('constants.size.size_chart_max').''
             

                
            ]);
			$file_name='';
			$file_name2='';
				$file_name3='';
					$file_name4='';
			
				  if ($request->hasFile('logo_image')) {
						$logo_image = $request->file('logo_image');
						$destinationPath =Config::get('constants.uploads.cat_logo');
						$file_name=$logo_image->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name,130,64);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							return Redirect::back();
							}
				  }
				    if ($request->hasFile('app_icon')) {
		
				$app_icon = $request->file('app_icon');
				$destinationPath =Config::get('constants.uploads.cat_logo');
				$file_name=$app_icon->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$app_icon,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		
        $file_name4= $file_name;
  }
			
		 if ($request->hasFile('banner_image')) {
							  $banner_image = $request->file('banner_image');
					$destinationPath2 =  Config::get('constants.uploads.cat_banner');
					$file_name2=$banner_image->getClientOriginalName();


					$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,1920,796);
					if($file_name2==''){
					MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
					return Redirect::back();
					}
				  }
				  
				 
 if ($request->hasFile('size_chart')) {
							  $size_chart = $request->file('size_chart');
					$destinationPath3 =  Config::get('constants.uploads.size_chart');
					$file_name3=$size_chart->getClientOriginalName();


					$file_name3= FIleUploadingHelper::UploadImage($destinationPath3,$size_chart,$file_name3);
					if($file_name3==''){
					MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
					return Redirect::back();
					}
				  }
          
	  
	    $Category = new Category;
		$Category->parent_id = $input['parent_id'];
		$Category->name = $input['name'];
		$Category->cat_url = $input['cat_url'];
		$Category->cat_compare = $input['cat_compare'];
        $Category->description = $input['description'];
        $Category->cancel_description = $input['cancel_description'];
        $Category->return_description = $input['return_description'];
		$Category->tax_rate = $input['tax_rate'];
		$Category->commission_rate = $input['commission_rate'];
		
        $Category->logo = $file_name;
        $Category->banner_image = $file_name2;
         $Category->app_icon = $file_name4;
        $Category->size_chart = $file_name3;
      
      /* save the following details */
      if($Category->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
     return Redirect::back();
	 }
    return view('admin.categories.category.form',['page_details'=>$page_details]);
   }





   public function edit(Request $request)
   {
           $id=base64_decode($request->id);

           $Category_detail = Category::where('id', $id)->first();
		   
		    $page_details=array(
       "Title"=>"Edit Category",
	    "Method"=>"2",
       "Box_Title"=>"Edit Category",
       "Action_route"=>route('edit_category', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "cat_name"=>array(
              'label'=>'Category Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Category_detail->name,
			'disabled'=>''
           ),
		   "select_field"=>array(
              'label'=>'Select Parent',
            'type'=>'select_with_inner_loop',
            'name'=>'parent_id',
            'id'=>'parent_id',
            'classes'=>'custom-select form-control',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChilds(0,'',$Category_detail->parent_id)
           ),
		   "select_compare"=>array(
            'label'=>'Compare',
            'type'=>'select',
            'name'=>'cat_compare',
            'id'=>'cat_compare',
            'classes'=>'custom-select form-control',
            'placeholder'=>'Name',
			'selected'=>$Category_detail->cat_compare,
            'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"2",
							"name"=>"No",
							)
							)
           ),
		    "cat_url"=>array(
              'label'=>'Category Url',
            'type'=>'text',
            'name'=>'cat_url',
            'id'=>'name',
            'classes'=>'cat_url form-control',
            'placeholder'=>'Url',
            'value'=>$Category_detail->cat_url,
			'disabled'=>''
           ),
		   "tax_rate"=>array(
					'label'=>'Tax (%)',
				'type'=>'text',
				'name'=>'tax_rate',
				'id'=>'tax_rate',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>$Category_detail->tax_rate,
				'disabled'=>''
           ),
		    "commission_rate"=>array(
				'label'=>'Commission (%)',
				'type'=>'text',
				'name'=>'commission_rate',
				'id'=>'commission_rate',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>$Category_detail->commission_rate,
				'disabled'=>''
           ),
             "logo_file_field"=>array(
			   'label'=>'Logo Image',
              'type'=>'file_special',
              'name'=>'logo_image',
              'id'=>'file-1',
              'classes'=>'inputfile inputfile-4',
              'placeholder'=>'',
              'value'=>''
             ),
             "app_icon_file_field"=>array(
			    'label'=>'App icon',
                'type'=>'file_special',
                'name'=>'app_icon',
                'id'=>'file-5',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>''
                
               ),
               "banner_file_field"=>array(
			    'label'=>'Banner Image',
                'type'=>'file_special',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>''
                
               ),
               "size_chart_file_field"=>array(
			    'label'=>'Size Chart',
                'type'=>'file_special',
                'name'=>'size_chart',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>''
                
               ),
                 "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            ),
			  "cat_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Category_detail->description,
							'disabled'=>''
			),
			 "cat_return_description_field"=>array(
							'label'=>'Return Description',
							'type'=>'textarea',
							'name'=>'return_description',
							'id'=>'return_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Category_detail->return_description,
							'disabled'=>''
							
			),
			 "cat_cancel_description_field"=>array(
							'label'=>'Cancel Description',
							'type'=>'textarea',
							'name'=>'cancel_description',
							'id'=>'cancel_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Category_detail->cancel_description,
							'disabled'=>''
							
			),
			 "images"=>array(
                        'logo_image'=>$Category_detail->logo,
                         'app_icon'=>$Category_detail->app_icon,
                        'banner_image'=>$Category_detail->banner_image,
                        'size_chart'=>$Category_detail->size_chart
            )
         )
       )
     );
	 
	 if ($request->isMethod('post')) {
				$input=$request->all();
				$id=base64_decode($request->id);
				$Category = Category::find($id);
         $request->validate([
				// 'name' => 'required|unique:categories,name,'.$id.',id,isdeleted,0',
					'name' => 'required|max:255',
				'cat_url' => 'required|max:255',
				'description' => 'max:60000',
                    'cancel_description' => 'max:60000',
                    'return_description' => 'max:60000',
				            'parent_id' => 'required|max:255',
                    'tax_rate' => 'required|numeric',
                    'commission_rate' => 'required|numeric',
                    'logo_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
                    'app_icon' => 'mimes:png|min:'.Config::get('constants.size.app_icon_min').'|max:'.Config::get('constants.size.app_icon_max').'',
                    'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').'',
                    'size_chart' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.size_chart_min').'|max:'.Config::get('constants.size.size_chart_max').''
             
         ]
        );
			$Category->name = $input['name'];
			$Category->cat_url = $input['cat_url'];
			 $Category->cat_compare = $input['cat_compare'];
			 $Category->parent_id = $input['parent_id'];
			 $Category->tax_rate = $input['tax_rate'];
			  $Category->description = $input['description'];
			  $Category->cancel_description = $input['cancel_description'];
			  $Category->return_description = $input['return_description'];
		$Category->commission_rate = $input['commission_rate'];
			$Category->status = 0;
			
			  if ($request->hasFile('app_icon')) {
		
				$app_icon = $request->file('app_icon');
				$destinationPath =Config::get('constants.uploads.cat_logo');
				$file_name=$app_icon->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$app_icon,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		
        $Category->app_icon = $file_name;
  }
  
  	  if ($request->hasFile('logo_image')) {
		
				$logo_image = $request->file('logo_image');
				$destinationPath =Config::get('constants.uploads.cat_logo');
				$file_name=$logo_image->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name,130,64);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		
        $Category->logo = $file_name;
  }
  
  if ($request->hasFile('size_chart')) {
		
				$size_chart = $request->file('size_chart');
				$destinationPath =Config::get('constants.uploads.size_chart');
				$file_name=$size_chart->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$size_chart,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		
        $Category->size_chart = $file_name;
  }


      if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.cat_banner');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,1920,796);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
              $Category->banner_image = $file_name2;
    }
  
   /* save the following details */
   if($Category->save()){
        MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
   } else{
		MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
   }
  
   return Redirect::back();
	 }
           return view('admin.categories.category.form',['page_details'=>$page_details,'id'=>$id]);

   }


     public function delete_category(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Category::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
                 $input['cat_id']=array($id);
                 Category::onChangeStsDelete($input['cat_id'],1);
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }

    public function cat_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);
  $st=($sts==0) ? 1 : 0;
            $res=Category::where('id',$id)
                    ->update(['status' => $st]);

            if ($res) {
                if($st==0){
                $input['cat_id']=array($id);
                  Category::onChangeStsDelete($input['cat_id'],0);
                }
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }


  // root category route end 
  public function multi_vendor_delete_cat(Request $request){
    $input=$request->all();
    $vendor_id = auth()->guard('vendor')->user()->id;;
    $assignedCategory = DB::table('vendor_categories')->where('vendor_id',$vendor_id)->first();
    if(!empty($assignedCategory )){       
      $selectedCats = $assignedCategory->selected_cats;
      $allAssignedCats = explode(",",$selectedCats);
      $newCategoryList = array_diff( $allAssignedCats, $input['cat_id']);  
      DB::table('vendor_categories')->where('vendor_id',$vendor_id)->update(['selected_cats' => implode(',',$newCategoryList) ]);
      MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
     return redirect()->back();
    }
  }

}
