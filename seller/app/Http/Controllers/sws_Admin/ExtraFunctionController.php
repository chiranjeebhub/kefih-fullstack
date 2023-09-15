<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Slider;
use App\ProductSlider;
use App\ProductRating;
use App\Products;
use App\Pages;
use App\Orders;
use App\Brands;
use App\Helpers\CustomFormHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Exports\ReportExport;
use App\Exports\SubcriberExport;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Validator;
use URL;
use DB;
use Config;
class ExtraFunctionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
      public function edit_faq(Request $request){
                    $description_id=base64_decode($request->faq_id);
            $description_data=DB::table('tbl_faq')->where('id',$description_id)->first();
        
			 $page_details=array(
         	"Title"=>"Update Faq",
	     "Method"=>"2",
       "Box_Title"=>"Update Faq",
       "Action_route"=>route('edit_faq',[base64_encode($description_id)]),
        	"back_route"=>route('faqs'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Question',
            'type'=>'text',
            'name'=>'question',
            'id'=>'question',
            'classes'=>'form-control',
            'placeholder'=>'Question',
            'value'=>$description_data->fld_faq_question,
			'disabled'=>''
           ),
           "description_field"=>array(
              'label'=>'Answer',
            'type'=>'text',
            'name'=>'answer',
            'id'=>'answer',
            'classes'=>'form-control',
            'placeholder'=>'Answer',
           'value'=>$description_data->fld_faq_answer,
			'disabled'=>''
           ),
            "faq_type_field"=>array(
				'label'=>'FAQ FOR',
				'type'=>'radio',
				'name'=>'fld_faq_type',
				'id'=>'fld_faq_type',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"USER",
							),(object)array(
							"id"=>"1",
							"name"=>"Vednor",
							)
							),
				'disabled'=>'',
				'selected'=>$description_data->fld_faq_type
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
			"images"=>array(
                  'description_image'=>''
            )
         )
       )
     );
     
      if ($request->isMethod('post')) {
		 
		$input=$request->all();
             $request->validate([
                    'answer' => 'required|max:255',
                    'question' => 'required|max:255'
                
            ]);
			
    $insert_data=array(
            "fld_faq_question"=>$input['question'],
            "fld_faq_answer"=>$input['answer'],
             "fld_faq_type"=>$input['fld_faq_type']
    );
	 
	  $res=DB::table('tbl_faq')->where('id',$description_id)->update($insert_data);
   if($res){
	   MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
   } else{
      MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
   }
  
   return Redirect::back();
	 }
			return view('admin.faq.form',['page_details'=>$page_details]);
    }
     public function delete_faq(Request $request){
        	$id=base64_decode($request->faq_id);
        $res=DB::table('tbl_faq')->where('id',$id)->delete();
        
            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    
    }
    	public function sts_faq(Request $request)
    {
            $id=base64_decode($request->faq_id);
            $sts=base64_decode($request->sts);

            $res=DB::table('tbl_faq')
						->where('id',$id)
						->update(['fld_faq_status' => ($sts==0) ? 1 : 0]);;

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
     public function add_faq(Request $request){
        		 $page_details=array(
       "Title"=>"Add Faq",
	     "Method"=>"1",
       "Box_Title"=>"Add Faq",
       "Action_route"=>route('add_faq'),
       	"back_route"=>route('faqs'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Question',
            'type'=>'text',
            'name'=>'question',
            'id'=>'question',
            'classes'=>'form-control',
            'placeholder'=>'Question',
            'value'=>'',
			'disabled'=>''
           ),
           "description_field"=>array(
              'label'=>'Answer',
            'type'=>'text',
            'name'=>'answer',
            'id'=>'answer',
            'classes'=>'form-control',
            'placeholder'=>'Answer',
            'value'=>'',
			'disabled'=>''
           ),
           "faq_type_field"=>array(
				'label'=>'FAQ FOR',
				'type'=>'radio',
				'name'=>'fld_faq_type',
				'id'=>'fld_faq_type',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"USER",
							),(object)array(
							"id"=>"1",
							"name"=>"Vednor",
							)
							),
				'disabled'=>'',
				'selected'=>0
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
			"images"=>array(
                  'description_image'=>''
            )
         )
       )
     );

	 if ($request->isMethod('post')) {
		 
		$input=$request->all();
             $request->validate([
                    'question' => 'required:max:255',
                    'answer' => 'required|max:255'
                
            ]);
			
    $insert_data=array(
        "fld_faq_question"=>$input['question'],
        "fld_faq_answer"=>$input['answer'],
         "fld_faq_type"=>$input['fld_faq_type']
    );
	 
	 
	  $res=DB::table('tbl_faq')->insert($insert_data);
   if($res){
	   MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
   } else{
      MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
   }
  
   return Redirect::back();
	 }
		return view('admin.faq.form',['page_details'=>$page_details]);
    
    }
     public function faqs(Request $request){
        	$prd_id=base64_decode($request->id);
        	 $product_info=Products::where('id',$prd_id)->first();
        $description_data=DB::table('tbl_faq')->get();
        
         $route=route('faqs');
        
        
        		$page_details=array(
			"Title"=>"Faq(s) ",
			"Box_Title"=>"Faq(s)",
			"Action_route"=>route('add_faq'),
            "back_route"=>$route,
			"reset_route"=>''
		);
			return view('admin.faq.list',['description_data'=>$description_data,'page_details'=>$page_details,'prd_id'=>$prd_id]);
    }
     public function ex_reports(Request $request)
    {
                $type= base64_decode($request->type);
                $daterange= $request->daterange;
                $cat= $request->cat;
            return Excel::download(new ReportExport($type,$daterange,$cat), 'Reports'.date('d-m-Y H:i:s').'.csv');
    }
     public function reports(Request $request){
	$type=base64_decode($request->type);
	$daterange=$request->daterange;
	$cat=$request->cat;

	$export_route=URL::to('admin/ex_reports')."/".$request->type;
        if($daterange!=''){
            $export_route.='/'.$daterange.'/'.$cat;
            } 
    
        
        $page_details=array(
            "Title"=>"Reports",
            "Box_Title"=>"",
            "search_route"=>URL::to('admin/reports')."/".$request->type,
            "reset_route"=>URL::to('admin/reports')."/".$request->type,
            "export_route"=>$export_route,
			 "Form_data"=>array(
				 "Form_field"=>array(
                    "select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'category_id',
                    'id'=>'category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',$cat)
                    )
					)
			)
		);
         
        if(@$type==0){
		$products=Orders::
               select('products.id','products.vendor_id','products.name','products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id');
             if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('order_details.order_status','3')->groupBy('products.id')->orderBy('total_sales','desc')->paginate(30); 	
		}
       
        if(@$type==1){
		$products=Orders::
               select('products.id','products.vendor_id','products.name','products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges','orders_shipping.order_shipping_zip','orders_shipping.order_shipping_city',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->join('orders_shipping','orders_shipping.order_id','order_details.order_id');
            if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('order_details.order_status','3')->groupBy('orders_shipping.order_shipping_zip')->orderBy('total_sales','desc')->paginate(30); 	
            
          } 
       
        if(@$type==2){
		$products=Orders::
               select('products.id','products.vendor_id','products.name','products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges','orders_shipping.order_shipping_zip','orders_shipping.order_shipping_city',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->join('orders_shipping','orders_shipping.order_id','order_details.order_id');
            if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            
            $products=$products->where('order_details.order_status','5')->groupBy('orders_shipping.order_shipping_zip')->orderBy('total_sales','desc')->paginate(30); 
         
		} 
		
		$repo_type = array("0", "1", "2");
		if (in_array($type, $repo_type))
		{
			return view('admin.extrafeature.reports.list',
			[
				'Order'=>$products,
				'page_details'=>$page_details,
				'daterange'=>$daterange
			]);
		}
		
		if(@$type==3){
		$products=Orders::
               select('vendors.id','vendors.username','order_details.order_shipping_charges',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
			->join('products','products.id','order_details.product_id')
			->join('vendors','vendors.id','products.vendor_id');
			
                            if( ($cat!=0)){
                                      $products =$products
                            		->join('product_categories','product_categories.product_id','=','products.id')
                            		->join('categories','categories.id','=','product_categories.cat_id');
                            			$products =$products->where('product_categories.cat_id',$cat);
                            } 
            if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('order_details.order_status','3')->groupBy('vendors.id')->orderBy('total_sales','desc')->paginate(30); 	
			
			return view('admin.extrafeature.reports.vendor_list',
			[
                'Order'=>$products,
                'page_details'=>$page_details,
                'daterange'=>$daterange
				
			]);
		} 
		
        
}
////////////////////////////////////////////
     public function shipping_charges(Request $request){
         
           if ($request->isMethod('post')) {
    $input=$request->all();

    $request->validate([
                'cart_total' => 'required|numeric|min:1|not_in:0'
    
            ]
			);  
			 $res=DB::table('store_shipping_charges')->update(array(
			     'cart_total'=>$input['cart_total']
			     ));
		  
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger','Nothing changed',$request);
      }
      return Redirect::back();
    
           }
       
       $shipping_chargs_data=DB::table('store_shipping_charges')->first();
      
         	$page_details=array(
            "Title"=>"Shipping Charges",
            "Box_Title"=>"Shipping Charges",
            "search_route"=>'',
            "method"=>0,
            "Action_route"=>route('shipping_charges'),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "offer_name"=>array(
                'label'=>'Add Shipping charges when cart total is less than',
                'type'=>'number',
                'name'=>'cart_total',
                'id'=>'cart_total',
                'classes'=>'form-control',
                'placeholder'=>'Cart Total',
                'value'=>$shipping_chargs_data->cart_total,
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
            )
         )
       )
     );
return view('admin.extrafeature.shipping_charges.form',['page_details'=>$page_details]);
     }
      public function selectedReview(Request $request){
          $input=$request->all();
          $ids=explode(',',$input['product_ids']);
          if($input['mode']==0){
             $res= DB::table('product_rating')->whereIn('id',$ids)->delete();
          } else{
               $res= DB::table('product_rating')->whereIn('id',$ids)->update(array(
                   "isActive"=>1
                   )); 
          }
           
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
      }
      
      public function reasons(Request $request){
          
			$page_details=array(
								"Title"=>"Reasons",
								"Box_Title"=>"Reasons",
								"search_route"=>'',
								"Action_route"=>''
							 );
			
			$status=$request->status;
			$reasons_type=$request->reasons_type;
			
			$reasons=DB::table('order_cancel_reason')
						->where('isdeleted',0);
			 
			 if($reasons_type=='all')
			 {
				 $reasons_type='';
			 }
			 if($status=='all')
			 {
				 $status='';
			 }
			 
			 if($reasons_type=='0'){
				 $reasons->where('reason_type',0); //cancel
			 }elseif($reasons_type=='1'){
				 $reasons->where('reason_type',1); //return
			 }
			 
			 if($status!='')
			 {
				 $reasons->where('status',$status); 
			 }
			 
			$reasons=$reasons->paginate(50);
			
			return view('admin.extrafeature.reasons.list',['reasons'=>$reasons,'reason_type'=>$request->reasons_type,'page_details'=>$page_details,'status'=>$status]);
     }
     
      public function add_reason(Request $request){
            if ($request->isMethod('post')) {
    $input=$request->all();
    $data=array();

    $request->validate([
                'reason' => 'required|max:254',
                'reason_for' => 'required',
                'cat.*' => 'required',
                
            ],[
                  'cat.*.required' => 'Please select category to continue',
                ]
			);  
			
			$data=array(
                "reason"=>$input['reason'],
                "reason_type"=>$input['reason_for']
			    );

  
 		 $res=DB::table('order_cancel_reason')
                   ->insert($data);
              
              if($res){
                 $reason_id=DB::getPdo()->lastInsertId();
        $obj=new Products();
        $obj->updateReason($input,$reason_id);
        MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
    
           }
        
         	$page_details=array(
            "Title"=>"Add Reason",
            "Box_Title"=>"Add Reason",
            "search_route"=>'',
            "method"=>0,
              "cats"=>array(),
            "Action_route"=>route('add_reason'),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "offer_name"=>array(
                'label'=>'Reason *',
                'type'=>'text',
                'name'=>'reason',
                'id'=>'reason',
                'classes'=>'form-control',
                'placeholder'=>'Reason',
                'value'=>'',
                'disabled'=>''
           ),
           
         "for_category_or_brand"=>array(
			'label'=>'Reason For ',
				'type'=>'radio',
				'name'=>'reason_for',
				'id'=>'reason_for',
				'classes'=>'radioSelection  offerType',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Cancel",
							),(object)array(
							"id"=>"1",
							"name"=>"Return",
							)
							),
				'disabled'=>'',
				'selected'=>0
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
       )
     );
return view('admin.extrafeature.reasons.form',['page_details'=>$page_details]);
     }
      public function edit_reason(Request $request){
          $id=base64_decode($request->id);
            $obj=new Products();
            if ($request->isMethod('post')) {
    $input=$request->all();
    $data=array();

    $request->validate([
                'reason' => 'required|max:254',
                'reason_for' => 'required',
                'cat.*' => 'required',
                
            ],[
                  'cat.*.required' => 'Please select category to continue',
                ]
			);  
			
			$data=array(
                "reason"=>$input['reason'],
                "reason_type"=>$input['reason_for']
			    );

  
 		 $res=DB::table('order_cancel_reason')
                    ->where('id',$id)
                   ->update($data);
             $res= $obj->updateReason($input,$id);
              if($res){
                
      
        
        MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
    
           }
           
          $reason_data = DB::table('order_cancel_reason')->where('id',$id)->first();
          
         	$page_details=array(
            "Title"=>"Edit Reason",
            "Box_Title"=>"Edit Reason",
            "search_route"=>'',
            "method"=>0,
             "cats"=> $obj->getReasonCat($id),
            "Action_route"=>route('edit_reason',base64_encode($id)),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "offer_name"=>array(
                'label'=>'Reason *',
                'type'=>'text',
                'name'=>'reason',
                'id'=>'reason',
                'classes'=>'form-control',
                'placeholder'=>'Reason',
                'value'=>$reason_data->reason,
                'disabled'=>''
           ),
           
         "for_category_or_brand"=>array(
			'label'=>'Reason For ',
				'type'=>'radio',
				'name'=>'reason_for',
				'id'=>'reason_for',
				'classes'=>'radioSelection  offerType',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Cancel",
							),(object)array(
							"id"=>"1",
							"name"=>"Return",
							)
							),
				'disabled'=>'',
				'selected'=>$reason_data->reason_type
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
       )
     );
return view('admin.extrafeature.reasons.form',['page_details'=>$page_details]);
     }
	 
	public function reasons_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=DB::table('order_cancel_reason')
						->where('id',$id)
						->update(['status' => ($sts==0) ? 1 : 0]);;

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	
      public function delete_reason(Request $request){
          $id=base64_decode($request->id);
          
          $res=DB::table('order_cancel_reason')
                ->where('id',$id)
              ->update(
                  array('isdeleted'=>1)
                  );
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
         
     }
     
      public function offers(Request $request){
          $page_details=array(
       "Title"=>"Offers",
       "Box_Title"=>"Offers",
		"search_route"=>'',
		"Action_route"=>''
     );
	 
	$offers=DB::table('offer_categories')
	->paginate(50);
return view('admin.extrafeature.offers.list',['offers'=>$offers,'page_details'=>$page_details]);
     }
     
     public function add_offer(Request $request){
         
           if ($request->isMethod('post')) {
    $input=$request->all();
    $data=array();
	
	$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.advertise');
			$file_name2=$banner_image->getClientOriginalName();

	  
		$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
	  if($file_name2==''){
		MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		 return Redirect::back();
	  }
	  
	  if ($request->hasFile('mobile_image')) {
			$mobile_image = $request->file('mobile_image');
			$destinationPath2 =  Config::get('constants.uploads.advertise');
			$file_name3=$mobile_image->getClientOriginalName();

			$file_name3= FIleUploadingHelper::UploadImage($destinationPath2,$mobile_image,$file_name3);
			  if($file_name3==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
		}
		
  if($input['for_category_or_brand']==0){
    $request->validate([
                'offer_name' => 'required|max:254',
                'discount' => 'required|min:1|max:99',
                'category_field' => 'required',
				//'banner_image' => 'required|mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').'',
                //'mobile_image' => 'mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''

                
            ]
			);  
			
			$data=array(
                "offer_name"=>$input['offer_name'],
                "offer_discount"=>$input['discount'],
                "offer_below_above"=>$input['discount_below_or_above'],
                "offer_zone_type"=>$input['for_category_or_brand'],
                "categories_id"=>$input['category_field'],
				"image"=>@$file_name2,
				"mobile_image"=>@$file_name3
			    );
  } else{
   $request->validate([
                'offer_name' => 'required|max:254',
                'discount' => 'required|min:1|max:99',
                'brand_field' => 'required',
				//'banner_image' => 'required|mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').'',
                //'mobile_image' => 'mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''

                
            ]
			);  
			
			
				$data=array(
                "offer_name"=>$input['offer_name'],
                "offer_discount"=>$input['discount'],
                "offer_below_above"=>$input['discount_below_or_above'],
                "offer_zone_type"=>$input['for_category_or_brand'],
                "categories_id"=>$input['brand_field'],
				"image"=>@$file_name2,
				"mobile_image"=>@$file_name3
			    );
  }
  
 		 $res=DB::table('offer_categories')
                   ->insert($data);
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
    
           }
          $brands = Brands::select('id','name')->where('isdeleted', 0)->get();
         	$page_details=array(
            "Title"=>"Add Offer",
            "Box_Title"=>"Add Offer",
            "search_route"=>'',
            "method"=>"1",
            "Action_route"=>route('add_offer'),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "offer_name"=>array(
                'label'=>'Offer Name',
                'type'=>'text',
                'name'=>'offer_name',
                'id'=>'offer_name',
                'classes'=>'form-control',
                'placeholder'=>'Name',
                'value'=>'',
                'disabled'=>''
           ),
		   "banner_file_field"=>array(
			          'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "mobile_file_field"=>array(
			    'label'=>'Mobile Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'mobile_image',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
             
           "discount"=>array(
                'label'=>'Discount (%)',
                'type'=>'number',
                'name'=>'discount',
                'id'=>'discount',
                'classes'=>'form-control',
                'placeholder'=>'0',
                'value'=>'',
                'disabled'=>''
           ),
           
           "discount_below_or_above"=>array(
				'label'=>'Offer Apply on',
				'type'=>'radio',
				'name'=>'discount_below_or_above',
				'id'=>'discount_below_or_above',
				'classes'=>'radioSelection',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Smaller or Equal to (>=)",
							),(object)array(
							"id"=>"1",
							"name"=>"Greater or Equal to (<=)",
							)
							),
				'disabled'=>'',
				'selected'=>0
				),
              "for_category_or_brand"=>array(
				'label'=>'Offer Type',
				'type'=>'radio',
				'name'=>'for_category_or_brand',
				'id'=>'for_category_or_brand',
				'classes'=>'radioSelection  offerType',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Category Wise",
							),(object)array(
							"id"=>"1",
							"name"=>"Brand Wise",
							)
							),
				'disabled'=>'',
				'selected'=>0
				),
			
			
			"category_field"=>array(
              'label'=>'Category',
            'type'=>'select_with_inner_loop',
            'name'=>'category_field',
            'id'=>'category_field',
            'classes'=>'custom-select form-control catClass',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChilds(1,'',0)
           ),
				"brand_field"=>array(
							'label'=>'Brand',
							'type'=>'select',
							'name'=>'brand_field',
							'id'=>'brand_field',
							'classes'=>'form-control brandClass',
							'placeholder'=>'',
							'value'=>$brands,
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
			 "images"=>array(
                  'banner_image'=>'',
                  'mobile_image'=>''
            )
         )
       )
     );
return view('admin.extrafeature.offers.form',['page_details'=>$page_details]);
     }
     
     
      public function edit_offer(Request $request){
             $id=base64_decode($request->id);
            $offer_data=DB::table('offer_categories')->where('id',$id)->first();
           if($request->isMethod('post')) {
			$input=$request->all();
	
			$data=array();
			if ($request->hasFile('banner_image')) {
				$banner_image = $request->file('banner_image');
				$destinationPath2 =  Config::get('constants.uploads.advertise');
				$file_name2=$banner_image->getClientOriginalName();

				$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
				  if($file_name2==''){
					MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
					 return Redirect::back();
				  }
				$image = $file_name2;
				$data["image"]=$image;
			}
			if ($request->hasFile('mobile_image')) {
				
				$mobile_image = $request->file('mobile_image');
				$destinationPath2 =  Config::get('constants.uploads.advertise');
				$file_name3=$mobile_image->getClientOriginalName();

				$file_name3= FIleUploadingHelper::UploadImage($destinationPath2,$mobile_image,$file_name3);
				  if($file_name3==''){
					MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
					 return Redirect::back();
				  }
				$mobile_image = $file_name3;
				$data["mobile_image"]=$mobile_image;
			}
		
    
  if($input['for_category_or_brand']==0){
    $request->validate([
                'offer_name' => 'required|max:254',
                'discount' => 'required|min:1|max:99',
                'category_field' => 'required',
				//'banner_image' => 'mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').'',
               //'mobile_image' => 'mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''

                
            ]
			);  
			
			$data["offer_name"]=$input['offer_name'];
			$data["offer_discount"]=$input['discount'];
			$data["offer_below_above"]=$input['discount_below_or_above'];
			$data["offer_zone_type"]=$input['for_category_or_brand'];
			$data["categories_id"]=$input['category_field'];
			
			
  } else{
   $request->validate([
                'offer_name' => 'required|max:254',
                'discount' => 'required|min:1|max:99',
                'brand_field' => 'required',
				//'banner_image' => 'mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').'',
               //'mobile_image' => 'mimes:jpg,jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''

                
            ]
			);  
			
			
				$data["offer_name"]=$input['offer_name'];
				$data["offer_discount"]=$input['discount'];
				$data["offer_below_above"]=$input['discount_below_or_above'];
				$data["offer_zone_type"]=$input['for_category_or_brand'];
				$data["categories_id"]=$input['brand_field'];
  }
  
 		 $res=DB::table('offer_categories')
 		            ->where('id',$id)
                   ->update($data);
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
    
           }
          $brands = Brands::select('id','name')->where('isdeleted', 0)->get();
         	$page_details=array(
            "Title"=>"Edit Offer",
            "Box_Title"=>"Edit Offer",
            "search_route"=>'',
            "method"=>2,
            "Action_route"=>route('edit_offer', [base64_encode($id)]),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "offer_name"=>array(
                'label'=>'Offer Name',
                'type'=>'text',
                'name'=>'offer_name',
                'id'=>'offer_name',
                'classes'=>'form-control',
                'placeholder'=>'Name',
                'value'=>$offer_data->offer_name,
                'disabled'=>''
           ),
		   "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "mobile_file_field"=>array(
			        'label'=>'Mobile Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'mobile_image',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
             
           "discount"=>array(
                'label'=>'Discount (%)',
                'type'=>'number',
                'name'=>'discount',
                'id'=>'discount',
                'classes'=>'form-control',
                'placeholder'=>'0',
				'value'=>$offer_data->offer_discount,
                'disabled'=>''
           ),
           
           "discount_below_or_above"=>array(
				'label'=>'Offer Apply On <br>',
				'type'=>'radio',
				'name'=>'discount_below_or_above',
				'id'=>'discount_below_or_above',
				'classes'=>'radioSelection',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Smaller or Equal to (>=)",
							),(object)array(
							"id"=>"1",
							"name"=>"Greater or Equal to (<=)",
							)
							),
				'disabled'=>'',
				'selected'=>$offer_data->offer_below_above
				),
              "for_category_or_brand"=>array(
				'label'=>'Offer Type',
				'type'=>'radio',
				'name'=>'for_category_or_brand',
				'id'=>'for_category_or_brand',
				'classes'=>'radioSelection  offerType',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Category Wise",
							),(object)array(
							"id"=>"1",
							"name"=>"Brand Wise",
							)
							),
				'disabled'=>'',
				'selected'=>$offer_data->offer_zone_type
				),
			
			
			"category_field"=>array(
              'label'=>'Category',
            'type'=>'select_with_inner_loop',
            'name'=>'category_field',
            'id'=>'category_field',
            'classes'=>'custom-select form-control catClass',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChilds(1,'',$offer_data->categories_id)
           ),
				"brand_field"=>array(
							'label'=>'Brand',
							'type'=>'select',
							'name'=>'brand_field',
							'id'=>'brand_field',
							'classes'=>'form-control brandClass',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>$offer_data->categories_id
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
			 "images"=>array(
                  'banner_image'=>$offer_data->image,
                  'mobile_image'=>$offer_data->mobile_image
            )
         )
       )
     );
return view('admin.extrafeature.offers.form',['page_details'=>$page_details]);
      }
      public function delete_offer(Request $request){
           $id=base64_decode($request->id);
          
          $res=DB::table('offer_categories')
                ->where('id',$id)
              ->delete();
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
      }
     public function rating_review(Request $request){
         	$page_details=array(
       "Title"=>"Rating & Reviews",
       "Box_Title"=>"Rating & Reviews",
		"search_route"=>'',
		"reset_route"=>''
     );
	 
	$reviews=DB::table('product_rating')
	->select('product_rating.*','products.name')
        ->join('products','product_rating.product_id','products.id')
        ->where('product_rating.isActive',0)
	->paginate(50);
return view('admin.reviews.list',['reviews'=>$reviews,'page_details'=>$page_details]);
     }
     public function edit_rating_review (Request $request){
             $product_id=base64_decode($request->product_id);
        $rating_id=base64_decode($request->rating_id);
        
           if ($request->isMethod('post')) {
               $request->validate([
                'review_field' => 'max:254'
                ],[
                    'review_field.max'=>'Review can not be exceed 255 length'
                    ]
			);
    $input=$request->all();
    $res=ProductRating::where('id',$rating_id)
        ->where('product_id',$product_id)
        ->update(
            array("review"=>$input['review_field'])
            );
     if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
    return Redirect::route('edit_rating_review', [base64_encode($product_id),base64_encode($rating_id)]);
           }
    
        
        $rating_data=ProductRating::where('id',$rating_id)
        ->where('product_id',$product_id)
        ->first();
        
        $product_data=Products::where('id',$product_id)
         ->first();
      
      $page_details=array(
       "Title"=>"Update Review & Rating",
       "Box_Title"=>"Update Review & Rating",
		"search_route"=>'',
		"Action_route"=>route('edit_rating_review', [base64_encode($product_id),base64_encode($rating_id)]),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "review_field"=>array(
                'label'=>'Review',
                'type'=>'text',
                'name'=>'review_field',
                'id'=>'review_field',
                'classes'=>'form-control',
                'placeholder'=>'',
                'value'=>$rating_data->review,
                'disabled'=>''
           ),
           "rating_field"=>array(
                'label'=>'Review',
                'type'=>'text',
                'name'=>'review_field',
                'id'=>'review_field',
                'classes'=>'form-control',
                'placeholder'=>'',
                'value'=>$rating_data->rating,
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
            )
         )
       )
     );
return view('admin.reviews.form',[
    'page_details'=>$page_details,
    'product_data'=>$product_data,
    'rating_data'=>$rating_data
    ]);
     }
     public function store_info(Request $request){
         
         if ($request->isMethod('post')) {
    $input=$request->all();
    
          $request->validate([
                'store_name' => 'required|max:254',
                'store_phone' => 'required|max:10',
                'store_address' => 'required|max:254',
                'store_email' => 'required|email|max:254',
                'facebook_url' => 'max:254',
                'twiter_url' => 'max:254',
                'linkedin_url' => 'max:254',
                'youtube_url' => 'max:255',
                'instagram_url' => 'max:255',
                
            ]
			);
			$data=array(
            'name'=>$input['store_name'],
            'phone'=>$input['store_phone'],
            'address'=>$input['store_address'],
            'email'=>$input['store_email'],
            'facebook_url'=>$input['facebook_url'],
            'twiter_url'=>$input['twiter_url'],
            'linkedin_url'=>$input['linkedin_url'],
            'youtube_url'=>$input['youtube_url'],
            'insta_url'=>$input['instagram_url']
			    );
		$res=	DB::table('store_info')->update($data);
		 if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
         }
         $store_info=DB::table('store_info')->first();
         $page_details=array(
       "Title"=>"Store Info",
       "Box_Title"=>"Store Info",
		"search_route"=>'',
		"Action_route"=>route('store_info'),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "store_fb_url_field"=>array(
                'label'=>'FB Url',
                'type'=>'text',
                'name'=>'facebook_url',
                'id'=>'facebook_url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$store_info->facebook_url,
                'disabled'=>''
           ),
             
           "store_tw_url_field"=>array(
                'label'=>'Twiiter Url',
                'type'=>'text',
                'name'=>'twiter_url',
                'id'=>'twiter_url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$store_info->twiter_url,
                'disabled'=>''
           ),
             
           "store_link_url_field"=>array(
                'label'=>'Linked Url',
                'type'=>'text',
                'name'=>'linkedin_url',
                'id'=>'linkedin_url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$store_info->linkedin_url,
                'disabled'=>''
           ),
            "store_insta_url_field"=>array(
                'label'=>'Instagram Url',
                'type'=>'text',
                'name'=>'instagram_url',
                'id'=>'instagram_url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$store_info->insta_url,
                'disabled'=>''
           ),
            "store_youtube_url_field"=>array(
                'label'=>'Youtube Url',
                'type'=>'text',
                'name'=>'youtube_url',
                'id'=>'youtube_url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$store_info->youtube_url,
                'disabled'=>''
           ),
           "store_name_field"=>array(
                'label'=>'Store name',
                'type'=>'text',
                'name'=>'store_name',
                'id'=>'store_name',
                'classes'=>'form-control',
                'placeholder'=>'Name',
                'value'=>$store_info->name,
                'disabled'=>''
           ),
            "store_phone_field"=>array(
			   
                 'label'=>'Store Phone',
                'type'=>'text',
                'name'=>'store_phone',
                'id'=>'store_phone',
                'classes'=>'form-control',
                'placeholder'=>'Phone',
                  'value'=>$store_info->phone,
                'disabled'=>''
               ),
               "store_email_field"=>array(
			   
                 'label'=>'Store Email',
                'type'=>'text',
                'name'=>'store_email',
                'id'=>'store_email',
                'classes'=>'form-control',
                'placeholder'=>'Email',
                  'value'=>$store_info->email,
                'disabled'=>''
               ),
               
                "store_address_field"=>array(
			   
                    'label'=>'Store Address',
                    'type'=>'text',
                    'name'=>'store_address',
                    'id'=>'store_address',
                    'classes'=>'form-control',
                    'placeholder'=>'Address',
                     'value'=>$store_info->address,
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
            )
         )
       )
     );
return view('admin.extrafeature.site_info.form',[
    'page_details'=>$page_details
    ]);
     }
	 
    public function subscriber(Request $request)
    {
        
		$str=$request->str;
		$status=$request->status;
		
		if($str=='all')
		{
			$str='';
		}
		
		if($status=='all')
		{
			$status='';
		}
		
		$export_route=URL::to('admin/subscriber_export');
                if($str!=''){
                $export_route.='/'.$str.'/'.$status;
                }
		$page_details=array(
		   "Title"=>"Subscribers",
		   "Box_Title"=>"Subscribers",
			"search_route"=>URL::to('admin/subscriber_search'),
			"reset_route"=>route('subscribers'),
			 "export_route"=>$export_route
		 );
		 
		 if($str!='' && $status!='')
		 {
			 $subscribers=DB::table('subscription')->where('email',$str)->where('status',$status)->paginate(50);
		 }else if($str!=''){
			 $subscribers=DB::table('subscription')->where('email',$str)->paginate(50);
		 }else if($status!=''){
			 $subscribers=DB::table('subscription')->where('status',$status)->paginate(50);
		 }else{
			 $subscribers=DB::table('subscription')->paginate(50);
		 }
		
		
		return view('admin.customers.subscribers',['subscribers'=>$subscribers,'page_details'=>$page_details,'status'=>$status]);
    }
	 public function subscriber_export(Request $request)
    {
           
            $str= $request->str;
             $status= $request->status;
            return Excel::download(new SubcriberExport($str,$status), 'Subscriber'.date('d-m-Y H:i:s').'.csv');
    }
	public function subscriber_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=DB::table('subscription')->where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	
	public function delete_subscriber(Request $request){
           $id=base64_decode($request->id);
           
          $res=DB::table('subscription')
                ->where('id',$id)
				->delete();
              
              if($res){
				  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			  } else{
				   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			  }
		
		return Redirect::back();
      }
	
       public function save_offer_product(Request $request){
           $input=$request->all();
           $products=explode(",",$input['product_ids']);
           $prd_size=sizeof($products);
           if($prd_size>0){
               $slider_name='';
                $url='';
               switch($input['product_type']){
                    case 0;
                    $slider_name='Deals Of the day';
                    break;
                    
                    case 1;
                     $slider_name='Best Selling';
                    break;
                    
                    case 2;
                     $slider_name='Also Bought';
                    break;
                    
                    case 4;
                     $slider_name='Offer going on';
                    break;
                   
               }
            //   echo "<pre>";
            //   echo $prd_size;
            //   echo $input['product_type'];
            //   print_r($products);
            //     print_r($input);
            //   die();
               $inserted_array=array();
               $exist_product=0;
               foreach($products as $prd){
                   $ixExist=DB::table('product_home_slider')
                            ->where('product_id',$prd)
                            ->where('slider_type',$input['product_type'])
                            ->first();
                  if($ixExist){
                      $exist_product++;
                  } else{
                      
                      $single=array(
                          'product_id'=>$prd,
                          'slider_type'=>$input['product_type'],
                          'slider_name'=>$slider_name
                          );
                          
                          array_push($inserted_array,$single);
                  }
                  
                $res=ProductSlider::insert($inserted_array);
                
                 if($res){
                     if($exist_product>0){
                           MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
                     } else{
                          MsgHelper::save_session_message('danger','Some Product Already in list',$request); 
                     }
		
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
                  
               }
               
           } else{
               MsgHelper::save_session_message('danger','Select Products To add',$request); 
           }
            return Redirect::back();
       }
       
       
      
      public function delete_offer_product(Request $request){
           $product_id=base64_decode($request->product_id);
           $type=base64_decode($request->type);
          $res=ProductSlider::
                where('product_id',$product_id)
                ->where('slider_type',$type)
              ->delete();
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
      }
     public function offer_slider(Request $request){
         $type=base64_decode($request->type);
        
          $page_details=array(
        "Title"=>"Offers",
        "Box_Title"=>"Offers",
        "search_route"=>'',
        "reset_route"=>''
        );
        
        $products=ProductSlider::
               select('products.id','products.name','products.price','products.spcl_price','products.sku',
                DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',products.default_image) AS default_image")
                )
            ->join('products','products.id','product_home_slider.product_id')
            ->where('slider_type',$type)->get();
        
        return view('admin.extrafeature.offer_slider.list',
        [
            'products'=>$products,
            'page_details'=>$page_details
        ]);
     }
      public function pages(Request $request)
    {
        
        
	
		$page_details=array(
       "Title"=>"Pages",
       "Box_Title"=>"pages",
		"search_route"=>'',
		"reset_route"=>''
     );
	 
		$Pages=Pages::get();
		
return view('admin.extrafeature.pages.list',['Pages'=>$Pages,'page_details'=>$page_details]);
    }
    
     public function add_page(Request $request)
   {
	    
		   
		   
     $page_details=array(
       "Title"=>"Add Pages",
	     "Method"=>"2",
       "Box_Title"=>"Add Pages",
         "Action_route"=>route('addpage'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Text',
                'type'=>'text',
                'name'=>'title',
                'id'=>'title',
                'classes'=>'form-control',
                'placeholder'=>'Title',
                'value'=>'',
                'disabled'=>''
           ),
           "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url_name',
                'id'=>'url_name',
                'classes'=>'form-control',
                'placeholder'=>'Url Name',
                'value'=>'',
                'disabled'=>''
           ),
            "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "banner_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>'',
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
			 "images"=>array(
                  'banner_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
   
          $request->validate([
              'title' => 'required|max:50',
              'description' => 'required|max:60000',
              'url_name' => 'required|max:255|alpha_dash',
              'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.page_banner_min').'|max:'.Config::get('constants.size.page_banner_max').''    

                
            ]
			);
           
           
		$pages = new Pages;
		 $pages->url_name = $input['url_name'];
        $pages->title = $input['title'];
        $pages->description = $input['description'];
        
         if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.pages');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,1140,300);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
             $pages->banner = $file_name2;
    }
       
    
     
      /* save the following details */
      if($pages->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.extrafeature.pages.form',['page_details'=>$page_details]);
	  

   }
   
   public function edit_page(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Pages_details = Pages::where('id', $id)->first();
		   
		   
     $page_details=array(
       "Title"=>"Update Pages",
	     "Method"=>"2",
       "Box_Title"=>"Update Pages",
         "Action_route"=>route('edit_page', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Text',
                'type'=>'text',
                'name'=>'title',
                'id'=>'title',
                'classes'=>'form-control',
                'placeholder'=>'Title',
                'value'=>$Pages_details->title,
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url_name',
                'id'=>'url_name',
                'classes'=>'form-control',
                'placeholder'=>'Url Name',
                'value'=>$Pages_details->url_name,
                'disabled'=>''
           ),
            "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "banner_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Pages_details->description,
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
			 "images"=>array(
                  'banner_image'=>$Pages_details->banner
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
    $id=base64_decode($request->id);
       $pages = Pages::find($id);
          $request->validate([
              'title' => 'required|max:50',
              'description' => 'required|max:60000',
              'url_name' => 'required|max:150|alpha_dash',             
              'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.page_banner_min').'|max:'.Config::get('constants.size.page_banner_max').''    

                
            ]
			);
           
           

    
        $pages->title = $input['title'];
        $pages->url_name = $input['url_name'];
        $pages->description = $input['description'];
        
         if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.pages');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,1140,300);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
             $pages->banner = $file_name2;
    }
       
    
     
      /* save the following details */
      if($pages->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.extrafeature.pages.form',['page_details'=>$page_details]);
	  

   }
    
     
    public function home_slider(Request $request)
    {
		$page_details=array(
		   "Title"=>"Home Slider",
		   "Box_Title"=>"Home Slider",
			"search_route"=>URL::to('admin/banner_search'),
			"reset_route"=>route('home_slider')
		 );
	 
	 $parameters=$request->status;
	 
		if($parameters!=''){
		  $banners=Slider::where('sliders.status','=',$parameters)->get();
		} else{
		  $banners=Slider::get();
		}
		
		return view('admin.extrafeature.home_slider.Slider',['banners'=>$banners,'page_details'=>$page_details,'status'=>$parameters]);
    }


   public function addbanner(Request $request){

   
   
     $page_details=array(
       "Title"=>"Add Banner",
	     "Method"=>"1",
       "Box_Title"=>"Add Banner",
       "Action_route"=>route('addbanner'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Text',
                'type'=>'text',
                'name'=>'text',
                'id'=>'text',
                'classes'=>'form-control',
                'placeholder'=>'Text',
                'value'=>old('text'),
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url',
                'id'=>'url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>old('url'),
                'disabled'=>''
           ),
            
               "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "banner_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>old('description'),
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
			 "images"=>array(
                  'banner_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
    
    
      
          $request->validate([
              'text' => 'max:50',
              'description' => 'max:255',
               'url' => 'max:255',
              'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''
                
            ]
			);
           
           $fld_type='';
        $assign_id='';
if($input['url']!=''){
                $url=$input['url'];
            if (preg_match("/cat/", $url))
            {
                    $url_array=explode("/",$url);
                    
                    $fld_type='fld_cat_id';
                    $assign_id=base64_decode(end($url_array));
                   
            }

        if (preg_match("/p/", $url))
        {
            $url_array=explode("/",$url);
            
            $fld_type='fld_product_id';
            $decodeInput=explode("~~~",base64_decode(end($url_array)));
            $assign_id=$decodeInput[0];
           
        }
        
        if (preg_match("/brand/", $url))
        {
            $url_array=explode("/",$url);
            $br_name=Brands::select('id')->where('name',end($url_array))->where('status',1)->where('isdeleted',0)->first();
            if($br_name){
                $fld_type='fld_brand_id';
                $assign_id=$br_name->id;
            }
       
        }
        
            }
            $banner_image = $request->file('banner_image');
            $destinationPath2 =  Config::get('constants.uploads.home_slider');
            $file_name2=$banner_image->getClientOriginalName();

      
        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,1920,796);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }

            $Slider = new Slider;
            $Slider->short_text = $input['text'];
            $Slider->url = $input['url'];
            $Slider->description = $input['description'];
            if($fld_type!=''){
              $Slider->product_type = $fld_type;
            }
            if($assign_id!=''){
              $Slider->cat_prd_id = $assign_id;
            }
          
               
          
          
            $Slider->image = $file_name2;
     
      /* save the following details */
      if($Slider->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.extrafeature.home_slider.form',['page_details'=>$page_details]);
   }


   public function edit_banner(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Slider_details = Slider::where('id', $id)->first();
		   
		   
     $page_details=array(
       "Title"=>"Update Banner",
	     "Method"=>"2",
       "Box_Title"=>"Update Banner",
         "Action_route"=>route('edit_banner', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Text',
                'type'=>'text',
                'name'=>'text',
                'id'=>'text',
                'classes'=>'form-control',
                'placeholder'=>'Text',
                'value'=>$Slider_details->short_text,
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'Text',
                'type'=>'text',
                'name'=>'url',
                'id'=>'url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
              'value'=>$Slider_details->url,
                'disabled'=>''
           ),
            
          //      "banner_file_field"=>array(
			    // 'label'=>'Banner Image *',
          //       'type'=>'file_special',
          //       'name'=>'banner_image',
          //       'id'=>'file-2',
          //       'classes'=>'inputfile inputfile-4',
          //       'placeholder'=>'',
          //       'value'=>''
                
          //      ),
               "banner_file_field"=>array(
                'label'=>'Banner Image *',
                      'type'=>'file_special_imagepreview',
                      'name'=>'banner_image',
                      'id'=>'file-2',
                      'classes'=>'inputfile inputfile-4',
                      'placeholder'=>'',
                      'value'=>'',
                      'onchange'=>'image_preview(event)'
                      
                     ),
               "banner_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Slider_details->description,
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
			 "images"=>array(
                  'banner_image'=>$Slider_details->image
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();

            $fld_type='';
            $assign_id=0;           

            $fld_type='';
            $assign_id='';
if($input['url']!=''){
                $url=$input['url'];
            if (preg_match("/cat/", $url))
            {
                    $url_array=explode("/",$url);
                    
                    $fld_type='fld_cat_id';
                    $assign_id=base64_decode(end($url_array));
                   
            }

        if (preg_match("/p/", $url))
        {
            $url_array=explode("/",$url);
            
            $fld_type='fld_product_id';
            $decodeInput=explode("~~~",base64_decode(end($url_array)));
            $assign_id=$decodeInput[0];
           
        }
        
        if (preg_match("/brand/", $url))
        {
            $url_array=explode("/",$url);
            $br_name=Brands::select('id')->where('name',end($url_array))->where('status',1)->where('isdeleted',0)->first();
            if($br_name){
                $fld_type='fld_brand_id';
                $assign_id=$br_name->id;
            }
       
        }
        
            }
    
    $id=base64_decode($request->id);
       $slider = Slider::find($id);
          $request->validate([
              'text' => 'max:50',
              'description' => 'max:255',
               'url' => 'max:255',              
              'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''

                
            ]
			);
           
           

    
        $slider->short_text = $input['text'];
        $slider->url = $input['url'];
        $slider->description = $input['description'];
          $slider->product_type ='';
            $slider->cat_prd_id = '';
         
                if($fld_type!=''){
                  $slider->product_type = $fld_type;
                }
            if($assign_id!=''){
              $slider->cat_prd_id = $assign_id;
            }
            $slider->status = 0;
        
         if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.home_slider');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,1920,796);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
             $slider->image = $file_name2;
    }
       
    
     
      /* save the following details */
      if($slider->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.extrafeature.home_slider.form',['page_details'=>$page_details]);
	  

   }


      public function delete_banner(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Slider::where('id',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }

    public function banner_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Slider::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
