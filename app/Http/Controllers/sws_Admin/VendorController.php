<?php

namespace App\Http\Controllers\sws_Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use App\Http\Controllers\Vendors;
use App\Helpers\MsgHelper;
use App\Brands;
use App\Vendor;
use App\Products;
use App\ProductImages;
use App\ProductCategories;
use App\ProductRelation;
use App\ProductAttributes;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Session;
use View;
use App\Helpers\CommonHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use URL;
class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	$this->middleware('auth:vendor', ['except' => [
            'getVendorName'
        ]]);
    }

    protected $rules =
    [
        'price' => 'required',
        'qty' => 'required',
    ];
	
    protected $messages =
    [
        'price.required' => 'Price can not be blank',
        'qty.required' => 'Quantity can not be blank'
    ];
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function getVendorName(Request $request)
	{
	    
	    $inputData=$request->all();
	    $term=$inputData['q']['term'];
	    
        $getDetails = Vendor::select('vendors.id','vendors.username as name')
            ->where(function($sql) use ($term){
	          $sql->where('vendors.username','like', DB::raw("'$term%'"));
            $sql->where('vendors.status',1);
            $sql->where('vendors.isdeleted',0);
		
			})
	       ->limit(10)->get()->toarray();
	        $results = [];

			if (!empty($getDetails)) {
			foreach($getDetails as $details){
				$results[] = [
					'id' => $details['id'],
					'text' => $details['name'],
					'isExist' => 1
				];
			}
			} else {
				$results[] = [
				'id'      => $request->q,
				'text'    => $request->q,
				'isExist' => 0,
				];
			}

			return json_encode([
				'results' => $results,
			]);
	}
   
   public function existing_product(){

	   $page_details=array(
        "Title"=>"Existing Product",
        "Box_Title"=>"Search product",
		"Form_data"=>array(

         "Form_field"=>array(
		 "product_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'number',
							'name'=>'qty',
							'id'=>'qty',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
             
			"product_price_field"=>array(
							'label'=>'Price *',
							'type'=>'text',
							'name'=>'price',
							'id'=>'price',
							'classes'=>'form-control',
							'placeholder'=>'Price',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_price_field"=>array(
							'label'=>'Special Price',
							'type'=>'text',
							'name'=>'spcl_price',
							'id'=>'spcl_price',
							'classes'=>'form-control',
							'placeholder'=>'Special Price',
							'value'=>'',
							'disabled'=>''
			),
				"product_spcl_from_date_field"=>array(
							'label'=>'Special Price From Date',
							'type'=>'date',
							'name'=>'spcl_from_date',
							'id'=>'spcl_from_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_to_date_field"=>array(
							'label'=>'Special Price To Date',
							'type'=>'date',
							'name'=>'spcl_to_date',
							'id'=>'spcl_to_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"product_manage_stock_field"=>array(
							'label'=>'Manage Stock',
							'type'=>'select',
							'name'=>'manage_stock',
							'id'=>'manage_stock',
							'classes'=>'form-control',
							'placeholder'=>'manage_stock',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"NO",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			
         "product_qty_for_out_stock_field"=>array(
							'label'=>"Qty to Become Out of Stock",
							'type'=>'number',
							'name'=>'qty_out',
							'id'=>'qty_out',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_stock_availability_field"=>array(
							'label'=>'Stock Availability',
							'type'=>'select',
							'name'=>'stock_availability',
							'id'=>'stock_availability',
							'classes'=>'form-control',
							'placeholder'=>'stock_availability',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"In Stock",
							),(object)array(
							"id"=>"0",
							"name"=>"Out Of Stock",
							)
							),
							'disabled'=>'',
							'selected'=>''
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
		"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
									 "product_atr_price_field"=>array(
							'label'=>'Price',
							'type'=>'text',
							'name'=>'atr_price[]',
							'id'=>'atr_price',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
		)
         ),"return_data"=>array(
							'attr'=>array(),
							'image_html'=>'',
							'product_images'=>array()
       )
        );
	  return view('vendor.product.search_existing_product',['page_details'=>$page_details]);
   }
   public function gotHome(){
       $page_details=array(
       "Title"=>"Home",
		"Method"=>"1",
		"Box_Title"=>"Home"
		);
	    return view('vendor.home',['page_details'=>$page_details]);
   }
    public function index(Request $request)
    { 
    	
      $Brands= Brands::where('isdeleted', 0);
                    $Brands=$Brands->orderBy('id', 'DESC')->paginate(100);
   $vendors=Vendor::get();
		$parameters=$request->str;	
		
		if($parameters!=''){
			$export=route('exportProduct_with_Search',($request->str));
			} else{
			$export=route('exportProduct');
		}
		
	 	$page_details=array(
			"Title"=>"Product List",
			"Box_Title"=>"Product(s)",
			  "search_route"=>URL::to('admin/vendor_filters_products'),
			"reset_route"=>route('vendor_product'),
			 "export"=>$export,
				 "Form_data"=>array(
				 "Form_field"=>array(
						 "submit_button_field"=>array(
						  'label'=>'',
						  'type'=>'submit',
						  'name'=>'submit',
						  'id'=>'submit',
						  'classes'=>'btn btn-danger disableAfterClick',
						  'placeholder'=>'',
						  'value'=>'Save'
						),
                    "select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'category_id',
                    'id'=>'category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',old('parent_id'))
                    )
					)
			)
		);
		
        $Products= Products::select('products.*')->where('products.isdeleted', 0);
		
		if($parameters!=''){
				  /*$Products=$Products->where('products.name','LIKE',$parameters.'%');*/
				  $Products =$Products
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('categories','product_categories.product_id','=','categories.id')
						->where('products.isexisting',0)
						->Where(function($query) use ($parameters){
							 $query->orWhere('products.name','LIKE', '%' . $parameters . '%');
							 $query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $parameters . '%');
						 });
		} 
		/*$products=$Products->orderBy('id', 'DESC')->where('products.name','LIKE',$parameters.'%')->paginate(100);*/
		
		 if(Auth::guard('vendor')->check()){
           $Products=$Products->where('products.vendor_id','=',auth()->guard('vendor')->user()->id);
        } 
						
		$products=$Products->groupBy('products.id')->orderBy('products.id', 'DESC')->paginate(100);
		
		return view('vendor.product.list',['products'=>$products,'page_details'=>$page_details,'vendors'=>$vendors,'Brands'=>$Brands]);
	      
    }
    
     public function vendor_filters_products(Request $request)
    {
		$vendors=Vendor::get();
		$Brands= Brands::where('isdeleted', 0);
                    $Brands=$Brands->orderBy('id', 'DESC')->paginate(100);
    $parameters=$request->str;	
    $sts=$request->sts;	
    $vendor=$request->vendor;
      $type=$request->type;	
       $category_id =$request->category_id;
        $blocked =$request->blocked;
         $brands =$request->brands;
	
		
		if($parameters!=''){
			$export=route('exportProduct_with_Search',($request->str));
			} else{
			$export=route('exportProduct');
		}
		
	 	$page_details=array(
			"Title"=>"Product List",
			"Box_Title"=>"Product(s)",
		     "search_route"=>URL::to('admin/vendor_filters_products'),
			"reset_route"=>route('vendor_product'),
			 "export"=>$export,
			 	"Form_data"=>array(
				 "Form_field"=>array(
						 "submit_button_field"=>array(
						  'label'=>'',
						  'type'=>'submit',
						  'name'=>'submit',
						  'id'=>'submit',
						  'classes'=>'btn btn-danger disableAfterClick',
						  'placeholder'=>'',
						  'value'=>'Save'
						),"select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'category_id',
                    'id'=>'category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',($category_id!='All')?$category_id:0)
                    )
					)
			)
		);
		
        $Products= Products::select('products.*')->where('products.isdeleted', 0);
		if(Auth::guard('vendor')->check()){
           $Products=$Products->where('products.vendor_id','=',auth()->guard('vendor')->user()->id);
        } 
		if( ($category_id!='All' && $category_id!='')  || ($parameters!='All' && $parameters!='')){
		   
		        $Products =$Products
				  		->join('product_categories','product_categories.product_id','=','products.id')
						->join('categories','categories.id','=','product_categories.cat_id');
				  
		} 
		
		if($brands!='All' &&  $brands!=''){
		    	$selcted_brand=explode(",",$brands);
		    
				   $Products=$Products->whereIn('products.product_brand',$selcted_brand);
		} 
	
        
		if( $sts!='' && $sts!='All'){
				$Products=$Products->where('products.status','=',$sts);
		} 
	
	if( $blocked!='' && $blocked!='All'){
				$Products=$Products->where('products.isblocked','=',$blocked);
		} 
		
		if($type!='All' && $type!=''){
		     $Products=$Products->where('products.isexisting','=',$type);
		}
		
		
		if($parameters!='All' && $parameters!=''){
		       $Products =$Products
						   ->Where(function($query) use ($parameters){
							 $query->Where('products.name','LIKE', '%' . $parameters . '%');
							 $query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $parameters . '%');
						 });
		} 
		if($category_id!='All' && $category_id!=''){
		  	$Products =$Products->where('product_categories.cat_id',$category_id);
		}  
						
		$products=$Products->groupBy('products.id')->orderBy('products.id', 'DESC')->paginate(100);
		
		return view('vendor.product.list',['products'=>$products,'page_details'=>$page_details,'vendors'=>$vendors,'Brands'=>$Brands]);
    }
	 public function getProDetails(Request $request)
		{
			
			$dt=array(
			"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getSizes(),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getColors(),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									));
			$input=$request->all();
			 $Products= Products::select('id','price','qty','spcl_price','spcl_from_date','spcl_to_date','manage_stock','qty_out','stock_availability')
							->where('isdeleted', 0)
							->where('products.id', '=',$input['prd_id'])
							->first()->toarray();
							
					$Products['spcl_from_date']=($Products['spcl_from_date']!='')?date("m/d/Y", strtotime($Products['spcl_from_date'])):'';	
					$Products['spcl_to_date']=($Products['spcl_to_date']!='')?date("m/d/Y", strtotime($Products['spcl_to_date'])):'';	
							$obj= new ProductAttributes;
							$data=$obj->getProductAttributes($input['prd_id']);
							$html='';
							foreach($data as $row){
								
								$html.='<div class="row">';
	
	$html.='<div class="col-md-3">';
	$html.=CustomFormHelper::getSizeHtml($dt["product_size_field"],$row['size_id']);
	$html.='</div>';
	
	
	$html.='<div class="col-md-3">';
	$html.=CustomFormHelper::getColorHtml($dt["product_color_field"],$row['color_id']);
	$html.='</div>';
	
		$html.='<div class="col-md-3">';
		$html.=CustomFormHelper::getQtyHtml($dt["product_atr_qty_field"],$row['qty']);
		$html.='</div>';
	
	
	$html.='<div class="col-md-3">';
	$html.='<div class="form-group">';
	$html.='<span class="remove_atr pointer"><i class="fa fa-trash"></i></span>';
	$html.='</div>';
	$html.='</div>';
	
	$html.='</div>';
							}
							 echo json_encode(array(
							 "product_details"=>$Products,
							 "product_attr"=>$html,
							  "product_attr_length"=>sizeof($data)
							 ));
							
							
		}
		
		 public function update_vdr_profile(Request $request)
    {
		$id=Auth::user()->id;
		$request->session()->put('vendor_id', $id);
		$level= base64_decode($request->level);


		$Vendor = new Vendor;		  
		$return_data=$Vendor->getVendorDetails($request->session()->get('vendor_id'));
		
		$select_cats='';
		$dr=DB::table('vendor_categories')->where('vendor_id',$id)->first();
		if($dr){
		   $select_cats= $dr->selected_cats;
		}
		
 $page_details=array(
       "Title"=>"Update Profile",
		"Method"=>"1",
		"Box_Title"=>"Update Profile",
        "Action_route"=>route('update_vdr_profile', [base64_encode($level),base64_encode($id)]),
       "Form_data"=>array(

         "Form_field"=>array(
             "vendor_f_name_field"=>array(
							'label'=>'First Name',
							'type'=>'text',
							'name'=>'f_name',
							'id'=>'f_name',
							'classes'=>'form-control',
							'placeholder'=>'First Name',
							'value'=>$return_data['f_name'],
							'disabled'=>''
									),
				"vendor_l_name_field"=>array(
						'label'=>'Last Name',
						'type'=>'text',
						'name'=>'l_name',
						'id'=>'l_name',
						'classes'=>'form-control',
						'placeholder'=>'Last Name',
						'value'=>$return_data['l_name'],
						'disabled'=>''
				),
				"vendor_user_name_field"=>array(
						'label'=>'User Name *',
						'type'=>'text',
						'name'=>'username',
						'id'=>'username',
						'classes'=>'form-control',
						'placeholder'=>'User Name',
						'value'=>$return_data['username'],
						'disabled'=>''
				),
				"vendor_public_name_field"=>array(
						'label'=>'Public Name *',
						'type'=>'text',
						'name'=>'public_name',
						'id'=>'public_name',
						'classes'=>'form-control',
						'placeholder'=>'Public Name',
						'value'=>$return_data['public_name'],
						'disabled'=>''
				),
				"vendor_email_field"=>array(
						'label'=>'Email *',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>$return_data['email'],
						'disabled'=>'disabled'
				),
				"vendor_phone_field"=>array(
						'label'=>'Phone *',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>$return_data['phone'],
						'disabled'=>'disabled'
				),
				"vendor_password_field"=>array(
						'label'=>'Password *',
						'type'=>'password',
						'name'=>'password',
						'id'=>'password',
						'classes'=>'form-control',
						'placeholder'=>'******',
						'value'=>'',
						'disabled'=>''
				),
			"vednor_gender_field"=>array(
							'label'=>'Gender',
							'type'=>'select',
							'name'=>'gender',
							'id'=>'gender',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Male",
							),(object)array(
							"id"=>"2",
							"name"=>"Female",
							)
							),
							'disabled'=>'',
							'selected'=>$return_data['gender']
			),
			"vendor_profile_pic_field"=>array(
						'label'=>'Profile Pic',
						'type'=>'file_special',
						'name'=>'profile_pic',
						'id'=>'file-5',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				"vendor_company_name_field"=>array(
						'label'=>'Company Name',
						'type'=>'text',
						'name'=>'company_name',
						'id'=>'company_name',
						'classes'=>'form-control',
						'placeholder'=>'Company Name',
						'value'=>$return_data['company_name'],
						'disabled'=>''
				),
				"vendor_company_address_field"=>array(
						'label'=>'Company Address',
						'type'=>'text',
						'name'=>'company_address',
						'id'=>'company_address',
						'classes'=>'form-control',
						'placeholder'=>'Company Address',
						'value'=>$return_data['company_address'],
						'disabled'=>''
				),
				"vendor_company_state_field"=>array(
						'label'=>'Company State',
						'type'=>'text',
						'name'=>'company_state',
						'id'=>'company_state',
						'classes'=>'form-control',
						'placeholder'=>'Company State',
						'value'=>$return_data['company_state'],
						'disabled'=>''
				),
				"vendor_company_city_field"=>array(
						'label'=>'Company City',
						'type'=>'text',
						'name'=>'company_city',
						'id'=>'company_city',
						'classes'=>'form-control',
						'placeholder'=>'Company City',
						'value'=>$return_data['company_city'],
						'disabled'=>''
				),
				"vendor_company_pincode_field"=>array(
						'label'=>'Company Pincode',
						'type'=>'text',
						'name'=>'company_pincode',
						'id'=>'company_pincode',
						'classes'=>'form-control',
						'placeholder'=>'Company Pincode',
						'value'=>$return_data['company_pincode'],
						'disabled'=>''
				),
				
				"vendor_company_about_field"=>array(
					'label'=>'About Us',
					'type'=>'textarea',
					'name'=>'company_about',
					'id'=>'company_about',
					'classes'=>'ckeditor form-control',
					'placeholder'=>'About',
					'value'=>$return_data['company_about_us'],
					'disabled'=>''
					),
				"vendor_company_logo_field"=>array(
						'label'=>'Company Logo',
						'type'=>'file_special',
						'name'=>'company_logo',
						'id'=>'file-1',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				 "vendor_tax_type_field"=>array(
				'label'=>'Tax Type',
				'type'=>'radio',
				'name'=>'tax_type',
				'id'=>'tax_type',
				'classes'=>'radioSelection',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Flat Percent",
							),(object)array(
							"id"=>"2",
							"name"=>"Fixed Rate",
							)
							),
				'disabled'=>'',
				'selected'=>$return_data['tax_type']
				),
				"vendor_tax_rate_field"=>array(
						'label'=>'Tax',
						'type'=>'text',
						'name'=>'tx_rate',
						'id'=>'tx_rate',
						'classes'=>'form-control hideextraField',
						'placeholder'=>'',
						'value'=>$return_data['tax_rate'],
						'disabled'=>''
				),
				"vendor_support_number_field"=>array(
						'label'=>'Phone',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>$return_data['support_phone'],
						'disabled'=>''
				),
				"vendor_support_email_field"=>array(
						'label'=>'Email',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>$return_data['support_email'],
						'disabled'=>''
				),
				"vendor_support_fb_field"=>array(
						'label'=>'Facebook Id',
						'type'=>'text',
						'name'=>'fb_id',
						'id'=>'fb_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>$return_data['support_fb_id'],
						'disabled'=>''
				),
				"vendor_support_tw_field"=>array(
						'label'=>'Twitter Id',
						'type'=>'text',
						'name'=>'tw_id',
						'id'=>'tw_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>$return_data['support_tw_id'],
						'disabled'=>''
				),
			 "vendoe_seo_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control meta_tile',
							'placeholder'=>'Meta Title',
							'value'=>$return_data['meta_title'],
							'disabled'=>''
									),
			"vendoe_seo_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control meta_description',
						'placeholder'=>'Meta description',
						'value'=>$return_data['meta_description'],
						'disabled'=>''
			),
			"vendoe_seo_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>' form-control meta_keyword',
						'placeholder'=>'Meta Keyword',
						'value'=>$return_data['meta_keyword'],
						'disabled'=>''
			),
				"vendor_bank_ac_holder_name_field"=>array(
				'label'=>'Account Holder Name.',
				'type'=>'text',
				'name'=>'ac_holder_name',
				'id'=>'ac_holder_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_ac_no_field"=>array(
				'label'=>'Account No.',
				'type'=>'text',
				'name'=>'ac_no',
				'id'=>'ac_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_name_field"=>array(
				'label'=>'Bank Name',
				'type'=>'text',
				'name'=>'bank_name',
				'id'=>'bank_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_branch_name_field"=>array(
				'label'=>'Branch Name',
				'type'=>'text',
				'name'=>'branch_name',
				'id'=>'branch_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_city_field"=>array(
				'label'=>'City',
				'type'=>'text',
				'name'=>'bank_city',
				'id'=>'bank_city',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_ifsc_field"=>array(
				'label'=>'IFSC Code',
				'type'=>'text',
				'name'=>'ifsc_code',
				'id'=>'ifsc_code',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_gst_field"=>array(
				'label'=>'GST NO',
				'type'=>'text',
				'name'=>'gst_no',
				'id'=>'gst_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>$return_data['gst_no'],
				'disabled'=>''
				),
				"vendor_pan_field"=>array(
				'label'=>'PAN NO',
				'type'=>'text',
				'name'=>'pan_no',
				'id'=>'pan_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>$return_data['pan_no'],
				'disabled'=>''
				),
				"vendor_gst_file_field"=>array(
						'label'=>'GST Doc',
						'type'=>'file',
						'name'=>'gst_file',
						'id'=>'file-2',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>$return_data['gst_file'],
						'disabled'=>''
				),
				"vendor_pan_file_field"=>array(
						'label'=>'Pan Doc',
						'type'=>'file_special',
						'name'=>'pan_file',
					'id'=>'file-3',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>$return_data['pan_file'],
						'disabled'=>''
				),	"vendor_cheque_file_field"=>array(
						'label'=>'Cancel cheque',
						'type'=>'file_special',
						'name'=>'cheque',
						'id'=>'file-5',
					     'classes'=>'inputfile inputfile-5',
						'placeholder'=>'',
						'value'=>$return_data['cancel_cheque_file'],
						'disabled'=>''
				),
				"vendor_signature_file_field"=>array(
						'label'=>'Signature',
						'type'=>'file_special',
						'name'=>'signature',
						'id'=>'file-7',
					     'classes'=>'inputfile inputfile-7',
						'placeholder'=>'',
						'value'=>$return_data['signature_file'],
						'disabled'=>''
				),
				"vendor_invoice_address_field"=>array(
				'label'=>'Address On Invoice',
				'type'=>'radio',
				'name'=>'invoice_address',
				'id'=>'invoice_address',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Same as Company Address",
							),(object)array(
							"id"=>"2",
							"name"=>"Same as Store Address",
							)
							),
				'disabled'=>'',
				'selected'=>$return_data['invoice_address']
				),
				"vendor_invoice_logo_field"=>array(
				'label'=>'Logo on invoice',
				'type'=>'radio',
				'name'=>'invoice_logo',
				'id'=>'invoice_logo',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Company Logo",
							),(object)array(
							"id"=>"2",
							"name"=>"Store Logo",
							)
							),
				'disabled'=>'',
				'selected'=>$return_data['invoice_logo']
				),
		   "submit_button_field"=>array(
							'label'=>'',
							'type'=>'submit',
							'name'=>'submit',
							'id'=>'submit',
							'classes'=>'btn btn-danger',
							'placeholder'=>'',
							'value'=>'Save'
		)
         )
       ),
	    "return_data"=>array(
								'category'=>explode(",",$select_cats),
							'company_logo'=>$return_data['company_logo'],
							'profile_pic'=>$return_data['profile_pic']
		)
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
			 
		
			 
		  		 switch($level){
			 case 0;  // general info tab
			
			$request->validate([
				'username' => 'max:25|required|unique:vendors,username,'.$id.',id,isdeleted,1',
				'public_name' => 'max:25|required|unique:vendors,public_name,'.$id.',id,isdeleted,1',
				'f_name' => 'max:25',
				'l_name' => 'max:25',
                'profile_pic' => 'mimes:jpeg,bmp,png',
             ]
        );
	   $Vendor = Vendor::where('id', $id)->first();
	 if ($request->hasFile('profile_pic')) {
						$profile_pic = $request->file('profile_pic');
						$destinationPath =Config::get('constants.uploads.vendor_profile_pic');
						$file_name=$profile_pic->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$profile_pic,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							return Redirect::back();
							}
							$proifle_pic=$file_name;
							$Vendor->profile_pic = $proifle_pic;
				  } 
				  
				   if ($input['password']!='') {
						$request->validate([
						'password' => 'min:5|max:15',
						]
						);
					   $Vendor->password =Hash::make($input['password']);
				  } 
		 
      
			
			
			$Vendor->f_name = $input['f_name'];
			$Vendor->l_name =$input['l_name'];
			$Vendor->username =$input['username'];
			$Vendor->public_name =$input['public_name'];
			$Vendor->gender = $input['gender'];
     
      /* save the following details */
      if($Vendor->save()){
		       Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 return Redirect::route('update_vdr_profile', [base64_encode(0),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(0),base64_encode($id)]);
      }
     
	  
			 break;
			  case 1;  // categories tab
				$Vendor = new Vendor;
      if($Vendor->updateCategories($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(1),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(1),base64_encode($id)]);
      }
			   break;
			   
			 
			   case 2: // company info tab
				 
					$request->validate([
			'company_name' => 'max:25',
			'company_address' => 'max:100',
            'company_state' => 'max:25',
            'company_city' => 'max:25',
            'company_pincode' => 'max:6|min:6',
			'about_us' => 'max:200',
			'company_logo' => 'mimes:jpeg,bmp,png'
			]
			);
			
	 if ($request->hasFile('company_logo')) {
						$company_logo = $request->file('company_logo');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$company_logo->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$company_logo,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['cm_logo']=$file_name;
				  } 
		 
				
				$Vendor = new Vendor;
      if($Vendor->updateCompanyInfo($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
      }
				 break;
			   
			    case 3;  // support info tab
				$request->validate([
			'phone' => 'max:10|regex:/[0-9]{10}/',
			'email' => 'max:50',
			'fb_id' => 'max:200',
			'tw_id' => 'max:200',
			]
			);
				$Vendor = new Vendor;
      if($Vendor->updateSupportInfo($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		     return Redirect::route('update_vdr_profile', [base64_encode(3),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(3),base64_encode($id)]);
      }
				 break;
				 
				   case 4;   // seo info info tab
				   
				 
			$request->validate([
			'meta_title' => 'max:60',
			'meta_description' => 'max:160',
			'meta_keyword' => 'max:255',
			]
			);
					$Vendor = new Vendor;
      if($Vendor->updateMetaInfo($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(4),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(4),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 5;   // bank  info info tab
				   
			
			$request->validate([
				'ac_no' => 'max:25',
				'bank_name' => 'max:50',
				'branch_name' => 'max:25',
				'bank_city' => 'max:25',
				'ifsc_code' => 'max:25',
				'cheque' => 'mimes:jpeg,png',
			]
			);
					$Vendor = new Vendor;
					
					
					if ($request->hasFile('cheque')) {
			    $cheque_file = $request->file('cheque');
			$destinationPath =Config::get('constants.uploads.cheque_file');
			$file_name=$cheque_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$cheque_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(5),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'cancel_cheque',$request->session()->get('vendor_id'));
			}
      if($Vendor->updateBankInfo($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(5),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(5),base64_encode($id)]);
      }
			   break;
			   
			  
			
			     case 6;   // tax  info info tab
			     
			     	$request->validate([
                'gst_no' => 'min:15|max:15',
                'pan_no' => 'min:10|max:10|alpha_num',
                'gst_file' => 'mimes:jpeg,png',
                'pan_file' => 'mimes:jpeg,png',
                
                'signature' => 'mimes:jpeg,png',
			]
			);
			
                       $Vendor = new Vendor;
			if ($request->hasFile('gst_file')) {
			$gst_file = $request->file('gst_file');
			$destinationPath =Config::get('constants.uploads.gst_file');
			$file_name=$gst_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$gst_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'gst_file',$request->session()->get('vendor_id'));
			} 
			
			if ($request->hasFile('pan_file')) {
			$pan_file = $request->file('pan_file');
			$destinationPath =Config::get('constants.uploads.pan_file');
			$file_name=$pan_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$pan_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'pan_file',$request->session()->get('vendor_id'));
			}
			
			
			
			if ($request->hasFile('signature')) {
			$signature_file = $request->file('signature');
			$destinationPath =Config::get('constants.uploads.signature_file');
			$file_name=$signature_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$signature_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'signature',$request->session()->get('vendor_id'));
			}
			
					
      if($Vendor->updateTaxInfo($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
      }
			   break;
			   
			   case 7;   // invoice details  info info tab
				 
			
			
					$Vendor = new Vendor;
      if($Vendor->updateInvoiceInfo($input,$request->session()->get('vendor_id'))){
              Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(7),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(7),base64_encode($id)]);
      }
			   break;
			   
			
				 
				 }
		
	  }
        
		
		 return view('vendor.profile.edit',['page_details'=>$page_details]);
	
    }
	
    public function search(Request $request)
		{ 
		  $vendorID=Auth::user()->id;
		  $vendor_product_data=array();
		
		$vendor_products=DB::table('vendor_existing_product')
			->select('vendor_existing_product.master_product_id')
			->where('vendor_existing_product.vendor_id','=',$vendorID)
			->get(); 
	
		foreach( $vendor_products  as $row){
			array_push($vendor_product_data,$row->master_product_id);
		}
		
		
			
			$output="";	
		if($request->search)
		{
			$products=DB::table('products')
			->select('products.id','products.name','products.price','products.qty')
			->where('products.name', 'like', '%'.$request->search."%")
			->where('products.isexisting', '==',0)
			->whereNotIn('products.id',$vendor_product_data)
			->orderBy('products.price','ASC')
			->paginate(100);
			
			if($products)
			{
			foreach ($products as $key => $product) {
			$output.='<tr id="product_row_'.$product->id.'">'.
			'<td>'.$product->name.'</td>'.
			'<td>'.$product->price.'</td>'.
			'<td><a href="javascript:void(0)" class="sell_class btn btn-primary" data="'.$product->id.'">Sell</a></td>'.
			'</tr>';
			}
			}
			
		   }
		   echo json_encode(array("data"=>$output));
		}	
		
		
		public function add(Request $request)
    {
		 $brands = Brands::select('id','name')->where('isdeleted', 0)->get();
		  $level= base64_decode($request->level);	
		
		
		
 $page_details=array(
       "Title"=>"Add Product",
		"Method"=>"1",
		"Box_Title"=>"Add Product",
       "Action_route"=>route('add_product_vdr',(base64_encode($level))),
       "Form_data"=>array(

         "Form_field"=>array(
             "product_name_field"=>array(
							'label'=>'Product Name *',
							'type'=>'text',
							'name'=>'name',
							'id'=>'name',
							'classes'=>'form-control',
							'placeholder'=>'Name',
							'value'=>'',
							'disabled'=>''
									),
			 "product_short_description_field"=>array(
							'label'=>'Short Description *',
							'type'=>'textarea',
							'name'=>'short_description',
							'id'=>'short_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Short description',
							'value'=>'',
							'disabled'=>''
			),
			 "product_long_description_field"=>array(
							'label'=>'Long Description *',
							'type'=>'textarea',
							'name'=>'long_description',
							'id'=>'long_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Long description',
							'value'=>'',
							'disabled'=>''
			),
			 "product_sku_field"=>array(
							'label'=>'SKU *',
							'type'=>'text',
							'name'=>'sku',
							'id'=>'sku',
							'classes'=>'form-control',
							'placeholder'=>'sku',
							'value'=>'',
							'disabled'=>''
									),
									"product_code_field"=>array(
                    'label'=>'Product Code',
                    'type'=>'text',
                    'name'=>'product_code',
                    'id'=>'product_code',
                    'classes'=>'form-control',
                    'placeholder'=>'123',
                    'value'=>'',
                    'disabled'=>''
                    ),
			"product_weight_field"=>array(
							'label'=>'Weight',
							'type'=>'text',
							'name'=>'weight',
							'id'=>'weight',
							'classes'=>'form-control',
							'placeholder'=>'weight',
							'value'=>'',
							'disabled'=>''
			),
			"product_status_field"=>array(
							'label'=>'Status *',
							'type'=>'select',
							'name'=>'status',
							'id'=>'status',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Enabled",
							),(object)array(
							"id"=>"0",
							"name"=>"Disabled",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_hsn_field"=>array(
							'label'=>'HSN CODE',
							'type'=>'text',
							'name'=>'hsn_code',
							'id'=>'hsn_code',
							'classes'=>'form-control',
							'placeholder'=>'123',
							'value'=>'',
							'disabled'=>''
									),
			"product_tax_field"=>array(
							'label'=>'Tax Class *',
							'type'=>'select',
							'name'=>'tax_class',
							'id'=>'tax_class',
							'classes'=>'form-control',
							'placeholder'=>'Tax',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"None",
							),(object)array(
							"id"=>"0",
							"name"=>"Taxable Goods",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_visibility_field"=>array(
							'label'=>'Visibility',
							'type'=>'select',
							'name'=>'visibility',
							'id'=>'visibility',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Not visible individualy",
							),(object)array(
							"id"=>"2",
							"name"=>"Catalog",
							),(object)array(
							"id"=>"3",
							"name"=>"Search",
							),(object)array(
							"id"=>"4",
							"name"=>"Catalog,Search",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_image_field"=>array(
							'label'=>'Default Image',
							'type'=>'file_special',
							'name'=>'default_image',
                            'id'=>'file-1',
                            'classes'=>'inputfile inputfile-4',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>'',
							'selected'=>''
			),
			"product_price_field"=>array(
							'label'=>'Price *',
							'type'=>'text',
							'name'=>'price',
							'id'=>'price',
							'classes'=>'form-control',
							'placeholder'=>'Price',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_price_field"=>array(
							'label'=>'Special Price',
							'type'=>'text',
							'name'=>'spcl_price',
							'id'=>'spcl_price',
							'classes'=>'form-control',
							'placeholder'=>'Special Price',
							'value'=>'',
							'disabled'=>''
			),
				"product_spcl_from_date_field"=>array(
							'label'=>'Special Price From Date',
							'type'=>'date',
							'name'=>'spcl_from_date',
							'id'=>'spcl_from_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_to_date_field"=>array(
							'label'=>'Special Price To Date',
							'type'=>'date',
							'name'=>'spcl_to_date',
							'id'=>'spcl_to_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			 "product_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control meta_tile',
							'placeholder'=>'Meta Title',
							'value'=>'',
							'disabled'=>''
									),
			"product_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control meta_description',
						'placeholder'=>'Meta description',
						'value'=>'',
						'disabled'=>''
			),
			"product_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>' form-control meta_keyword',
						'placeholder'=>'Meta Keyword',
						'value'=>'',
						'disabled'=>''
			),
			"product_deleivery_days_field"=>array(
							'label'=>"Delivery Days",
							'type'=>'number',
							'name'=>'delivery_days',
							'id'=>'delivery_days',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_shipping_charge_stock_field"=>array(
							'label'=>"Shipping Charges",
							'type'=>'number',
							'name'=>'shipping_charge',
							'id'=>'shipping_charge',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_manage_stock_field"=>array(
							'label'=>'Manage Stock',
							'type'=>'select',
							'name'=>'manage_stock',
							'id'=>'manage_stock',
							'classes'=>'form-control',
							'placeholder'=>'manage_stock',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"NO",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'number',
							'name'=>'qty',
							'id'=>'qty',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
         "product_qty_for_out_stock_field"=>array(
							'label'=>"Qty for Item's Status to Become Out of Stock",
							'type'=>'number',
							'name'=>'qty_out',
							'id'=>'qty_out',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_stock_availability_field"=>array(
							'label'=>'Stock Availability',
							'type'=>'select',
							'name'=>'stock_availability',
							'id'=>'stock_availability',
							'classes'=>'form-control',
							'placeholder'=>'stock_availability',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"In Stock",
							),(object)array(
							"id"=>"0",
							"name"=>"Out Of Stock",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_material_field"=>array(
							'label'=>'Material',
							'type'=>'select',
							'name'=>'material',
							'id'=>'Material',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Test Material 1",
							),(object)array(
							"id"=>"0",
							"name"=>"Test Material 2",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_brands_field"=>array(
							'label'=>'Brands',
							'type'=>'select',
							'name'=>'product_brand',
							'id'=>'product_brands_field',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>''
			),
			"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getSizes($request->session()->get('product_id')),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getColors($request->session()->get('product_id')),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
			"product_related_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_related_product_shown',
							'id'=>'is_related_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_up_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_up_sell_product_shown',
							'id'=>'is_up_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_cross_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_cross_sell_product_shown',
							'id'=>'is_cross_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
		   "submit_button_field"=>array(
							'label'=>'',
							'type'=>'submit',
							'name'=>'submit',
							'id'=>'submit',
							'classes'=>'btn btn-danger',
							'placeholder'=>'',
							'value'=>'Save'
		)
         )
       ),
	    "return_data"=>array(
							'attr'=>array(),
							'image_html'=>'',
							'product_images'=>array()
		)
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
			 
		  		 switch($level){
			 case 0;  // general info tab
			 $request->validate([
			'name' => 'required|unique:products,name,1,isdeleted|max:25',
			'short_description' => 'required|max:200',
			'long_description' => 'required|max:2000',
			'sku' => 'required|max:25',
			'product_code' => 'required|max:25',
			'weight' => 'max:25',
			'status' => 'required|max:25',
			 'default_image' => ''
             ]
        );
		
		if ($request->hasFile('default_image')) {
			   $defualt_image = $request->file('default_image');
            $destinationPath = Config::get('constants.uploads.product_images');
            $file_name=$defualt_image->getClientOriginalName();

       $file_name= FIleUploadingHelper::UploadImage($destinationPath,$defualt_image,$file_name);
      if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		   return Redirect::back();
      }else{
		    $input['default_image']=$file_name;
	  }
		  }
		  
         $vendorID=Auth::user()->id;
			$Products = new Products;
			$Products->name = $input['name'];
			$Products->short_description =$input['short_description'];
			$Products->long_description =$input['long_description'];
			$Products->sku =$input['sku'];
				$Products->product_code =$input['product_code'];
			$Products->weight = $input['weight'];
			$Products->prd_sts = $input['status'];
			$Products->default_image = $input['default_image'];
			$Products->vendor_id = $vendorID;
     
      /* save the following details */
      if($Products->save()){
		  $request->session()->put('product_id', $Products->id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(1)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
     
	  
			 break;
			  case 1;  // price tab
			$request->validate([
			'price' => 'required',
			'spcl_price' => '',
			'tax_class' => 'required'
			]
			);
			
			if($input['spcl_from_date']!='' || $input['spcl_to_date']!=''){
				$request->validate([
					'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
					'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
			]
			);
			}
		
		
			$Products = new Products;
      /* save the following details */
      if($Products->updatePrice($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(2)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			   
			     case 2;    // categories tab
					$ProductCategories = new ProductCategories;
      if($ProductCategories->updateCategories($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(3)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			     break;
				 
				 
			  
			   
			   
			   case 3: // attributes tab
				 
			 if (array_key_exists("atr_color",$input))
					{
						$color_validator = Validator::make($request->all(),[
					'atr_color.*' => 'required',
					]);
						
						
					$size_validator = Validator::make($request->all(),[
					'atr_size.*' => 'required',
					]);
					
					if($color_validator->fails() && $size_validator->fails() ) {
						 return Redirect::back()->withErrors(['Size or color is mandatory']);
					}
					
					if($color_validator->fails() ) {
						 $this->validate($request, [
							'atr_size.*' => 'distinct|required',
							
							],[
							'atr_size.*.distinct' => 'Size  needs to be unique',
							'atr_size.*.required' => 'Size  is mandatory'
							]
							);
					}
						
						if($size_validator->fails() ) {
						 $this->validate($request, [
							'atr_color.*' => 'distinct|required',
							
							],[
							'atr_color.*.distinct' => 'Color  needs to be unique',
							'atr_color.*.required' => 'atr_color  is mandatory'
							]
							);
					}
							$this->validate($request, [
							'atr_qty.*' => 'required',
							
							],[
							'atr_qty.*.required' => 'Quantity  is mandatory'
							]
							);
							if($color_validator->fails()==0 && $size_validator->fails()==0) {
								for($i=0;$i<sizeof($input['atr_size'])-1;$i++){
								for($k=$i;$k<sizeof($input['atr_size'])-1;$k++){
								if($input['atr_size'][$i]==$input['atr_size'][$k+1] && $input['atr_color'][$i]==$input['atr_color'][$k+1]){
								return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
								} 
								}
								}
							}

							
					}
					$ProductAttributes = new ProductAttributes;
			 if($ProductAttributes->updateAttributes($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return redirect()->route('add_product_vdr', ['level' => base64_encode(4)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
				 break;
			   
			    case 4;  // images tab
			
				$images_name=array();
			if($request->hasfile('images'))
				 {
					foreach($request->file('images') as $image)
					{
						$destinationPath =Config::get('constants.uploads.product_images');
						$file_name=$image->getClientOriginalName();
						$images_name[] = FIleUploadingHelper::UploadImage($destinationPath,$image,$file_name);						
					}
				 }
				$ProductImages = new ProductImages;
      if($ProductImages->updateImages($images_name,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(5)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			   case 5;  // manage stock tab
			  $request->validate([
			'manage_stock' => '',
			'qty' => '',
			'qty_out' => '',
			'stock_availability' => ''
			]
			);
		
			$Products = new Products;
      if($Products->updateStock($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(6)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			
			   break;
			   
			   
			   
			  
				 
				 case 6;  // extra info tab
					
		
					$Products = new Products;
      if($Products->updateExtras($input,$request->session()->get('product_id'))){
		   MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		      return redirect()->route('add_product_vdr', ['level' => base64_encode(7)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			     break;
				 
				   case 7;   // meta info info tab
			$request->validate([
			'meta_title' => 'max:60',
			'meta_description' => 'max:160',
				'meta_keyword' => 'max:255',
			]
			);
				$Products = new Products;
      if($Products->updatemetaInfo($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(8)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			    case 8;  /// related product tab
			
				$ProductRelation = new ProductRelation;
				
      if($ProductRelation->updateRelation($input,$request->session()->get('product_id'),0)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				 return redirect()->route('add_product_vdr', ['level' => base64_encode(9)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   case 9; /// up sell tab
			
				$ProductRelation = new ProductRelation;
				if($ProductRelation->updateRelation($input,$request->session()->get('product_id'),1)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				 return redirect()->route('add_product_vdr', ['level' => base64_encode(10)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			    case 10; /// cross sell tab
			
				$ProductRelation = new ProductRelation;
							
      if($ProductRelation->updateRelation($input,$request->session()->get('product_id'),2)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return redirect()->route('vendor_product');
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
				 
				 
				 }
		
	  }
        
		
		 return view('vendor.product.form_add',['page_details'=>$page_details]);
	
    }
	
	public function edit_product(Request $request)
   {
	      $id=base64_decode($request->id);
		   $request->session()->put('product_id', $id);
		   
		  $products_list = Products::where('isdeleted', 0)->where('id','!=',$id)->orderBy('id', 'DESC')->paginate(10);
		  
			$ProductImages = new ProductImages;
			$images=$ProductImages->getImagesHtml($id);

			$ProductAttributes = new ProductAttributes;

			$attr=$ProductAttributes->getProductAttributes($id);
	  
	  
		$Products = Products::where('id', $id)->first();
					$brands = Brands::select('id','name')->where('isdeleted', 0)->get();
					$level= base64_decode($request->level);	
		
 $page_details=array(
       "Title"=>"Edit Product",
		"Method"=>"1",
		"Box_Title"=>"Edit Product",
       "Action_route"=>route('edit_product_vdr', [base64_encode($level),base64_encode($id)]),
       "Form_data"=>array(

         "Form_field"=>array(
             "product_name_field"=>array(
							'label'=>'Product Name *',
							'type'=>'text',
							'name'=>'name',
							'id'=>'name',
							'classes'=>'form-control',
							'placeholder'=>'Name',
							'value'=>$Products->name,
							'disabled'=>''
									),
			 "product_short_description_field"=>array(
							'label'=>'Short Description *',
							'type'=>'textarea',
							'name'=>'short_description',
							'id'=>'short_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Short description',
							'value'=>$Products->short_description,
							'disabled'=>''
			),
			 "product_long_description_field"=>array(
							'label'=>'Long Description *',
							'type'=>'textarea',
							'name'=>'long_description',
							'id'=>'long_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Long description',
							'value'=>$Products->long_description,
							'disabled'=>''
			),
			 "product_sku_field"=>array(
							'label'=>'SKU *',
							'type'=>'text',
							'name'=>'sku',
							'id'=>'sku',
							'classes'=>'form-control',
							'placeholder'=>'sku',
							'value'=>$Products->sku,
							'disabled'=>''
									),
									"product_code_field"=>array(
                    'label'=>'Product Code',
                    'type'=>'text',
                    'name'=>'product_code',
                    'id'=>'product_code',
                    'classes'=>'form-control',
                    'placeholder'=>'123',
                    'value'=>$Products->product_code,
                    'disabled'=>''
                    ),
			"product_weight_field"=>array(
							'label'=>'Weight',
							'type'=>'text',
							'name'=>'weight',
							'id'=>'weight',
							'classes'=>'form-control',
							'placeholder'=>'weight',
							'value'=>$Products->weight,
							'disabled'=>''
			),
			"product_status_field"=>array(
							'label'=>'Status *',
							'type'=>'select',
							'name'=>'status',
							'id'=>'status',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Enabled",
							),(object)array(
							"id"=>"0",
							"name"=>"Disabled",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->prd_sts
			),
			"product_visibility_field"=>array(
							'label'=>'Visibility',
							'type'=>'select',
							'name'=>'visibility',
							'id'=>'visibility',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Not visible individualy",
							),(object)array(
							"id"=>"2",
							"name"=>"Catalog",
							),(object)array(
							"id"=>"3",
							"name"=>"Search",
							),(object)array(
							"id"=>"4",
							"name"=>"Catalog,Search",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->visibility
			),
			"product_image_field"=>array(
							'label'=>'Default Image',
							'type'=>'file_special',
							'name'=>'default_image',
        					  'id'=>'file-1',
                            'classes'=>'inputfile inputfile-4',
							'placeholder'=>'',
							'value'=>$Products->default_image,
							'disabled'=>'',
							'selected'=>''
			),
			 "product_hsn_field"=>array(
							'label'=>'HSN CODE',
							'type'=>'text',
							'name'=>'hsn_code',
							'id'=>'hsn_code',
							'classes'=>'form-control',
							'placeholder'=>'123',
							'value'=>$Products->hsn_code,
							'disabled'=>''
									),
			"product_tax_field"=>array(
							'label'=>'Tax Class *',
							'type'=>'select',
							'name'=>'tax_class',
							'id'=>'tax_class',
							'classes'=>'form-control',
							'placeholder'=>'Tax',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"None",
							),(object)array(
							"id"=>"0",
							"name"=>"Taxable Goods",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->tax_class
			),
			"product_price_field"=>array(
							'label'=>'Price *',
							'type'=>'text',
							'name'=>'price',
							'id'=>'price',
							'classes'=>'form-control',
							'placeholder'=>'Price',
							'value'=>$Products->price,
							'disabled'=>''
			),
			"product_spcl_price_field"=>array(
							'label'=>'Special Price',
							'type'=>'text',
							'name'=>'spcl_price',
							'id'=>'spcl_price',
							'classes'=>'form-control',
							'placeholder'=>'Special Price',
							'value'=>$Products->spcl_price,
							'disabled'=>''
			),
				"product_spcl_from_date_field"=>array(
							'label'=>'Special Price From Date',
							'type'=>'date',
							'name'=>'spcl_from_date',
							'id'=>'spcl_from_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=> ($Products->spcl_from_date!='')?date("m/d/Y", strtotime($Products->spcl_from_date)):'',
							'disabled'=>''
			),
			"product_spcl_to_date_field"=>array(
							'label'=>'Special Price To Date',
							'type'=>'date',
							'name'=>'spcl_to_date',
							'id'=>'spcl_to_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>($Products->spcl_to_date!='')?date("m/d/Y", strtotime($Products->spcl_to_date)):'',
							'disabled'=>''
			),
			 "product_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control',
							'placeholder'=>'Meta Title',
							'value'=>$Products->meta_title,
							'disabled'=>''
									),
			"product_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control',
						'placeholder'=>'Meta description',
						'value'=>$Products->meta_description,
						'disabled'=>''
			),
			"product_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>'cat_url form-control',
						'placeholder'=>'Meta Keyword',
						'value'=>$Products->meta_keyword,
						'disabled'=>''
			),
			"product_manage_stock_field"=>array(
							'label'=>'Manage Stock',
							'type'=>'select',
							'name'=>'manage_stock',
							'id'=>'manage_stock',
							'classes'=>'form-control',
							'placeholder'=>'manage_stock',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"NO",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->manage_stock
			),
			"product_deleivery_days_field"=>array(
							'label'=>"Delivery Days",
							'type'=>'number',
							'name'=>'delivery_days',
							'id'=>'delivery_days',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->delivery_days,
							'disabled'=>''
			),
			"product_shipping_charge_stock_field"=>array(
							'label'=>"Shipping Charges",
							'type'=>'number',
							'name'=>'shipping_charge',
							'id'=>'shipping_charge',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->shipping_charges,
							'disabled'=>''
			),
			"product_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'number',
							'name'=>'qty',
							'id'=>'qty',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->qty,
							'disabled'=>''
			),
         "product_qty_for_out_stock_field"=>array(
							'label'=>"Qty for Item's Status to Become Out of Stock",
							'type'=>'number',
							'name'=>'qty_out',
							'id'=>'qty_out',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->qty_out,
							'disabled'=>''
			),
			"product_stock_availability_field"=>array(
							'label'=>'Stock Availability',
							'type'=>'select',
							'name'=>'stock_availability',
							'id'=>'stock_availability',
							'classes'=>'form-control',
							'placeholder'=>'stock_availability',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"In Stock",
							),(object)array(
							"id"=>"0",
							"name"=>"Out Of Stock",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->stock_availability
			),
			"product_material_field"=>array(
							'label'=>'Material',
							'type'=>'select',
							'name'=>'material',
							'id'=>'Material',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Test Material 1",
							),(object)array(
							"id"=>"0",
							"name"=>"Test Material 2",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->material
			),
			"product_related_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_related_product_shown',
							'id'=>'is_related_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->is_related_product_shown
			),
			"product_up_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_up_sell_product_shown',
							'id'=>'is_up_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->is_up_sell_product_shown
			),
			"product_cross_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_cross_sell_product_shown',
							'id'=>'is_cross_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->is_cross_sell_product_shown
			),
			"product_brands_field"=>array(
							'label'=>'Brands',
							'type'=>'select',
							'name'=>'product_brand',
							'id'=>'product_brands_field',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>$Products->product_brand
			),
			"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getSizes($id),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getColors($id),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
		   "submit_button_field"=>array(
							'label'=>'',
							'type'=>'submit',
							'name'=>'submit',
							'id'=>'submit',
							'classes'=>'btn btn-danger disableAfterCick',
							'placeholder'=>'',
							'value'=>'Save'
		)
         )
       ),
	    "return_data"=>array(
							'product_category'=>CustomFormHelper::getProductCategory($id),
							'attr'=>$attr,
							'image_html'=>$images,
							'product_images'=>$ProductImages->getImages($id)
							
							
		)
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
			
		  		 switch($level){
			 case 0;    /// general info tab
			 $request->validate([
				'name' => 'max:25|required|unique:products,name,'.$id.',id,isdeleted,0',
				'short_description' => 'required|max:200',
				'long_description' => 'required|max:2000',
				'sku' => 'required|max:25',
					'product_code' => 'required|max:25',
				'weight' => 'max:25',
				'status' => 'required|max:25',
				'default_image' => ''
             ]
        );
		
		
		  if ($request->hasFile('default_image')) {
			   $defualt_image = $request->file('default_image');
            $destinationPath = Config::get('constants.uploads.product_images');
            $file_name=$defualt_image->getClientOriginalName();

       $file_name= FIleUploadingHelper::UploadImage($destinationPath,$defualt_image,$file_name);
      if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		   return Redirect::back();
      }else{
		    $input['default_image']=$file_name;
	  }
		  }
		     
     

			$Products = new Products;
				
      if($Products->updateInfo($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return Redirect::route('edit_product_vdr', [base64_encode(0),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(0),base64_encode($id)]);
      }
     
	  
			 break;
			  case 1;  /// price tab
			$request->validate([
			'price' => 'required',
			'spcl_price' => '',
			'tax_class' => 'required'
			]
			);
			if($input['spcl_from_date']!='' || $input['spcl_to_date']!=''){
				$request->validate([
					'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
					'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
			]
			);
			}
		
		
			$Products = new Products;
      /* save the following details */
      if($Products->updatePrice($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 return Redirect::route('edit_product_vdr', [base64_encode(1),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(1),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 2;  /// categories tab
					$ProductCategories = new ProductCategories;
      if($ProductCategories->updateCategories($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_product_vdr', [base64_encode(2),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(2),base64_encode($id)]);
      }
			     break;
			   
			   
			    
			    case 3:    /// attributes tab
				 
				  if (array_key_exists("atr_color",$input))
					{
						$color_validator = Validator::make($request->all(),[
					'atr_color.*' => 'required',
					]);
						
						
					$size_validator = Validator::make($request->all(),[
					'atr_size.*' => 'required',
					]);
					
					if($color_validator->fails() && $size_validator->fails() ) {
						 return Redirect::back()->withErrors(['Size or color is mandatory']);
					}
					
					if($color_validator->fails() ) {
						 $this->validate($request, [
							'atr_size.*' => 'distinct|required',
							
							],[
							'atr_size.*.distinct' => 'Size  needs to be unique',
							'atr_size.*.required' => 'Size  is mandatory'
							]
							);
					}
						
						if($size_validator->fails() ) {
						 $this->validate($request, [
							'atr_color.*' => 'distinct|required',
							
							],[
							'atr_color.*.distinct' => 'Color  needs to be unique',
							'atr_color.*.required' => 'atr_color  is mandatory'
							]
							);
					}
							$this->validate($request, [
							'atr_qty.*' => 'required',
							
							],[
							'atr_qty.*.required' => 'Quantity  is mandatory'
							]
							);
							if($color_validator->fails()==0 && $size_validator->fails()==0) {
								for($i=0;$i<sizeof($input['atr_size'])-1;$i++){
								for($k=$i;$k<sizeof($input['atr_size'])-1;$k++){
								if($input['atr_size'][$i]==$input['atr_size'][$k+1] && $input['atr_color'][$i]==$input['atr_color'][$k+1]){
								return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
								} 
								}
								}
							}

							
					}
					
				 $ProductAttributes = new ProductAttributes;
			 if($ProductAttributes->updateAttributes($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return Redirect::route('edit_product_vdr', [base64_encode(3),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(3),base64_encode($id)]);
      }
				 break;
			   
			   
			    case 4;  /// images tab
				
		      
			
				$images_name=array();
							if (array_key_exists("product_images",$input))
							{
							foreach($input['product_images'] as $row){
							array_push($images_name,$row);
							}
							}
				
			
		
			
			if($request->hasfile('images'))
				 {
					foreach($request->file('images') as $image)
					{
						$destinationPath =Config::get('constants.uploads.product_images');
						$file_name=$image->getClientOriginalName();
						$fn= FIleUploadingHelper::UploadImage($destinationPath,$image,$file_name);	
						array_push($images_name,$fn);						
					}
				 }
		 
      if($ProductImages->updateImages($images_name,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		return Redirect::route('edit_product_vdr', [base64_encode(4),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(4),base64_encode($id)]);
      }
			   break;
			   
			   
			   case 5;   ///manage stock tab
			  $request->validate([
			'manage_stock' => '',
			'qty' => '',
			'qty_out' => '',
			'stock_availability' => ''
			]
			);
		
			$Products = new Products;
      if($Products->updateStock($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		return Redirect::route('edit_product_vdr', [base64_encode(5),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(5),base64_encode($id)]);
      }
			
			   break;
			   
			   
			   
			   
				 
				 case 6;  /// extra info tab
				
					
					
					$Products = new Products;
      if($Products->updateExtras($input,$id)){
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return Redirect::route('edit_product_vdr', [base64_encode(6),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(6),base64_encode($id)]);
      }
			     break;
				 
				  case 7;   /// meta data tab
			$request->validate([
			'meta_title' => 'max:160',
			'meta_description' => 'max:360',
				'meta_keyword' => 'max:255',
			]
			);
				$Products = new Products;
      if($Products->updatemetaInfo($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(7),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(7),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 8;  /// related product tab
			
				$ProductRelation = new ProductRelation;
				
				
      if($ProductRelation->updateRelation($input,$id,0)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(8),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(8),base64_encode($id)]);
      }
			   break;
			   
			   
			   
			   case 9; /// up sell tab
			
				$ProductRelation = new ProductRelation;
				
				
      if($ProductRelation->updateRelation($input,$id,1)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(9),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(9),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 10; /// cross sell tab
			
				$ProductRelation = new ProductRelation;
				
				
      if($ProductRelation->updateRelation($input,$id,2)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(10),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(10),base64_encode($id)]);
      }
			   break;
				 
				
				 }
		
	  }
        
		
		 return view('vendor.product.form_edit',['page_details'=>$page_details,'products_list'=>$products_list]);

   }
    public function addSellProduct(Request $request)
		{ 
		
			$error="NO";
		$input=$request->all();

		$request->validate([
                'price' => 'required',
                'spcl_price' => 'lt:price',
                'qty' => 'required'
			]
			);
			
// 			if($input['spcl_from_date']!='' || $input['spcl_to_date']!=''){
// 				$request->validate([
// 					'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
// 					'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
// 			]
// 			);
// 			}
			
			 if (array_key_exists("atr_color",$input))
					{
							$this->validate($request, [
							'atr_qty.*' => 'required',
							'atr_size.*' => 'required',
							'atr_color.*' => 'required'
							],[
							'atr_qty.*.required' => 'Quantity  is mandatory',
							'atr_size.*.required' => 'Size is mandatory',
							'atr_color.*.required' => 'Color is mandatory'
							]
							);
							

						for($i=0;$i<sizeof($input['atr_size'])-1;$i++){
							for($k=$i;$k<sizeof($input['atr_size'])-1;$k++){
							if($input['atr_size'][$i]==$input['atr_size'][$k+1] && $input['atr_color'][$i]==$input['atr_color'][$k+1]){
								$output="same size and color combination can not be entered";
								$error="YES";
									echo json_encode(array(
									"Msg"=>$output,
									"Error"=>$error
									));
									die();
							} 
							
							}
						}	
					}
					
					$vdr_id=Auth::user()->id;
			
			$isAlready=DB::table('vendor_existing_product')
			->select('vendor_existing_product.id')
			->where('vendor_existing_product.vendor_id',$vdr_id)
			->where('vendor_existing_product.product_id',$request->product_id)
			->first();
			
			if($isAlready){
				 $output="You have this product already";
				 $error="YES";
			} else{
				
				
							$prd_info=DB::table('products')
							->where('products.id',$request->product_id)
							->first();
							
			
						$Products= new Products;
						$Products->vendor_id =$vdr_id;
						$Products->name=$prd_info->name;
						$Products->short_description=$prd_info->short_description;
						$Products->long_description=$prd_info->long_description;
						$Products->sku=$prd_info->sku;
						$Products->weight=$prd_info->weight;
						$Products->visibility=$prd_info->visibility;
						$Products->hsn_code=$prd_info->hsn_code;
						$Products->prd_sts=$prd_info->prd_sts;
						$Products->tax=$prd_info->tax;
						$Products->price=$prd_info->price;
						$Products->spcl_price=$prd_info->spcl_price;
				// 		$Products->spcl_from_date=($input['spcl_from_date']!='')?date("Y-m-d", strtotime($input['spcl_from_date'])):null;
				// 		$Products->spcl_to_date=($input['spcl_from_date']!='')?date("Y-m-d", strtotime($input['spcl_from_date'])):null;
						$Products->tax_class=$prd_info->tax_class;
						$Products->meta_title=$prd_info->meta_title;
						$Products->meta_keyword=$prd_info->meta_keyword;
						$Products->meta_description=$prd_info->meta_description;
						$Products->default_image=$prd_info->default_image;
						$Products->qty=$prd_info->qty;
						$Products->manage_stock=$prd_info->manage_stock;
						$Products->qty_out=$prd_info->qty_out;
						$Products->stock_availability=$prd_info->stock_availability;
						$Products->product_brand=$prd_info->product_brand;
						$Products->material=$prd_info->material;
						$Products->is_related_product_shown=$prd_info->is_related_product_shown;
						$Products->is_up_sell_product_shown=$prd_info->is_up_sell_product_shown;
						$Products->is_cross_sell_product_shown=$prd_info->is_cross_sell_product_shown;
						$Products->isexisting=1;
						$Products->product_type=$prd_info->product_type;;
						$Products->status=0;
						
						 $Products->save();
						
					
			 if($Products->save()){
			    $ext_prd_id= $Products->id;
				 	DB::table('vendor_existing_product')->insert(
					[
					'vendor_id' =>$vdr_id,
					'product_id' =>$ext_prd_id,
					'master_product_id' =>$request->product_id
					]
					);
					
							$pr_cat=new ProductCategories;
							$cats['cat']=$pr_cat->getCategories($request->product_id);
							$pr_cat->updateCategories($cats,$Products->id);
							
							$pr_images=new ProductImages;
							$images=$pr_images->getImages($request->product_id);
							$imgs=array();
							
							foreach($images as $row){
								array_push($imgs,$row['image']);
							}
							
							$config_images=DB::table('product_configuration_images')->where('product_id',$Products->id)->get();
							$whole_img=array();
							foreach($config_images as $config_img){
							DB::table('product_configuration_images')->insert(
							    array(
                                            "product_id"=>$ext_prd_id,
                                            "color_id"=>$config_img->color_id,
                                            "product_config_image"=>$config_img->product_config_image
							        )
							    );
							}
						
							
						      $images=$pr_images->updateImages($imgs,$Products->id);
							$pd_atr=new ProductAttributes;
							$pd_atr->updateAttributes($input,$Products->id);
							
				
					
				 $output="Product Copied Saved Successfully!";
				 $error="NO";
			 }else{
				 $output="Something went wrong , Please Try Again!";
				 $error="YES";
			 }
			}
				
			
			 echo json_encode(array(
			 "Msg"=>$output,
			 "Error"=>$error
			 ));
			
	    }
	public function del(Request $request)
        {
            $id=base64_decode($request->id);
            $res=vendor_products::where('id',$id);   
            // dd($res);
            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }
                    return Redirect::route('vendor_product');
        }
	
	
}
