<?php

namespace App\Http\Controllers\sws_Admin;

use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Illuminate\Http\Request;
use App\Orders;
use App\Brands;
use App\Vendor;
use App\Products;
use App\OrdersDetail;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use App\Exports\SubOrderExport;
use URL;
use PDF;

class OrderController extends Controller
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
    
    
public function srefundConfirm(Request $request){
          $id= base64_decode($request->id);
          
           $order__id=DB::table('replace_order_details')
                                    ->select('replace_order_details.sub_order_id')
                                    ->where('id',$id)
								    ->first();
								  
							$OrdersDetail=OrdersDetail::
							    select(
							        'orders.customer_id',
							        'order_details.order_id',
							        'order_details.order_reward_points',
							        'order_details.product_id',
							        'order_details.product_qty',
							        'order_details.size_id',
							        'order_details.color_id'
							        )
							        ->join('orders','orders.id','order_details.order_id')
							    ->where('order_details.id',$order__id->sub_order_id)->first();
							    
               
                   
          
        //   refund completed
          $isReplaced=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'replceOrder_sts'=>1
								          )
								      );
								       
                    if($isReplaced){
                        
                         $wallet=array(
                'fld_customer_id'=>$OrdersDetail->customer_id,
                'fld_order_id'=>$OrdersDetail->order_id,
                'fld_order_detail_id'=>$order__id->sub_order_id,
                'fld_reward_narration'=>'Deducted',
                'fld_reward_deduct_points'=>$OrdersDetail->order_reward_points,
                );
                
                // create wallet history
                 
			 DB::table('tbl_wallet_history')->insert($wallet);
			 

								   Products::increaseProductQty(
								       $OrdersDetail->product_id,
								       $OrdersDetail->size_id,
								       $OrdersDetail->color_id,
								       $OrdersDetail->product_qty
								       ); 
								       
								       // decease customers wallet amount
                        DB::table('customers')
                        ->where('id',$OrdersDetail->customer_id)
                        ->decrement('total_reward_points',$OrdersDetail->order_reward_points);
                                $replacement_data=DB::table('replace_order_details')->select('replace_order_details.sub_order_id')->where('id',$id)->first();
                                CommonHelper::generateMailforOrderSts($replacement_data->sub_order_id,6);
                    MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
                    return redirect()->back();
                    } else{
                    MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
                    return redirect()->back();
                    }
     }
     public function sreplaceConfirm(Request $request){
          $id= base64_decode($request->id);
          
         $order__id=DB::table('replace_order_details')
                                    ->select('replace_order_details.sub_order_id','replace_order_details.suborder_no')
                                    ->where('id',$id)
								    ->first();
								  
							$OrdersDetail=OrdersDetail::where('order_details.id',$order__id->sub_order_id)->first();
							
							
							 $order_detail=array(
				'suborder_no'=>$order__id->suborder_no,
				'order_id'=>$OrdersDetail->order_id,
				'product_id'=>$OrdersDetail->product_id,
				'product_name'=>$OrdersDetail->product_name,
				'product_qty'=>$OrdersDetail->product_qty,
                'product_price'=>$OrdersDetail->product_price,
                'product_price_old'=>$OrdersDetail->product_price_old,
                'size'=> Products::getSizeName($OrdersDetail->size_id),
                'color'=> Products::getcolorName($OrdersDetail->color_id),
                'size_id'=>$OrdersDetail->size_id,
                'color_id'=>$OrdersDetail->color_id,
               
				'order_reward_points'=>($OrdersDetail->order_reward_points)?$OrdersDetail->order_reward_points:0,
                'order_shipping_charges'=>$OrdersDetail->order_shipping_charges,
                'order_commission_rate'=>$OrdersDetail->order_commission_rate,
                 'return_days'=>$OrdersDetail->return_days
				);
				 DB::table('order_details')->insert($order_detail);
							    
							   
          
          
          $isReplaced=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'replceOrder_sts'=>1
								          )
								      );
								       
                    if($isReplaced){
                            $replacement_data=DB::table('replace_order_details')->select('replace_order_details.sub_order_id')->where('id',$id)->first();
                            CommonHelper::generateMailforOrderSts($replacement_data->sub_order_id,5);
                    MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
                    return redirect()->back();
                    } else{
                    MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
                    return redirect()->back();
                    }
     }
     public function spickupOrder(Request $request){
          $id= base64_decode($request->id);
          $replacement_data=DB::table('replace_order_details')->select('replace_order_details.sub_order_id')->where('id',$id)->first();
          $isReplaced=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'order_status'=>1
								          )
								      );
								       
                    if($isReplaced){
                          CommonHelper::generateMailforOrderSts($replacement_data->sub_order_id,3);
                    MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
                    return redirect()->back();
                    } else{
                    MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
                    return redirect()->back();
                    }
     }
     public function spackageReceived(Request $request){
          $id= base64_decode($request->id);
            $replacement_data=DB::table('replace_order_details')->select('replace_order_details.sub_order_id')->where('id',$id)->first();
          $isReplaced=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'order_status'=>2
								          )
								      );
								       
                    if($isReplaced){
                          CommonHelper::generateMailforOrderSts($replacement_data->sub_order_id,4);
                    MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
                    return redirect()->back();
                    } else{
                    MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
                    return redirect()->back();
                    }
     }
    public function index(Request $request)
    {
        	$vendors=Vendor::where('isdeleted',0)->where('public_name',"!=",'')->get();	
            $vendor=$request->vendor;	
            $str=$request->str;	
            $daterange=$request->daterange;	
            $type= base64_decode($request->type);
// 		if($str!='' && $daterange!=''){
// 			$export=route('exportorders_with_Search', [$request->type,$str,$daterange]);
// 			$str_url=URL::to('admin/order_search')."/".$request->type;
// 		}elseif($str!=''){
// 			$export=route('exportorders_with_Search', [$request->type,$str,'']);
// 			$str_url=URL::to('admin/order_search_str')."/".$request->type;
// 		}elseif($daterange!=''){
// 			$export=route('exportorders_with_Search', [$request->type,'',$daterange]);
// 			$str_url=URL::to('admin/order_search_date')."/".$request->type;
// 		}else{
// 			$export=route('exportorders',($request->type));
// 			$str_url=URL::to('admin/order_search')."/".$request->type;
// 		}
		$export='';
		$page_details=array(
				   "Title"=>"Orders",
				   "Box_Title"=>"List",
				   "search_route"=>URL::to('admin/order_search')."/".$request->type,
				   "export"=>$export,
					"reset_route"=>route('orders',($request->type))
				 );
	 
	 
	 $Orders =Orders::select('orders.delivery_time','orders.delivery_day','orders.delivery_date',
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.razorpay_order_id',
						'orders.service_charge',
						'orders.exhibition_code',		
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.coupon_code',
						'orders.coupon_percent',
						'orders.grand_total',
                        'orders_shipping.order_shipping_address',
                        'orders_shipping.order_shipping_address1',
                        'orders_shipping.order_shipping_address2',
                        'orders_shipping.order_shipping_city',
                        'orders_shipping.order_shipping_state',
                        'orders_shipping.order_shipping_phone',
                        'orders_shipping.order_shipping_email',
                        'orders_shipping.order_shipping_zip'
						)
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id');
					
					
					if($vendor!=0){
				  $Orders=$Orders->join('products', 'products.id', '=', 'order_details.product_id')
				                    ->where('products.vendor_id',$vendor);
						
				}
				if($str!='All'){
					  $Orders=$Orders
							->Where(function($query) use ($str){
								 $query->orwhere('orders.order_no','LIKE',$str.'%');
								 $query->orWhere('orders_shipping.order_shipping_name','LIKE',$str.'%');
								 $query->orWhere('orders_shipping.order_shipping_phone','LIKE',$str.'%');
								 $query->orWhere('orders_shipping.order_shipping_email','LIKE',$str.'%');
								 $query->orWhere('orders.exhibition_code','LIKE',$str.'%');

							 });
				} 
				
				 if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
        //             	echo $from." ".$to;
                   $Orders=$Orders
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
				// if($daterange!=''){
				// 	$daterange=explode('--',$daterange);
				// 	$daterange[0] = explode("-", $daterange[0]);
				// 	$from = $daterange[0][2] . "-" . $daterange[0][1] . "-" . $daterange[0][0]; 

				// 	$daterange[1] = explode("-", $daterange[1]);
				// 	$to = $daterange[1][2] . "-" . $daterange[1][1] . "-" . $daterange[1][0];
					
				// 	  $Orders=$Orders
				// 			 ->whereBetween('orders.order_date',[$from,$to]);
				// } 
			if($type != 9){
				$Orders=$Orders->where('order_details.order_status','=', 0)->where('orders.order_status',$type)->orderBy('orders.id','desc')->groupBy('orders.id')->paginate(100); 
			}else{
				$Orders=$Orders->where('orders.order_status',$type)->orderBy('orders.id','desc')->groupBy('orders.id')->paginate(100); 
			}

		
			
		$parameters = $request->type;
		$parameters_level = base64_decode($parameters);
		
		return view('admin.orders.list',['orders'=>$Orders,'parameters_level'=>$parameters_level,'str'=>$str,'daterange'=>$daterange,'vendor'=>$vendor,'page_details'=>$page_details,'vendors'=>$vendors]);
	 
	
// 	  return view('admin.orders.list',['orders'=>$Orders,'parameters_level'=>$parameters_level,'str'=>$str,'daterange'=>$request->daterange,'page_details'=>$page_details]);
	 
    }
	
	 public function exportorders(Request $request)
    {
		$type= base64_decode($request->type);
		$str=$request->str;
		return Excel::download(new OrderExport($str,$type), 'Orders'.date('d-m-Y H:i:s').'.csv');
    }
	 public function order_detail(Request $request)
    {

			
		
		$page_details=array(
       "Title"=>"Order Details",
       "Box_Title"=>"Order Details"
     );
	  $id= base64_decode($request->id);
	  
	$Order =Orders::select(
	    'orders.delivery_time','orders.delivery_day','orders.delivery_date',
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.service_charge',
						'orders.total_shipping_charges',
						'orders.razorpay_order_id',
						'orders.txn_id',
						'orders.payment_mode',
						'orders.exhibition_payment_mode',
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.grand_total',
						'orders.coupon_percent',
                        'orders.coupon_code',
                        'orders.coupon_amount',
                        'orders_shipping.order_shipping_address',
						'orders_shipping.order_shipping_name',
						'orders_shipping.order_shipping_phone',
						'orders_shipping.order_shipping_email',
						'orders_shipping.order_shipping_zip',
						'order_details.suborder_no',
						'order_details.product_id',
						'order_details.product_name',
						'order_details.product_qty',
						'order_details.product_price',
						'order_details.size',
						'order_details.color',
						'order_details.color_id',
						'order_details.size_id',
						'order_details.order_status',
						)
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')					
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('orders.id',$id)
					->get()->toarray();
					
			 return view('admin.orders.detail',['Order'=>$Order,'page_details'=>$page_details]);	
	 
    }
	
		public function order_delivered(Request $request)
    {

	  $id= base64_decode($request->id);
	 	
			Orders::where('id', $id)
			->update([
			'order_status'=>3
			]);
			return redirect()->back();
	 
    }
	public function order_generate_invoice(Request $request)
    {

	  $id= base64_decode($request->id);
	 	
			Orders::where('id', $id)
			->update([
			'order_status'=>1,
			'invoice_num'=>'PHT/BLR/'.$id,
			]);
			return redirect()->route('order_invoice', ['id' => $request->id]);
	 
    }


	public function order_ServiceInvoice(Request $request)
    {
									
	$page_details=array(
       "Title"=>"Service Invoice",
       "Box_Title"=>"Service Invoice"
     );
	  $id= base64_decode($request->id);
			
	  $serviceChargeTax =  DB::table('store_info')->select('service_charge_tax')->where('id',1)->first(); 

	 $Order =Orders::select(
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.service_charge',
						'orders.service_invoice_num',
						'orders.service_invoice_date',
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.grand_total',
						'orders.coupon_percent',
                        'orders.coupon_code',
                        'orders.coupon_amount',
                        'orders.invoice_num',
						'orders.payment_mode',
                        'orders_shipping.order_shipping_address',
                        'orders_shipping.order_shipping_name',
                        'orders_shipping.order_shipping_phone',
                        'orders_shipping.order_shipping_email',
                        'orders_shipping.order_shipping_zip',
						'orders_shipping.order_shipping_state',
						'orders_shipping.order_shipping_city',	
                        'order_details.product_name',
                        'order_details.product_qty',
                        'order_details.product_price',
                        'order_details.size',
                        'order_details.color'
						)
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('orders.id',$id)
					->first();

					$billingAddress = [];
					if(!empty($Order)){
					   $billingAddress = DB::table('orders_billing_addresses')
					   ->where('order_id', $id)
					   ->first();
					}
					$type = $request->download; 
					$pdfData = ['billingAddress'=>$billingAddress,'serviceChargeTax'=>$serviceChargeTax,'Order'=>$Order,'page_details'=>$page_details, 'is_front'=>1];

					if($type == 1){
                        $pdf=PDF::loadView('admin.orders.service_invoice',$pdfData);
                        return $pdf->download($Order->order_no.'_service_invoice.pdf'); 
                     }    

			 return view('admin.orders.service_invoice',['billingAddress'=>$billingAddress,'serviceChargeTax'=>$serviceChargeTax,'Order'=>$Order,'page_details'=>$page_details]);	
	 
    }


	 public function order_invoice(Request $request)
    {
		
								
	$page_details=array(
       "Title"=>"Invoice",
       "Box_Title"=>"Invoice"
     );
	  $id= base64_decode($request->id);
	  
	  
			
	  
$Order =Orders::select(
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.grand_total',
						'orders.coupon_percent',
                        'orders.coupon_code',
                        'orders.coupon_amount',
                        'orders.invoice_num',
                        'orders_shipping.order_shipping_address',
                        'orders_shipping.order_shipping_name',
                        'orders_shipping.order_shipping_phone',
                        'orders_shipping.order_shipping_email',
                        'orders_shipping.order_shipping_zip',
                        'order_details.product_name',
                        'order_details.product_qty',
                        'order_details.product_price',
                        'order_details.size',
                        'order_details.color'
						)
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('orders.id',$id)
					->get()->toarray();
			 return view('admin.orders.invoice',['Order'=>$Order,'page_details'=>$page_details]);	
	 
    }
	
	public function order_shipping(Request $request)
    {


$cors=DB::table('couriers')->get();
	  $id= base64_decode($request->id);
	  
	$page_details=array(
       "Title"=>"Shipping",
		"Method"=>"Shipping",
		"Box_Title"=>"Shipping",
       "Action_route"=>route('order_shipping',(base64_encode($id))),
       "Form_data"=>array(

         "Form_field"=>array(
             "docket_no"=>array(
							'label'=>'Docket No *',
							'type'=>'text',
							'name'=>'docket_no',
							'id'=>'docket_no',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
			   "remarks"=>array(
							'label'=>'Remarks',
							'type'=>'text',
							'name'=>'remarks',
							'id'=>'remarks',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
			 
			"courier"=>array(
							'label'=>'Service Provider *',
							'type'=>'select',
							'name'=>'courier',
							'id'=>'courier',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>$cors,
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
       )
     );
	
	
	 if ($request->isMethod('post')) {
		     $input=$request->all();
			 
			  $request->validate([
			'docket_no' => 'required|max:25',
			'courier' => 'required|max:20',
			'remarks' => 'max:50'
             ]
        );
		
			 
			 DB::table('orders_shipping')->where('order_id', $id)
			->update([
			'docket_no'=>$input['docket_no'],
			'courier_name'=>$input['courier'],
			'remarks'=>$input['remarks']
			]);
			
			Orders::where('id', $id)
			->update([
			'order_status'=>2
			]);
				MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return redirect()->route('orders', ['type' => base64_encode(2)]);
			
	 }
	
	  return view('admin.orders.shipping',['orders'=>array(),'page_details'=>$page_details]);
	 
    }
	
	public function sub_orders(Request $request)
    {
        
        // echo "1244/01-10-2019--31-10-2019";
        
        	$vendors=Vendor::get();
            $vendor=$request->vendor;	
            $str=$request->str;	
            $daterange=$request->daterange;	
            $type= base64_decode($request->type);
            $category_id =$request->category;
            $Brands= Brands::where('isdeleted', 0);
                 
            $Brands=$Brands->orderBy('id', 'DESC')->paginate(100);
                    
            $brands =$request->brand;
               
// 		if($str!=''){
// 			$export=route('sexportorders_with_Search', [$request->type,$str]);
// 			} else{
// 			$export=route('sexportorders',($request->type));
// 		  }
		$export="";
		$page_details=array(
       "Title"=>"Orders",
       "Box_Title"=>"List",
	   "search_route"=>URL::to('admin/sorder_search')."/".$request->type,
	   "export"=>$export,
		"reset_route"=>route('sorders',($request->type)),
		"Form_data"=>array(
				 "Form_field"=>array(
                    "select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'order_category_id',
                    'id'=>'order_category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',$category_id)
                    )
					)
			)
     );
	 
	 
	 /*$Orders =Orders::select(
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.coupon_code',
						'orders.coupon_percent',
						'orders.grand_total',
                        'orders_shipping.order_shipping_address',
                        'orders_shipping.order_shipping_address1',
                        'orders_shipping.order_shipping_address2',
                        'orders_shipping.order_shipping_city',
                        'orders_shipping.order_shipping_state',
                        'orders_shipping.order_shipping_phone',
                        'orders_shipping.order_shipping_email',
                        'orders_shipping.order_shipping_zip'
						)
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id');
					
					if($str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$str.'%')
						->orWhere('orders.order_date','LIKE',$str.'%')
						->orWhere('orders_shipping.order_shipping_name','LIKE',$str.'%')
						->orWhere('orders_shipping.order_shipping_phone','LIKE',$str.'%')
						->orWhere('orders_shipping.order_shipping_email','LIKE',$str.'%');
				} 
		$Orders=$Orders->where('orders.order_status',$type)->orderBy('orders.id','desc')->paginate(100);*/
					
		$Orders=OrdersDetail::select(
						'orders.order_no',
						'orders.order_date',
						'orders.shipping_id',
						'order_details.*',
						'products.default_image'
						)
						->join('orders', 'orders.id', '=', 'order_details.order_id')
						->join('products', 'products.id', '=', 'order_details.product_id')
                        ->join('product_categories','product_categories.product_id','=','products.id')
                        ->join('categories','categories.id','=','product_categories.cat_id');
						
						
					if($vendor!=0){
				  $Orders=$Orders->where('products.vendor_id',$vendor);
						
				}
				if($brands!='All' &&  $brands!=''){
		    	$selcted_brand=explode(",",$brands);
		    
				   $Orders=$Orders->whereIn('products.product_brand',$selcted_brand);
		} 
		
			if($category_id!='All' && $category_id!=''){
		  	$Orders =$Orders->where('product_categories.cat_id',$category_id);
		} 
		
				if($str!='All' && $str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$str.'%')
						->orWhere('order_details.suborder_no','LIKE',$str.'%');
				}

				// if($daterange!=''){
				// 	$daterange=explode('--',$daterange);
				// 	$daterange[0] = explode("-", $daterange[0]);
				// 	$from = $daterange[0][2] . "-" . $daterange[0][1] . "-" . $daterange[0][0]; 

				// 	$daterange[1] = explode("-", $daterange[1]);
				// 	$to = $daterange[1][2] . "-" . $daterange[1][1] . "-" . $daterange[1][0];
					
				// 	  $Orders=$Orders
				// 			 ->whereBetween('orders.order_date',[$from,$to]);
				// } 
				
				 if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $Orders=$Orders
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
                
				$Orders=$Orders->where('order_details.order_status',$type)->groupBy('order_details.id')->orderBy('order_details.id','desc')->paginate(100);
	
		$parameters = $request->type;
		$parameters_level = base64_decode($parameters);
	
	  return view('admin.suborders.list',['orders'=>$Orders,'parameters_level'=>$parameters_level,'str'=>$str,'daterange'=>$daterange,'vendor'=>$vendor,'page_details'=>$page_details,'vendors'=>$vendors,'Brands'=>$Brands]);
	 
    }
	
	public function suborder_detail(Request $request)
	{
		$page_details=array(
		   "Title"=>"Order Details",
		   "Box_Title"=>"Order Details"
		 );
		
		$id= base64_decode($request->order_detail_id);
		
		$Order =Orders::select(
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.grand_total',
						'orders.coupon_percent',
                        'orders.coupon_code',
                        'orders.coupon_amount',
                        'orders_shipping.order_shipping_address',
						'orders_shipping.order_shipping_name',
						'orders_shipping.order_shipping_phone',
						'orders_shipping.order_shipping_email',
						'orders_shipping.order_shipping_zip',
						'order_details.suborder_no',
						'order_details.product_id',
						'order_details.product_name',
						'order_details.product_qty',
						'order_details.product_price',
						'order_details.size',
						'order_details.w_size',
						'order_details.color'
						)
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('order_details.id',$id)
					->get()->toArray();
					
			 return view('admin.suborders.detail',['Order'=>$Order,'page_details'=>$page_details]);
	}
	
	 public function sub_exportorders(Request $request)
    {
		$type= base64_decode($request->type);
		$str=$request->str;
		return Excel::download(new SubOrderExport($str,$type), 'SubOrders'.date('d-m-Y H:i:s').'.csv');
    }
	
	public function suborder_invoice(Request $request)
    {
		$page_details=array(
		   "Title"=>"Invoice",
		   "Box_Title"=>"Invoice"
		 );
	  
	  $id= base64_decode($request->order_detail_id);
	  
	  $Order =Orders::select(
								'orders.order_no',
								'orders.order_date',
								'orders.updated_at',
								'orders.id',
									'orders.customer_id',
									'orders.shipping_id',
								'orders.deduct_reward_points',
								'orders.discount_amount',
								'orders.grand_total',
								'orders.coupon_percent',
								'orders.coupon_code',
								'orders.coupon_amount',
								'orders.invoice_num',
								'orders_shipping.order_shipping_address',
								'orders_shipping.order_shipping_address1',
								'orders_shipping.order_shipping_address2',
								'orders_shipping.order_shipping_city',
								'orders_shipping.order_shipping_state',
								'orders_shipping.order_shipping_name',
								'orders_shipping.order_shipping_phone',
								'orders_shipping.order_shipping_email',
								'orders_shipping.order_shipping_zip',
								'order_details.id as order_detail_id',
								'order_details.product_id',
								'order_details.order_detail_invoice_num',
								'order_details.order_detail_invoice_date',
								'order_details.product_name',
								'order_details.product_qty',
								'order_details.product_price',
								'order_details.product_price_old',
								'order_details.order_shipping_charges',
								'order_details.size',
								'order_details.color'
								)
							
							->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->where('order_details.id',$id)
							->get()->toarray();
							
							$vendor =Products::select(
								'products.vendor_id'
								)
							->where('products.id',$Order[0]['product_id'])
							->first();
							
							$vdr_id=$vendor->vendor_id;
							
							$vdr=new Vendor();
							$vdr_data=$vdr->getVendorDetails($vdr_id);
							
			 return view('admin.suborders.invoice_test',['Order'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data]);	
    }
	
	public function suborder_refund(Request $request)
    {
		$page_details=array(
		   "Title"=>"Refund",
		   "Box_Title"=>"Refund"
		 );
	  
		$id= base64_decode($request->order_detail_id);
	  
	  	OrdersDetail::where('id', $id)
					->update([
					'order_status'=>6
					]);
		return redirect()->back();
    }
	
	public function sorders_refund_complete(Request $request)
    {
		$page_details=array(
		   "Title"=>"Refund Completed",
		   "Box_Title"=>"Refund Completed"
		 );
	  
		$id= base64_decode($request->order_detail_id);
	  
	  	OrdersDetail::where('id', $id)
					->update([
					'refund_status'=>1
					]);
		return redirect()->back();
    }



	/**
	 * Razor Pay check 
	 */
	public function razorPayCheck(Request $request){
		$page_details=array(
			"Title"=>"Razor Pay Check",
			"Box_Title"=>"Razor Pay Check"
		  );
		return view('admin.orders.razorpaycheck',['razorPayOrderID'=>$request->id,'page_details'=>$page_details]);	
	}


	public function MoveToNewOrder(Request $request){
		$request->validate([
			'transactionID' => 'required|max:255',		
			 ]
		);
		$razorPayOrderId = $request->id; 
		$masterOrderData = Orders::where('razorpay_order_id',$razorPayOrderId)->first(); 
		if(!empty($masterOrderData)){

			Orders::where('razorpay_order_id',$razorPayOrderId)->update([
				'txn_id' => $request->transactionID,
				'txn_status' => "success",
				'order_status' => 0
			]);
	
			OrdersDetail::where('order_id',$masterOrderData->id)->update([
				'order_status' => 0
			]);				
			

				 /**
                 * Deduct wallet amount on payment success
                 */

                if($MasterOrderData->deduct_reward_points>0){

                    if($MasterOrderData->deduct_reward_points!=0 && $MasterOrderData->deduct_reward_points!=''){
                        $wallet=array(
                                   'fld_customer_id'=>$MasterOrderData->customer_id,
                                   'fld_order_id'=>$merchant_order_id,
                                   'fld_order_detail_id'=>0,
                                   'fld_reward_narration'=>'Deducted',
                                   'fld_reward_deduct_points'=>$MasterOrderData->deduct_reward_points
                               );
                       

                       $isCreated = DB::table('tbl_wallet_history')->insert($wallet);

                    }                    
                                       
                   $cust_points=DB::table('customers')->where('id',$MasterOrderData->customer_id)->first();
                   $deduct_amt=$cust_points->total_reward_points-$MasterOrderData->deduct_reward_points;
                   
                   DB::table('customers')->where('id',$MasterOrderData->customer_id)
                            ->update(
                                array(
                                    'total_reward_points'=>($deduct_amt)
                                ));
                }


		} 	
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		return redirect()->route('orders',base64_encode(0));
	}
	
   
}
