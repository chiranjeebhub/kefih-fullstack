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
use App\Products;
use App\Vendor;
use App\OrdersDetail;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdervExport;
use URL;
use PDF;

class VendorOrderController extends Controller
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

public function refundConfirm(Request $request){
          $id= base64_decode($request->id);
          
           $order__id=DB::table('replace_order_details')
                                    ->select('replace_order_details.sub_order_id')
                                    ->where('id',$id)
								    ->first();
								  
							$OrdersDetail=OrdersDetail::
							    select(
							        'orders.customer_id',
							        'order_details.order_id',
				                     'order_details.id',
							        'order_details.order_reward_points',
							        'order_details.product_id',
							        'order_details.product_qty',
							        'order_details.size_id',
							        'order_details.color_id'
							        )
							        ->join('orders','orders.id','order_details.order_id')
							    ->where('order_details.id',$order__id->sub_order_id)->first();
							    
               
                   DB::table('order_details')
			->where('id', $OrdersDetail->id)
			->update(
				array(
					'order_status' => 6,
					'product_price' => 0,
					'tds_amt' => 0,
					'tcs_amt' => 0,
					'order_detail_tax_amt' => 0,
				)
			);
          
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
     public function replaceConfirm(Request $request){
          $id= base64_decode($request->id);
          
         $order__id=DB::table('replace_order_details')
                                    ->select('replace_order_details.*')
                                    ->where('id',$id)
								    ->first();
								
								  
							$OrdersDetail=OrdersDetail::where('order_details.id',$order__id->sub_order_id)->first();
							DB::table('order_details')
		->where('id', $order__id->sub_order_id)
		->update(
			array(
				'product_price' => 0,
				'tds_amt' => 0,
				'tcs_amt' => 0,
				'order_detail_tax_amt' => 0,
			)
		);
							
							 $order_detail=array(
				'suborder_no'=>$order__id->suborder_no,
				'order_id'=>$OrdersDetail->order_id,
				'product_id'=>$OrdersDetail->product_id,
				'product_name'=>$OrdersDetail->product_name,
				'product_qty'=>$OrdersDetail->product_qty,
                'product_price'=>$order__id->product_price,
                'product_price_old'=>$order__id->product_price_old,
                'size'=>$order__id->size,
                'color'=>$order__id->color,
                'size_id'=>$order__id->size_id,
                'color_id'=>$order__id->color_id,
                  'tcs_percentage'=>$OrdersDetail->tcs_percentage,
                 'tcs_amt'=>$OrdersDetail->tcs_amt,
                 'tds_percentage'=>$OrdersDetail->tds_percentage,
                 'tds_amt'=>$OrdersDetail->tds_amt,
                'w_size'=>$order__id->w_size,
                'w_size_id'=>$order__id->w_size_id,
                'order_reward_points'=>($OrdersDetail->order_reward_points)?$OrdersDetail->order_reward_points:0,
                'order_vendor_shipping_charges'=>$OrdersDetail->order_vendor_shipping_charges,
				'reverse_order_shipping_charge'=>$OrdersDetail->order_vendor_shipping_charges,
                'order_commission_rate'=>$OrdersDetail->order_commission_rate,
                 'return_days'=>$OrdersDetail->return_days
				);
				 DB::table('order_details')->insert($order_detail);
							    
            			$order_detail_id=DB::getPdo()->lastInsertId();
            	
            	DB::table('order_details')->where('id',$order_detail_id)->update(
                 array(
                     'suborder_no'=>'JKR'.$order_detail_id
                     )
                 );	   
          
          
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
     
       public function pickupOrderManual(Request $request){
          $id= base64_decode($request->id);
        
           $replaceOrder=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->first();
								      
          $isReplaced=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'order_status'=>1
								          )
								      );
                CommonHelper::generateMailforOrderSts($replaceOrder->sub_order_id,3);
                return redirect()->route('vendor_orders', ['type' => base64_encode(5)])->with('flash_success', 'Pikup Request send successfully');  
               
       }
     public function pickupOrder(Request $request){
          $id= base64_decode($request->id);
          $multiple_order_detail_id='';
           $replacement_data=DB::table('replace_order_details')->select('replace_order_details.sub_order_id')->where('id',$id)->first();
          $masterData=self::after_return_request_vendor_order_shipping_master_data_load($replacement_data->sub_order_id);
         
         
          $order_details=DB::table('order_details')
          ->select(
            'order_details.weight',
            'order_details.length',
            'order_details.width',
            'order_details.height',
            'order_details.package_description'
            )
          ->where('id',$replacement_data->sub_order_id)->first();
         
           $ShipmentChargesData = array(
                        'delivery_pincode' => $masterData['delivery_pincode'],
                        'pickup_pincode' => $masterData['pickup_pincode'],
                        'product_name' => $masterData['product_name'],
                        'qty' => $masterData['product_order_qty'],
                        'price' => $masterData['order_price'],
                        'weight'=>$order_details->weight,
                        'height'=>$order_details->height,
                        'length'=>$order_details->length,
                        'width'=>$order_details->width
                             );
         
            $back_response=CommonHelper::ShipmentCharges($ShipmentChargesData);
            $output = (array)json_decode($back_response);
               
                $courier_list=array();
                if($output['success']==1)
        {
            $data=@$output['couriers'];
            
            foreach($data as $row)
            {
                $service_types=$row->service_types;
                
                foreach($row->service_types as $services)
                {
                    $courier_list[]=array(
    'courier_id'=>$row->courier_id,
    'courier_name'=>$row->name,
    'courier_charges'=>$services->rate_breakup->total_charge,
    'codcharges'=>$services->rate_breakup->COD_charges,
    'courier_charges1'=>$services->rate_breakup->courier_total_charge,
    'codcharges1'=>$services->rate_breakup->courier_COD_charges,
    'service_id'=>$services->service_id,
    'service_name'=>$services->service_name,
    'display_name'=>$services->display_name,
    'expected_pickup_date'=>$row->exp_pickup_date,
    'expected_delivery'=>$services->expected_delivery_days
                 );
                }
    
            }
            
$pagedata = array(
                                'Title' => 'Available Couriers to pickup',
                                'Box_Title' => 'Available Couriers to pickup',
                                );
            
            return view('vendor.orders.pickup_after_delivery_courier_list',['page_details'=>$pagedata,'package_content'=>$order_details->package_description,'courier_list'=>$courier_list,'shipment_measure'=>$ShipmentChargesData,'multiple_order_detail_id'=>$multiple_order_detail_id,'order_detail_id'=>$replacement_data->sub_order_id]);
           
        }
          
        // echo "<pre>";
        // print_r($courier_list);
        // die();
         
        //   $isReplaced=DB::table('replace_order_details')
								//   ->where('id',$id)
								//   ->update(
								//       array(
								//           'order_status'=>1
								//           )
								//       );
								       
        //             if($isReplaced){
        //                 CommonHelper::generateMailforOrderSts($replacement_data->sub_order_id,3);
        //             MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
        //             return redirect()->back();
        //             } else{
        //             MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
        //             return redirect()->back();
        //             }
     }
     public function vendor_order_shipping_couirer_orders(Request $request){
    	$ordercode='';
		$pagedata = array(
				'Title' => 'Couirer Orders',
				'Box_Title' => 'Couirer Orders',
				);
        $id= $request->order_detail_id;
		
		$multiple_order_detail_id=$request->select_check; 
		$multi_order_detail_id=''; 
		if(count($multiple_order_detail_id)>0)
		{
			//$multi_order_detail_id=explode(',',$multiple_order_detail_id); 
			$masterData=self::vendor_order_shipping_master_data_load($multiple_order_detail_id);
			$id1=explode(',',$multiple_order_detail_id); 
			$getpID=DB::table('order_details')->whereIn('id',$id1)->get();
			$id=$multiple_order_detail_id; 
			$sub_order_no='';
			foreach($getpID as $row)
			{
				$sub_order_no.=' '.$row->suborder_no;
			}
		}else{
			$masterData=self::vendor_order_shipping_master_data_load($id);
			$id1=explode(',',$id); 
			$getpID=DB::table('order_details')->whereIn('id',$id1)->first();
			$id=$id;
			$sub_order_no=$getpID->suborder_no;
		}
        
        
        
        //$prodcut_detail=DB::table('products')->where('id',$getpID->product_id)->first();
        $sub_order_no0=$sub_order_no1=$sub_order_no2='';
		$ss=explode(' ',trim($sub_order_no));
		if(count($ss)>0){
			for($i=0;$i<count($ss);$i++)
			{
				if($i==0)
					$sub_order_no0=$ss[$i];
				if($i==1)
					$sub_order_no1=$ss[$i];
				if($i==2)
					$sub_order_no2=$ss[$i];
			}
		}else{
			$sub_order_no0=trim($sub_order_no);
		}
		
        $shiporderRequestData = array(
                               "shipmentIds"=>$request->shipment_id,
                               "udf1"=>$sub_order_no0,
							   "udf2"=>$sub_order_no1,
							   "udf3"=>$sub_order_no2,
                               
                               //"client_source": "JKR",
                             );
        
        
        $back_response1=CommonHelper::ShipmentOrder($shiporderRequestData);
      
          $output1 = (array)json_decode($back_response1);
        if($output1['success']==1)
        {
        	$ordercode =$output1['order_code'];
		}
        
       
    return view('vendor.orders.couirer_orders_list',[
    'page_details'=>$pagedata,
    'couirer_orders'=>$output1,
	'multiple_order_detail_id'=>$multiple_order_detail_id,
    'order_detail_id'=>$id,
    'ordercode'=>$ordercode
    ]);     
        
	}
     public function reutrn_vendor_order_shipping_couirer_pickup(Request $request)
    {$pikup_list =array();
         $pagedata = array(
                                'Title' => 'Pikup Request',
                                'Box_Title' => 'Pikup Request',
                                );
        $id= $request->order_detail_id;
		
		$multiple_order_detail_id=$request->select_check; 
		$multi_order_detail_id=''; 
		if(count($multiple_order_detail_id)>0)
		{
			$multi_order_detail_id=explode(',',$multiple_order_detail_id); 
			$id=$multiple_order_detail_id;
		}else{
			
		}
     $isReplaced=DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'order_status'=>1
								          )
								      );
								       
       
                       CommonHelper::generateMailforOrderSts($id,3);
        $masterData=self::after_return_request_vendor_order_shipping_master_data_load($id);
        
        
     
        $vendor_info =DB::table('vendor_company_info')->where('vendor_id',43)->first();
         $vendor =DB::table('vendors')->where('id',43)->first();
        
        
        if($masterData['payment_mode']==0)
		    $payment_mode='COD';
		else
		    $payment_mode='online';
        
        $PickupRequestData = array(
                                 'payment_mode'=>$payment_mode,
                                'pickup_company_name'=>'',
                                'company_name'=>$masterData['delivery_contact_name'],
                                'courier_id'=>$request->courier_id,
                                'service_id'=>$request->service_id,
                                'service_type'=>$request->service_name,
                                'pickup_address2'=> $masterData['pickup_address1']."".$masterData['delivery_country'],
                                'pickup_landmark'=>'',
                                'pickup_contact'=>$masterData['pickup_mobile'],
                                'pickup_email'=>$masterData['pickup_email'],
                                'delivery_landmark'=>'',
                                'package_content'=>$request->input('package_description'),
								'weight'=>$request->input('phy_weight'),
                                'height'=>$request->input('ship_length'),
                                'length'=>$request->input('ship_width'),
                                'width'=>$request->input('ship_height'),
                                
                                'order_number' => $masterData['order_number'],
                                'order_total' => $masterData['order_price'],
                                'delivery_pincode' => $masterData['delivery_pincode'],
								'pickup_pincode' => $masterData['pickup_pincode'],
                                'pickup_contact_name' => $masterData['pickup_contact_name'],
                                'pickup_address1' => $masterData['pickup_address1'],
                                'pickup_city' => $masterData['pickup_city'],
                                'pickup_state' => $masterData['pickup_state'],
                                'delivery_contact_name' => $masterData['delivery_contact_name'],
                                'delivery_address1' =>  $masterData['delivery_address1'],
                                'delivery_address2' =>  $masterData['delivery_address2'],
                                'delivery_address3' =>  $masterData['delivery_address3'],
                                'delivery_city' => $masterData['delivery_city'],
                                'delivery_state' =>  $masterData['delivery_state'],
                                'delivery_country' => $masterData['delivery_country'],
                                'delivery_mobile' =>  $vendor->phone,
                                'delivery_email' =>  'connect@myweb.com',
                             );
        
       
      
        $back_response1=CommonHelper::PickupRequest($PickupRequestData);
       
         file_put_contents('pickup123.txt',$back_response1);
        $output1 = (array)json_decode($back_response1);
        
       
        if(@$output1['success']==1)
        {
            $data=$output1['shipmentPackages'];
			
            foreach($data as $row)
            {
            	
                $shipment_id=$output1['shipment_id'];
                
                $package_detail=$row->package_detail;
                 $pikup_list[]=array(
                            'shipment_id'=>$shipment_id,
                            'no_of_items'=>$package_detail->no_of_items,
                            'package_content'=>$package_detail->package_content,
                            'invoice_value'=>$package_detail->invoice_value,
                           
                             );
             
            }
            
            }
            
            
            return view('vendor.orders.reurnPikup_list',['page_details'=>$pagedata,'pikup_list'=>$pikup_list,'multiple_order_detail_id'=>$multiple_order_detail_id,'order_detail_id'=>$id]);
       
        
    }
     public function packageReceived(Request $request){
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

		    $vendors=array();
                $vendor=$request->vendor;	
                $str=$request->str;	
                $daterange=$request->daterange;	
               
                
                $type= base64_decode($request->type);
                
                //echo  $type;
				//exit();
                $category_id =$request->category;
                 $Brands= Brands::where('isdeleted', 0);
                 
                    $Brands1=$Brands->orderBy('id', 'DESC')->paginate(100);
                    
                     $brands =$request->brand;
                
		$export=URL::to("admin/exportvorders")."/".$request->type;
		if(!empty($daterange) || !empty($category_id) || !empty($brands) || !empty($str) ){
		    $sstr = empty($request->str)?"All":$request->str;
		    $dr = empty($request->daterange)?"All":$request->daterange;
		    $CC = empty($request->category)?"All":$request->category;
		   $bb = empty($request->brands)?"All":$request->brands;
		    
		   $export=URL::to("admin/exportsearchorder")."/".$request->type."/".$sstr."/".$dr."/".$CC."/".$bb; 
		}
		
		$page_details=array(
			   "Title"=>"Orders",
			   "Box_Title"=>"List",
			   "search_route"=>URL::to('admin/vendor_order_search')."/".$request->type,
			   "export"=>$export,
			   "reset_route"=>route('vendor_orders',($request->type)),
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
        
		$Orders=OrdersDetail::select(
						'products.vendor_id',
                        'orders.delivery_time','orders.delivery_day','orders.delivery_date',
                        'orders.order_no',
                        'orders.payment_mode',
						'orders.exhibition_code',
						'orders.exhibition_name',

						'orders.exhibition_payment_mode',
                        'orders.order_date',
                        'order_details.*',
                        'order_details.product_price as grand_total',
                        'order_details.seller_invoice_num',
                        'order_details.product_price as details_price',
                        'order_details.product_qty as  details_qty',
                        'order_details.order_shipping_charges as  details_shipping_charges',
                        'order_details.order_cod_charges as  details_cod_charges',
                        'order_details.order_coupon_amount as  details_cpn_amt',
                        'order_details.order_wallet_amount as  details_wlt_amt',
                        'order_details.id as order_details_id',
                        'order_details.suborder_no as suborder_no',
                        'order_details.product_qty as qty',
                        'products.default_image',
                        'customers.name as cust_name',
                        
                        'orders_shipping.order_shipping_name as customer_name',
                        'orders_shipping.order_shipping_address as customer_add',
                        'orders_shipping.order_shipping_address1 as customer_add1',
                        'orders_shipping.order_shipping_address2 as customer_add2',
                        'orders_shipping.order_shipping_city as customer_city',
                        'orders_shipping.order_shipping_state as customer_state',
                        'orders_shipping.order_shipping_country as customer_country',
                        'orders_shipping.order_shipping_zip as customer_zip',
                        'orders_shipping.order_shipping_phone as customer_phone',
                        'orders_shipping.order_shipping_email as customer_email'

						)
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                 ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->join('product_categories','product_categories.product_id','=','products.id')
                ->join('categories','categories.id','=','product_categories.cat_id')
                  ->join('customers','orders.customer_id','=','customers.id')
                // ->where('products.vendor_id',43)
                  ->where('order_details.order_status',$type); 
                  
				if($str!='All' && $str!=''){
                        $Orders=$Orders->where(function($query) use ($str){
                        $query->orWhere('orders.order_no','LIKE',$str.'%');
                        $query->orWhere('order_details.suborder_no','LIKE',$str.'%');
						$query->orWhere('orders.exhibition_code','LIKE',$str.'%');

                        });
             
				//   $Orders=$Orders
				// 		->where('orders.order_no','LIKE',$str.'%')
				// 		->orWhere('order_details.suborder_no','LIKE',$str.'%');
				}
				
				if($brands!='All' &&  $brands!=''){
		    	$selcted_brand=explode(",",$brands);
		    
				   $Orders=$Orders->whereIn('products.product_brand',$selcted_brand);
		} 
		   
                             
			if($category_id!='All' && $category_id!=''){
		  	$Orders =$Orders->where('product_categories.cat_id',$category_id);
		} 
            
				 if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                            $from= date("Y-m-d", strtotime($daterange_array[0]));
                            $to=date("Y-m-d", strtotime($daterange_array[1]));
                    
                    $Orders->whereDate('orders.order_date', '>=',$from);
                      $Orders->whereDate('orders.order_date', '<=',$to);
                    
        //           $Orders=$Orders
				 			//  ->whereBetween('orders.order_date',[$from,$to]);
                }
            
			$Orders=$Orders->orderBy('order_details.id','desc')->groupBy('order_details.id')->paginate(100);
			
			

			$parameters = $request->type;
		$parameters_level = base64_decode($parameters);
             

		return view('vendor.orders.list',
	 ['orders'=>$Orders,'parameters_level'=>$parameters_level,'str'=>$str,'daterange'=>$daterange,'vendor'=>$vendor,'page_details'=>$page_details,'vendors'=>$vendors,'Brands'=>$Brands1]
		);
    }
    
	public function exportvorders(Request $request)
    {
		$str=$request->str;	
        $daterange=$request->daterange;	
        $type= base64_decode($request->type);
        $category =$request->category;
        $brands =$request->brand;
		return Excel::download(new OrdervExport($type,$str,$daterange,$category,$brands), 'Orders'.date('d-m-Y H:i:s').'.csv');
    }
    
    public function exportsearchorder(Request $request)
    {
		$str=$request->str;	
        $daterange=$request->daterange;	
        $type= base64_decode($request->type);
        $category =$request->category;
        $brands =$request->brand;
		return Excel::download(new OrdervExport($type,$str,$daterange,$category,$brands), 'Orders'.date('d-m-Y H:i:s').'.csv');
    }
	public function orderdetail(Request $request)
	{
		$page_details=array(
		   "Title"=>"Order Details",
		   "Box_Title"=>"Order Details"
		 );
		
		$id= base64_decode($request->order_detail_id);
		
		$Order =Orders::select(
		     'orders.delivery_time','orders.delivery_day','orders.delivery_date',
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
						'order_details.color',
						'order_details.color_id',
						'order_details.size_id',
						'order_details.w_size',
						'order_details.order_coupon_amount',
						'order_details.order_wallet_amount',
						'order_details.order_cod_charges',
						'order_details.order_shipping_charges',
						'order_details.slot_price'
						)
					
					
					
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('order_details.id',$id)
					->get()->toarray();
				// 	echo "<pre>";
				// 	print_r($Order);
				// 	die();
					
			 return view('vendor.orders.detail',['Order'=>$Order,'page_details'=>$page_details]);
	}
	
	public function order_cancel(Request $request)
	{
		$id= base64_decode($request->order_detail_id);
		
		$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.order_status'=>4
				]);
		CommonHelper::generateMailforOrderSts($id,7);
		return Redirect::route('vendor_orders',[base64_encode(0)]);
		 
	}
	
	public function orderdetail_shipping(Request $request)
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
						'order_details.id as order_detail_id',
						'order_details.suborder_no',
						'order_details.product_id',
						'order_details.product_name',
						'order_details.product_qty',
						'order_details.product_price',
						'order_details.size',
						'order_details.color',
						'order_details.w_size'
						)
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('order_details.order_id',$id)
					->where('order_details.order_status',1)
					->get()->toarray();
				// 	echo "<pre>";
				// 	print_r($Order); 
				// 	die();
					
			 return view('vendor.orders.detail_shipping',['Order'=>$Order,'page_details'=>$page_details]);
	}
	
	public function vendor_order_generate_invoice(Request $request)
    {

		$id= base64_decode($request->order_detail_id);
		
	 	
		$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.order_status'=>1,
					'order_details.order_detail_invoice_num'=>'PHT/BLR/'.$id,
					'order_details.order_detail_invoice_date'=>date('Y-m-d H:i:s')
				]);
		 CommonHelper::generateMailforOrderSts($id,0);
		
		return Redirect::route('vendor_order_invoice',[$request->order_detail_id]);
    }
	public function vendor_order_completed(Request $request)
    {

		$id= base64_decode($request->order_detail_id);
		
	 	
		$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.order_status'=>8,
					'order_details.order_detail_invoice_num'=>'PHT/BLR/'.$id,
					'order_details.order_detail_invoice_date'=>date('Y-m-d H:i:s')
				]);
		// CommonHelper::generateMailforOrderSts($id,0);
		$ordertype=base64_encode('8');
		return Redirect::route('vendor_orders',[$ordertype]);
    }
	
	public function vendor_order_print_invoice(Request $request)
    {

		$id= base64_decode($request->order_detail_id); 
		
	 	
		$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.order_status'=>1,
					'order_details.order_detail_invoice_num'=>'PHT/BLR/'.$id,
					'order_details.order_detail_invoice_date'=>date('Y-m-d H:i:s')
				]);
		
		return Redirect::route('vendor_order_invoice',[$request->order_detail_id]);
    }
	
	public function vendor_order_invoice(Request $request)
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
								'orders.payment_mode',
								'orders.customer_id',
								'orders.shipping_id',
								'orders.deduct_reward_points',
								'orders.discount_amount',
								'orders.grand_total',
								'orders.coupon_percent',
								'orders.coupon_code',
								'orders.coupon_amount',
								'orders.invoice_num',
								'orders.cod_charges',
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
								'order_details.suborder_no',
								'order_details.product_qty',
								'order_details.product_price',
								'order_details.product_price_old',
								'order_details.order_shipping_charges',
									'order_details.order_cod_charges',
								'order_details.product_tax',
								'order_details.order_deduct_reward_points',
								'order_details.order_coupon_amount',
									'order_details.order_wallet_amount',
								'order_details.size',
								'order_details.color',
								'order_details.w_size',	'order_details.slot_price'
								)
							
							->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->where('order_details.id',$id)
							->get()->toarray();
							
						
						
						
                            // $vdr_id=43;
                            $vdr=new Vendor();
                            $vdr_id=$vdr->vendor_id($id,1);
                            
                            $vdr_data=$vdr->getVendorDetails($vdr_id);
							

							$billingAddress = [];
							if(!empty($Order)){
							$billingAddress = DB::table('orders_billing_addresses')
							->where('order_id', $Order[0]['id'])
							->first();
							}
						
							$pdfData = ['billingAddress'=>$billingAddress,'Order'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data];
							$type = $request->download;
							if($type == 1){
							   $pdf=PDF::loadView('vendor.orders.invoice_test',$pdfData);
							   return $pdf->download($Order[0]['suborder_no'].'_product_invoice.pdf'); 
							}
			 return view('vendor.orders.invoice_test',['billingAddress'=>$billingAddress,'Order'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data]);	
    }



	public function vendor_order_seller_invoice(Request $request){
		$page_details=array(
			"Title"=>"Seller Invoice",
			"Box_Title"=>"Seller Invoice"
		  );
 
	   $id= base64_decode($request->order_detail_id);
	   $Order =Orders::select(
								 'orders.order_no',
								 'orders.order_date',
								 'orders.updated_at',
								 'orders.id',
								 'orders.payment_mode',
								 'orders.customer_id',
								 'orders.shipping_id',
								 'orders.deduct_reward_points',
								 'orders.discount_amount',
								 'orders.grand_total',
								 'orders.coupon_percent',
								 'orders.coupon_code',
								 'orders.coupon_amount',
								 'orders.invoice_num',
								 'orders.cod_charges',
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
								 'order_details.suborder_no',	
								 'order_details.order_detail_invoice_num',
								 'order_details.order_detail_invoice_date',
								 'order_details.product_name',
								 'order_details.product_qty',
								 'order_details.product_price',
								 'order_details.product_price_old',
								 'order_details.order_shipping_charges',
								 'order_details.order_cod_charges',
								 'order_details.product_tax',
								 'order_details.order_deduct_reward_points',
								 'order_details.order_coupon_amount',
								 'order_details.order_wallet_amount',
								 'order_details.size',
								 'order_details.color',
								 'order_details.w_size',	
								 'order_details.slot_price',
								 'order_details.courier_charges',	
								 'order_details.seller_invoice_tcs',	
								 'order_details.payment_gateway_tax',	
								 'order_details.logistics_tax',	
								 'order_details.seller_invoice_date',
								 'order_details.seller_invoice_num',
								 )
							 ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
							 ->join('order_details', 'orders.id', '=', 'order_details.order_id')
							 ->where('order_details.id',$id)
							 ->first();
							
							 $vdr=new Vendor();
							 $vdr_id=$vdr->vendor_id($id,1);							 
							 $vdr_data=$vdr->getVendorDetails($vdr_id);
							 
							 /**
							  * Updating seller invoice number and date
							  */
							  if(empty($Order->seller_invoice_date)){
								OrdersDetail::where('id', $id)->update([
									'seller_invoice_date' => date('Y-m-d H:i'),
									'seller_invoice_num'  => 'SINUM'.$id,
								]);

								$Order =Orders::select(
									'orders.order_no',
									'orders.order_date',
									'orders.updated_at',
									'orders.id',
									'orders.payment_mode',
									'orders.customer_id',
									'orders.shipping_id',
									'orders.deduct_reward_points',
									'orders.discount_amount',
									'orders.grand_total',
									'orders.coupon_percent',
									'orders.coupon_code',
									'orders.coupon_amount',
									'orders.invoice_num',
									'orders.cod_charges',
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
									'order_details.suborder_no',	
									'order_details.order_detail_invoice_num',
									'order_details.order_detail_invoice_date',
									'order_details.product_name',
									'order_details.product_qty',
									'order_details.product_price',
									'order_details.product_price_old',
									'order_details.order_shipping_charges',
									'order_details.order_cod_charges',
									'order_details.product_tax',
									'order_details.order_deduct_reward_points',
									'order_details.order_coupon_amount',
									'order_details.order_wallet_amount',
									'order_details.size',
									'order_details.color',
									'order_details.w_size',	
									'order_details.slot_price',
									'order_details.courier_charges',	
									'order_details.seller_invoice_tcs',	
									'order_details.payment_gateway_tax',	
									'order_details.logistics_tax',	
									'order_details.seller_invoice_date',
									'order_details.seller_invoice_num',
									)
								->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
								->join('order_details', 'orders.id', '=', 'order_details.order_id')
								->where('order_details.id',$id)
								->first();
							  }
							 
							  $type = $request->download; 
							  $pdfData = ['sellerData' => $vdr_data,'Order'=>$Order,'page_details'=>$page_details,'is_front'=>1];
		  
							  if($type == 1){
								  $pdf=PDF::loadView('vendor.orders.seller_invoice',$pdfData);
								  return $pdf->download($Order->suborder_no.'_seller_invoice.pdf'); 
							   }  		 

			  return view('vendor.orders.seller_invoice',['sellerData' => $vdr_data,'Order'=>$Order,'page_details'=>$page_details]);
	 
	 }
    
    public static function vendor_order_shipping_master_data_load($order_detail_id)
    {
        $id=$order_detail_id; 
       
        /**
         * Getting Order Details data
         */
		 
		 $id=explode(',',$order_detail_id);  
		 
		 $order_details = DB::table('order_details')->whereIn('id',$id)->get();
		 
		 $order_data=$shipping_data=$vendor_pickup_address_data='';
		 $suborder_no='';
		 $product_name='';
		 $product_order_qty=0;
		 $order_price=0;
		 foreach($order_details as $order_details){
			 
			 $suborder_no.=$order_details->suborder_no.' ';
			 $product_name.=$order_details->product_name.' ';
			 $product_order_qty+=$order_details->product_qty;
			 
        $order_price+=($order_details->product_qty*$order_details->product_price)+$order_details->order_shipping_charges+$order_details->order_cod_charges-$order_details->order_coupon_amount-$order_details->order_wallet_amount;                     
       
			 //$order_price+= $order_details->product_price * $order_details->product_qty;
			 
			$order_data = DB::table('orders')->where('id',$order_details->order_id)->first(); 
            $product_data = DB::table('products')->where('id',$order_details->product_id)->first();
			
			$shipping_data = DB::table('orders_shipping')
                ->join('states', 'orders_shipping.order_shipping_state', '=', 'states.name')
                ->join('cities', 'orders_shipping.order_shipping_city', '=', 'cities.name')
				//->join('states', 'orders_shipping.order_shipping_state', '=', 'states.id')
                //->join('cities', 'orders_shipping.order_shipping_city', '=', 'cities.id')
                ->select('orders_shipping.*', 'states.name as state_name', 'cities.name as city_name')
                ->where('orders_shipping.id',$order_data->shipping_id)->first();
				
			$vendor_pickup_address_data = DB::table('vendor_company_info')->where('vendor_id',$product_data->vendor_id)->first();
		 }
		 
		 /*if(!empty($order_details)){
            $order_data = DB::table('orders')->where('id',$order_details->order_id)->first(); 
            $product_data = DB::table('products')->where('id',$order_details->product_id)->first();
         }
         
         if(!empty($order_data)){
                
                $shipping_data = DB::table('orders_shipping')
                ->join('states', 'orders_shipping.order_shipping_state', '=', 'states.name')
                ->join('cities', 'orders_shipping.order_shipping_city', '=', 'cities.name')
                ->select('orders_shipping.*', 'states.name as state_name', 'cities.name as city_name')
                ->where('orders_shipping.id',$order_data->shipping_id)->first();
         }
         
         if(!empty($product_data)){
                
                $vendor_pickup_address_data = DB::table('vendor_company_info')->where('vendor_id',$product_data->vendor_id)->first();

         }*/
        
         $masterData = array(   'payment_mode' => $order_data->payment_mode,
                                'order_number' => $suborder_no,
                                'delivery_pincode' => @$shipping_data->order_shipping_zip,
                                'pickup_pincode' => $vendor_pickup_address_data->pincode,
                                'pickup_contact_name' => $vendor_pickup_address_data->name,
                                'pickup_address1' => $vendor_pickup_address_data->address,
                                'pickup_city' => $vendor_pickup_address_data->city,
                                'pickup_state' => $vendor_pickup_address_data->state,
                                
                                'delivery_contact_name' => @$shipping_data->order_shipping_name,
                                'delivery_address1' => '#'.@$shipping_data->order_shipping_address,
                                'delivery_address2' =>  @$shipping_data->order_shipping_address1,
                                'delivery_address3' =>  @$shipping_data->order_shipping_address2,
                                //'delivery_city' =>  @$shipping_data->order_shipping_city,
                                //'delivery_state' =>  @$shipping_data->order_shipping_state,
                                //'delivery_country' => $shipping_data->order_shipping_country,
								'delivery_city' =>  @$shipping_data->city_name,
                                'delivery_state' =>  @$shipping_data->state_name,
                                'delivery_country'=>"IN",
                                'delivery_mobile' => @$shipping_data->order_shipping_phone,
                                'delivery_email' => @$shipping_data->order_shipping_email,
                                
								'product_name' => $product_name,
                                'product_order_qty' => $product_order_qty,
                                'order_price' => $order_price,
                             );
                             
        return $masterData;
    }
    public static function after_return_request_vendor_order_shipping_master_data_load($order_detail_id)
    {
        $id=$order_detail_id; 
       
        /**
         * Getting Order Details data
         */
		 
		 $id=explode(',',$order_detail_id);  
		 
		 $order_details = DB::table('order_details')->whereIn('id',$id)->get();
		 
		 $order_data=$shipping_data=$vendor_pickup_address_data='';
		 $suborder_no='';
		 $product_name='';
		 $product_order_qty=0;
		 $order_price=0;
		 foreach($order_details as $order_details){
			 
			 $suborder_no.=$order_details->suborder_no.' ';
			 $product_name.=$order_details->product_name.' ';
			 $product_order_qty+=$order_details->product_qty;
			 
        $order_price+=($order_details->product_qty*$order_details->product_price)+$order_details->order_shipping_charges+$order_details->order_cod_charges-$order_details->order_coupon_amount-$order_details->order_wallet_amount;                     
       
			$order_data = DB::table('orders')->where('id',$order_details->order_id)->first(); 
            $product_data = DB::table('products')->where('id',$order_details->product_id)->first();
			
			$shipping_data = DB::table('orders_shipping')
                ->join('states', 'orders_shipping.order_shipping_state', '=', 'states.name')
                ->join('cities', 'orders_shipping.order_shipping_city', '=', 'cities.name')
                ->select('orders_shipping.*', 'states.name as state_name', 'cities.name as city_name')
                ->where('orders_shipping.id',$order_data->shipping_id)->first();
				
			$vendor_pickup_address_data = DB::table('vendor_company_info')->join('vendors','vendor_company_info.vendor_id','vendors.id')->where('vendors.id',$product_data->vendor_id)->first();
		 }
		 
	
        
         $masterData = array(   'payment_mode' => $order_data->payment_mode,
                                'order_number' => $suborder_no,
                            'delivery_pincode' =>  $vendor_pickup_address_data->pincode,
                            'pickup_pincode' => @$shipping_data->order_shipping_zip,
                            
                                'pickup_contact_name' =>  @$shipping_data->order_shipping_name,
                                'pickup_address1' =>@$shipping_data->order_shipping_address.' '.@$shipping_data->order_shipping_address1.' '.@$shipping_data->order_shipping_address2 ,
                                'pickup_city' =>  @$shipping_data->city_name,
                                'pickup_state' => @$shipping_data->state_name ,
                                
                                'pickup_mobile' => @$shipping_data->order_shipping_phone ,
                                'pickup_email' => @$shipping_data->order_shipping_email ,
                                
                                'delivery_contact_name' => $vendor_pickup_address_data->name,
                                'delivery_address1' =>   'Ground floor, sf no',
                                'delivery_address2' =>  '133-134, road,',
                                'delivery_address3' =>  'Lic colony 1st street, college road ,',
								'delivery_city' =>  $vendor_pickup_address_data->city,
                                'delivery_state' =>  $vendor_pickup_address_data->state, 
                                'delivery_country'=>"IN",
                                
                                
                                'delivery_mobile' => $vendor_pickup_address_data->phone	,
                                'delivery_email' => $vendor_pickup_address_data->email,
                                
								'product_name' => $product_name,
                                'product_order_qty' => $product_order_qty,
                                'order_price' => $order_price,
                             );
                             
        return $masterData;
    }
    
    public function vendor_order_shipping_extradetails(Request $request){
             
    $id= base64_decode($request->order_detail_id);
	
	$multiple_order_detail_id=$request->select_check; 
	$multi_order_detail_id=''; 
	if(count($multiple_order_detail_id)>0)
	{
		$multi_order_detail_id=implode(',',$multiple_order_detail_id);
		
		$Order = Orders::select('orders.grand_total')
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->whereIn('order_details.id',$multiple_order_detail_id)
							->first();
	}else{
		 $Order = Orders::select('orders.grand_total')
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->where('order_details.id',$id)
							->first();
	}
							
            $page_details = array(
                                    "Title"=>"Shipping Extra Details",
		                            "Box_Title"=>"Shipping Extra Details",
		                            "Grand_Total"=>$Order->grand_total,
		                            
		                            "Form_data"=>array(
                                                         "Form_field"=>array(
                                                            
                                                               "phy_weight"=>array(
                                                							'label'=>'Weight (Kg) *',
                                                							'type'=>'text',
                                                							'name'=>'phy_weight',
                                                							'id'=>'phy_weight',
                                                							'classes'=>'form-control',
                                                							'placeholder'=>'',
                                                							'value'=>'',
                                                							'disabled'=>''
                                                									),
                                                			 
                                                			"ship_length"=>array(
                                                							'label'=>'Length (CM) *',
                                                							'type'=>'text',
                                                							'name'=>'ship_length',
                                                							'id'=>'ship_length',
                                                							'classes'=>'form-control',
                                                							'placeholder'=>'',
                                                							'value'=>'',
                                                							'disabled'=>'',
                                                						
                                                			),
                                                			
                                                			"ship_width"=>array(
                                                							'label'=>'Width (CM) *',
                                                							'type'=>'text',
                                                							'name'=>'ship_width',
                                                							'id'=>'ship_width',
                                                							'classes'=>'form-control',
                                                							'placeholder'=>'',
                                                							'value'=>'',
                                                							'disabled'=>'',
                                                						
                                                			),
                                                			
                                                				"package_description"=>array(
                                                							'label'=>'Package description',
                                                							'type'=>'textarea',
                                                							'name'=>'package_description',
                                                							'id'=>'package_description',
                                                							'classes'=>'form-control',
                                                							'placeholder'=>'',
                                                							'value'=>'',
                                                							'disabled'=>'',
                                                						
                                                			),
                                                			
                                                				"ship_height"=>array(
                                                							'label'=>'Height (CM) *',
                                                							'type'=>'text',
                                                							'name'=>'ship_height',
                                                							'id'=>'ship_height',
                                                							'classes'=>'form-control',
                                                							'placeholder'=>'',
                                                							'value'=>'',
                                                							'disabled'=>'',
                                                						
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
            
        	return view('vendor.orders.shipping_extra_details',['page_details'=>$page_details,'multiple_order_detail_id'=>$multi_order_detail_id]);
    }
	
	public function vendor_order_shipping(Request $request)
    {
        $data_inputs=$request->all();
        $id= base64_decode($request->order_detail_id);
		
		$request->validate([
                                'phy_weight' => 'required|numeric',
                                'ship_length' => 'required|numeric',
                                'ship_width' => 'required|numeric',
                                'ship_height' => 'required|numeric',
                                 'package_description' => 'max:255'
                                ],[
                                    'phy_weight.required'=>'Weight is required',
                                    'phy_weight.numeric'=>'Weight should numeric',
                                    'ship_length.numeric'=>'Length should numeric',
                                    'ship_length.required'=>'Length  is required',
                                    'ship_width.numeric'=>'Width numeric',
                                    'ship_width.required'=>'Width is required',
                                    'ship_height.numeric'=>'Height should numeric',
                                    'ship_height.required'=>'Height is required',
                                    'package_description.max'=>'Description should nor be greater then 255 characters'
                                ]);
        
		 
         $multiple_order_detail_id=$request->select_check; 
        // $multiple_order_detail_id='303,304';
		//$multiple_order_detail_id='305'; 
		$multi_order_detail_id='';
		if(count($multiple_order_detail_id)>0)
		{
			$multi_order_detail_id=explode(',',$multiple_order_detail_id); 
			
			/*$getpID=DB::table('order_details')->whereIn('id',$multi_order_detail_id)->get();
			
			foreach($getpID as $check_prd)
			{
				$prodcut_detail=DB::table('products')->where('id',$check_prd->product_id)->first();
			}*/
			$prd_name='';
			$prd_qty=$prd_price=0;
			foreach($multi_order_detail_id as $check_id)
			{
				$masterData=self::vendor_order_shipping_master_data_load($check_id);
				
				$prd_name.=$masterData['product_name'];
				$prd_qty+=$masterData['product_order_qty'];
				$prd_price+=$masterData['order_price'];
			}
			$masterData['product_name']=$prd_name;
			$masterData['product_order_qty']=$prd_qty;
			$masterData['order_price']=$prd_price;
		
		}else{
			//$getpID=DB::table('order_details')->where('id',$id)->first();
			//$prodcut_detail=DB::table('products')->where('id',$getpID->product_id)->first();
			
			$masterData=self::vendor_order_shipping_master_data_load($id);
		}
		                     
        $ShipmentChargesData = array(
                                'delivery_pincode' => $masterData['delivery_pincode'],
								//'delivery_pincode' => 110011,
                                'pickup_pincode' => $masterData['pickup_pincode'],
                                'product_name' => $masterData['product_name'],
                                'qty' => $masterData['product_order_qty'],
                                'price' => $masterData['order_price'],
                                // 'weight'=>'0.460',
                                // 'height'=>'2',
                                // 'length'=>'32',
                                // 'width'=>'36',
								'weight'=>$request->input('phy_weight'),
                                'height'=>$request->input('ship_length'),
                                'length'=>$request->input('ship_width'),
                                'width'=>$request->input('ship_height'),
                             );
							 
                             		 
        $back_response=CommonHelper::ShipmentCharges($ShipmentChargesData);
        $output = (array)json_decode($back_response);
       
    
       
        if($output['success']==1)
        {
            $data=@$output['couriers'];
            
            foreach($data as $row)
            {
                $service_types=$row->service_types;
                
                foreach($row->service_types as $services)
                {
                    $courier_list[]=array(
    'courier_id'=>$row->courier_id,
    'courier_name'=>$row->name,
    'courier_charges'=>$services->rate_breakup->total_charge,
    'codcharges'=>$services->rate_breakup->COD_charges,
    'courier_charges1'=>$services->rate_breakup->courier_total_charge,
    'codcharges1'=>$services->rate_breakup->courier_COD_charges,
    'service_id'=>$services->service_id,
    'service_name'=>$services->service_name,
    'display_name'=>$services->display_name,
    'expected_pickup_date'=>$row->exp_pickup_date,
    'expected_delivery'=>$services->expected_delivery_days
                 );
                }
    
            }
            
            $pagedata = array(
                                'Title' => 'Available Couriers',
                                'Box_Title' => 'Available Couriers',
                                );
            
            return view('vendor.orders.order_shipping_courier_list',['page_details'=>$pagedata,'package_content'=>$request->input('package_description'),'courier_list'=>$courier_list,'shipment_measure'=>$ShipmentChargesData,'multiple_order_detail_id'=>$multiple_order_detail_id,'order_detail_id'=>$id]);
        }

    }
    
    public function vendor_order_shipping_couirer_pickup(Request $request)
    {$pikup_list =array();
         $pagedata = array(
                                'Title' => 'Pikup Request',
                                'Box_Title' => 'Pikup Request',
                                );
        $id= $request->order_detail_id;
		
		$multiple_order_detail_id=$request->select_check; 
		$multi_order_detail_id=''; 
		if(count($multiple_order_detail_id)>0)
		{
			$multi_order_detail_id=explode(',',$multiple_order_detail_id); 
			$id=$multiple_order_detail_id;
		}else{
			
		}
        
        $masterData=self::vendor_order_shipping_master_data_load($id);
        //$getpID=DB::table('order_details')->where('id',$id)->first();
        $vendor_info =DB::table('vendor_company_info')->where('vendor_id',43)->first();
         $vendor =DB::table('vendors')->where('id',43)->first();
        
       // echo $vendor_info->name;die;
        
        //$prodcut_detail=DB::table('products')->where('id',$getpID->product_id)->first();
        
        if($masterData['payment_mode']==0)
		    $payment_mode='COD';
		else
		    $payment_mode='online';
        
        $PickupRequestData = array(
                                /*'payment_mode'=>$masterData['payment_mode'],
                                'pickup_company_name'=>$vendor_info->name,
                                'company_name'=>$vendor_info->name,
                                'courier_id'=>$request->courier_id,
                                'service_id'=>$request->service_id,
                                'service_type'=>$request->service_name,
                                'pickup_address2'=>$vendor_info->address,
                                'pickup_landmark'=>'near sub-hub market',
                                'pickup_contact'=>$vendor->phone,
                                'pickup_email'=>$vendor->email,
                                'delivery_landmark'=>'Landmark is not available',
                                'package_content'=>'sdfefs',
                                'weight'=>$prodcut_detail->weight,
                                'height'=>$prodcut_detail->height,
                                'length'=>$prodcut_detail->length,
                                'width'=>$prodcut_detail->width,*/
                                'payment_mode'=>$payment_mode,
                                //'pickup_company_name'=>'Arbizon ecommerce India pvt ltd',
                                'pickup_company_name'=>'test.com',
                                'company_name'=>$masterData['delivery_contact_name'],
                                'courier_id'=>$request->courier_id,
                                'service_id'=>$request->service_id,
                                'service_type'=>$request->service_name,
                                'pickup_address2'=>$masterData['pickup_address1'],
                                'pickup_landmark'=>'',
                                'pickup_contact'=>$vendor->phone,
                                //'pickup_email'=>'connect@tst.com',
                                'pickup_email'=>$vendor->email,
                                'delivery_landmark'=>'',
                                'package_content'=>$request->input('package_description'),
								'weight'=>$request->input('phy_weight'),
                                'height'=>$request->input('ship_length'),
                                'length'=>$request->input('ship_width'),
                                'width'=>$request->input('ship_height'),
                                
                                'order_number' => $masterData['order_number'],
                                'order_total' => $masterData['order_price'],
                                'delivery_pincode' => $masterData['delivery_pincode'],
								'pickup_pincode' => $masterData['pickup_pincode'],
                                'pickup_contact_name' => $masterData['pickup_contact_name'],
                                'pickup_address1' => $masterData['pickup_address1'],
                                'pickup_city' => $masterData['pickup_city'],
                                'pickup_state' => $masterData['pickup_state'],
                                'delivery_contact_name' => $masterData['delivery_contact_name'],
                                'delivery_address1' => $masterData['delivery_address1'],
                                'delivery_address2' =>  ($masterData['delivery_address2']==null)?$masterData['delivery_address1']:$masterData['delivery_address2'],
                                'delivery_address3' =>  $masterData['delivery_address3'],
                                'delivery_city' => $masterData['delivery_city'],
                                'delivery_state' =>  $masterData['delivery_state'],
                                'delivery_country' => $masterData['delivery_country'],
                                'delivery_mobile' => $masterData['delivery_mobile'],
                                'delivery_email' => $masterData['delivery_email'],
                             );
        
        
        file_put_contents('pickup.txt',json_encode($PickupRequestData));
        $back_response1=CommonHelper::PickupRequest($PickupRequestData);
       
         file_put_contents('pickup123.txt',$back_response1);
        $output1 = (array)json_decode($back_response1);
        
        
        if(@$output1['success']==1)
        {
            $data=$output1['shipmentPackages'];
			
			DB::table('order_details')
					->where('id', $id)
					->update([
					'weight'=>$request->input('phy_weight'),
					'height'=>$request->input('ship_length'),
					'length'=>$request->input('ship_width'),
					'width'=>$request->input('ship_height'),
					'package_description'=>$request->input('package_description'),
					]);
           
           
            foreach($data as $row)
            {
            	
                $shipment_id=$output1['shipment_id'];
                
                $package_detail=$row->package_detail;
                 $pikup_list[]=array(
                            'shipment_id'=>$shipment_id,
                            'no_of_items'=>$package_detail->no_of_items,
                            'package_content'=>$package_detail->package_content,
                            'invoice_value'=>$package_detail->invoice_value,
                           
                             );
             
            }
            
            }
            
            
            return view('vendor.orders.order_shipping_pikup_list',['page_details'=>$pagedata,'pikup_list'=>$pikup_list,'multiple_order_detail_id'=>$multiple_order_detail_id,'order_detail_id'=>$id]);
       
        
    }
    public function after_return_vendor_order_shipping_couirer_orders(Request $request){
    	$ordercode='';
		$pagedata = array(
				'Title' => 'Couirer Orders',
				'Box_Title' => 'Couirer Orders',
				);
        $id= $request->order_detail_id;
		
		$multiple_order_detail_id=$request->select_check; 
		$multi_order_detail_id=''; 
		if(count($multiple_order_detail_id)>0)
		{
			//$multi_order_detail_id=explode(',',$multiple_order_detail_id); 
			$masterData=self::vendor_order_shipping_master_data_load($multiple_order_detail_id);
			$id1=explode(',',$multiple_order_detail_id); 
			$getpID=DB::table('order_details')->whereIn('id',$id1)->get();
			$id=$multiple_order_detail_id; 
			$sub_order_no='';
			foreach($getpID as $row)
			{
				$sub_order_no.=' '.$row->suborder_no;
			}
		}else{
			$masterData=self::vendor_order_shipping_master_data_load($id);
			$id1=explode(',',$id); 
			$getpID=DB::table('order_details')->whereIn('id',$id1)->first();
			$id=$id;
			$sub_order_no=$getpID->suborder_no;
		}
        
        
        
        //$prodcut_detail=DB::table('products')->where('id',$getpID->product_id)->first();
        $sub_order_no0=$sub_order_no1=$sub_order_no2='';
		$ss=explode(' ',trim($sub_order_no));
		if(count($ss)>0){
			for($i=0;$i<count($ss);$i++)
			{
				if($i==0)
					$sub_order_no0=$ss[$i];
				if($i==1)
					$sub_order_no1=$ss[$i];
				if($i==2)
					$sub_order_no2=$ss[$i];
			}
		}else{
			$sub_order_no0=trim($sub_order_no);
		}
		
        $shiporderRequestData = array(
                               "shipmentIds"=>$request->shipment_id,
                               "udf1"=>$sub_order_no0,
							   "udf2"=>$sub_order_no1,
							   "udf3"=>$sub_order_no2,
                               
                               //"client_source": "test",
                             );
        
        
        $back_response1=CommonHelper::ShipmentOrder($shiporderRequestData);
      
          $output1 = (array)json_decode($back_response1);
        if($output1['success']==1)
        {
        	$ordercode =$output1['order_code'];
        	DB::table('replace_order_details')
								  ->where('id',$id)
								  ->update(
								      array(
								          'order_status'=>1
								          )
								      );
								       
        CommonHelper::generateMailforOrderSts($id,3);
		}
        
       
    return view('vendor.orders.after_return_courier_orders_list',[
    'page_details'=>$pagedata,
    'couirer_orders'=>$output1,
	'multiple_order_detail_id'=>$multiple_order_detail_id,
    'order_detail_id'=>$id,
    'ordercode'=>$ordercode
    ]);     
        
	}
	
	public function CourierOrderInfo(Request $request){
		$id= $request->order_detail_id;
		 
		$multiple_order_detail_id=$request->select_check; 
		$multi_order_detail_id=''; 
		if(count($multiple_order_detail_id)>0)
		{
			$id=explode(',',$multiple_order_detail_id); 
			
		}else{
			$id=explode(',',$id); 
		}
		 
		 
		$ordercode = array(
		"ordercode"=>$request->ordercode
		);
		
        $back_response1=CommonHelper::CourierOrder($ordercode);
        $output1 = (array)json_decode($back_response1);
      
         if($output1['success']==1){
         	
         	$data1 = $output1['shipments'];
		 
		  foreach($data1 as $row)
          {
            	//$pp=implode(',',$id);
				
            $shipmentPackages=$row->shipmentPackages;
            $pickup=$row->pickup;
           
			   for($m=0;$m<count($id);$m++)
			   {			   
					$data = array(
							"order_detail_id"=>$id[$m],
							"shipping_status"=>$output1['status'],
							"shipping_amount"=>$output1['amount'],
							"order_no"=>$output1['udf1'],
							"paymentMode"=>$output1['paymentMode'],
							"shipment_id"=>$row->shipment_id,
							"courier_id"=>$row->courier_id,
							"courier_name"=>$row->courier_name,
							"courier_logo_url"=>$row->courier_logo_url,
							"service_id"=>$row->service_id,
							"service_type"=>$row->service_type,
							"service_display_name"=>$row->service_display_name,
							"payment_mode"=>$row->payment_mode,
							"invoice_value"=>$row->invoice_value,
							"pickup_date"=>$pickup->pickup_date,
							"forward_label"=>$shipmentPackages->forward_label,
							//"no_of_items"=>$row->shipment_id,
							//"weight"=>$row->shipment_id,
						   // "length"=>$row->shipment_id,
							//"width"=>$row->shipment_id,
						   // "height"=>$row->shipment_id,
							"tracking_number"=>$shipmentPackages->tracking_number,
						   // "min_delivery_days"=>$row->shipment_id,
							//"max_delivery_days"=>$row->shipment_id,
							"order_code"=>$request->ordercode,
						);
					
				   DB::table('tbl_courierorderinfo')->insert($data);
				   
				/*DB::table('order_details')
					->whereIn('id', $id[$m])
					->update([
					"order_detail_onshipping_date"=>date("h:sa M d, Y"),
					//'weight'=>$prodcut_detail->weight,
                   // 'height'=>$prodcut_detail->height,
                    //'length'=>$prodcut_detail->length,
                   // 'width'=>$prodcut_detail->width,
                    'order_status'=>2
					]);
					
			CommonHelper::generateMailforOrderSts($id[$m],1); */
			
			$pp=$id[$m];
			DB::table('order_details')
						->where('id', $pp)
						->update([
						'order_status'=>2,
                        'order_detail_onshipping_date'=>date('Y-m-d H:i:s')
						]);
			
			//CommonHelper::generateMailforOrderSts($pp,1);
						
			   }
			}
        
       
        
	}
return redirect()->route('vendor_orders', ['type' => base64_encode(2)])->with('flash_success', 'Pikup Request send successfully');  
        
	}
	public function afterCourierOrderInfo(Request $request){
		$id= $request->order_detail_id;
		
		$isReplaced=DB::table('replace_order_details')
								  ->where('sub_order_id',$id)
								  ->update(
								      array(
								          'order_status'=>1
								          )
								      );
								       
        
         CommonHelper::generateMailforOrderSts($id,3);
		 
		$multiple_order_detail_id=$request->select_check; 
		$multi_order_detail_id=''; 
		if(count($multiple_order_detail_id)>0)
		{
			$id=explode(',',$multiple_order_detail_id); 
			
		}else{
			$id=explode(',',$id); 
		}
		 
		 
		$ordercode = array(
		"ordercode"=>$request->ordercode
		);
		
        $back_response1=CommonHelper::CourierOrder($ordercode);
        $output1 = (array)json_decode($back_response1);
      
         if($output1['success']==1){
         	
         	$data1 = $output1['shipments'];
		 
		  foreach($data1 as $row)
          {
            	//$pp=implode(',',$id);
				
            $shipmentPackages=$row->shipmentPackages;
            $pickup=$row->pickup;
           
			   for($m=0;$m<count($id);$m++)
			   {			   
					$data = array(
							"order_detail_id"=>$id[$m],
							"shipping_status"=>$output1['status'],
							"shipping_amount"=>$output1['amount'],
							"order_no"=>$output1['udf1'],
							"paymentMode"=>$output1['paymentMode'],
							"shipment_id"=>$row->shipment_id,
							"courier_id"=>$row->courier_id,
							"courier_name"=>$row->courier_name,
							"courier_logo_url"=>$row->courier_logo_url,
							"service_id"=>$row->service_id,
							"service_type"=>$row->service_type,
							"service_display_name"=>$row->service_display_name,
							"payment_mode"=>$row->payment_mode,
							"invoice_value"=>$row->invoice_value,
							"pickup_date"=>$pickup->pickup_date,
							"forward_label"=>$shipmentPackages->forward_label,
							//"no_of_items"=>$row->shipment_id,
							//"weight"=>$row->shipment_id,
						   // "length"=>$row->shipment_id,
							//"width"=>$row->shipment_id,
						   // "height"=>$row->shipment_id,
							"tracking_number"=>$shipmentPackages->tracking_number,
						   // "min_delivery_days"=>$row->shipment_id,
							//"max_delivery_days"=>$row->shipment_id,
							"order_code"=>$request->ordercode,
						);
					
				   DB::table('tbl_courierorderinfo')->insert($data);
				   
				/*DB::table('order_details')
					->whereIn('id', $id[$m])
					->update([
					"order_detail_onshipping_date"=>date("h:sa M d, Y"),
					//'weight'=>$prodcut_detail->weight,
                   // 'height'=>$prodcut_detail->height,
                    //'length'=>$prodcut_detail->length,
                   // 'width'=>$prodcut_detail->width,
                    'order_status'=>2
					]);
					
			CommonHelper::generateMailforOrderSts($id[$m],1); */
			
			$pp=$id[$m];
		
			
			//CommonHelper::generateMailforOrderSts($pp,1);
						
			   }
			}
        
       
        
	}
return redirect()->route('vendor_orders', ['type' => base64_encode(5)])->with('flash_success', 'Pikup Request send successfully');  
        
	}
	public function vendor_order_shipping_manual(Request $request)
    {

		$cors=DB::table('couriers')->get();
	    $id= base64_decode($request->order_detail_id);
	 
	$page_details=array(
       "Title"=>"Shipping",
		"Method"=>"Shipping",
		"Box_Title"=>"Shipping",
       "Action_route"=>route('vendor_order_shipping',(base64_encode($id))),
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
			
				 /*
				 DB::table('orders_shipping')->where('order_detail_id', $id)
				->update([
				'docket_no'=>$input['docket_no'],
				'courier_name'=>$input['courier'],
				'remarks'=>$input['remarks']
				]);
				 
				 Orders::where('id', $id)
				->update([
				'order_status'=>2
				]);
				
				 */
				  
				 DB::table('orders_courier')
						->insert([
						'order_detail_id'=>$id,
						'docket_no'=>$input['docket_no'],
						'courier_name'=>$input['courier'],
						'remarks'=>$input['remarks']
						]);
				
				DB::table('order_details')
						->where('id', $id)
						->update([
						'order_status'=>2,
                        'order_detail_onshipping_date'=>date('Y-m-d H:i:s')
						]);
						
						CommonHelper::generateMailforOrderSts($id,1);
						
					MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
					return redirect()->route('vendor_orders', ['type' => base64_encode(2)]);
		 }
	
	  return view('vendor.orders.shipping',['orders'=>array(),'page_details'=>$page_details]);
    }
	
	public function vendor_order_delivered(Request $request){
	     $id= base64_decode($request->order_detail_id);
	     CommonHelper::generateMailforOrderSts($id,2);
	     	DB::table('order_details')
						->where('id', $id)
						->update([
						'order_status'=>3,
						 'order_detail_delivered_date'=>date('Y-m-d H:i:s')
						]);
							MsgHelper::save_session_message('success','Order delivered',$request);
					return redirect()->route('vendor_orders', ['type' => base64_encode(3)]);
	}
	
	
		public function vendor_order_returned(Request $request){
	     $id= base64_decode($request->order_detail_id);
	    //CommonHelper::generateMailforOrderSts($id,5);
	     	DB::table('order_details')
						->where('id', $id)
						->update([
						'order_status'=>5,
					
						]);
							MsgHelper::save_session_message('success','Order returned',$request);
					return redirect()->route('vendor_orders', ['type' => base64_encode(5)]);
	}
	
	public function vendor_order_tcs_generate_invoice(Request $request)
    {

		$id= base64_decode($request->vendor_id);
		
		/*$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.order_status'=>1,
					'order_details.order_detail_invoice_num'=>'PHT/BLR/'.$id,
					'order_details.order_detail_invoice_date'=>date('Y-m-d H:i:s')
				]);*/
		 /*CommonHelper::generateMailforOrderSts($id,0);*/
		
		/*return Redirect::route('vendor_order_tcs_invoice',[$request->vendor_id]);*/
		
		$page_details=array(
		   "Title"=>"TCS Settlement Generate Invoice",
		   "Box_Title"=>"TCS Settlement Generate Invoice",
		   "export"=>""
		 );
	  
	    $Order =Orders::select(
								'orders.id',
								'orders.tax_percent',
								'orders.total_commission_rate',
								'orders.tcs_percentage',
								'orders.tds_percentage',
								'orders.order_no',
								'order_details.id as order_detail_id',
								'order_details.suborder_no as suborder_no',
								'order_details.order_date',
								'order_details.tcs_invoice_no',
								'order_details.tcs_invoice_date',
								 DB::raw("SUM(order_details.product_qty*order_details.product_price) AS order_amt"),
								 DB::raw("SUM(order_details.tcs_amt) AS tcs_order_amt"),
								 DB::raw("SUM(order_details.tds_amt) AS tds_order_amt"),
								'order_details.order_commission_rate',
								'order_details.product_tax',
								'order_details.order_detail_tax_amt',
								/*'order_details.tcs_percentage',	*/
								'order_details.tds_amt',
								'order_details.tcs_amt'
							)
							
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->join('products', 'products.id', '=', 'order_details.product_id')
							->where('order_details.order_status',3)
							->whereNull('order_details.tcs_invoice_no')
							->where('products.vendor_id',$id)
							->groupBy('order_details.order_id')
							->paginate(100);
					
						  /*$vdr_id=43;*/
						   $vdr_id=$id;
						   
                            $vdr=new Vendor();
                            //$vdr_id=$vdr->vendor_id($id,1);
                            
                            $vdr_data=$vdr->getVendorDetails($vdr_id); 
							
						
							
			 return view('vendor.orders.tcs_generate_order_invoice',['orders'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data]);
    }
    
    public function vendor_order_tcs_generate_invoice_update(Request $request)
    {
        $id= base64_decode($request->vendor_id);
		
		$order_detail_id=$request->order_detail_id;
		$rand='SS/'.rand(1111,9999);
		
		for($i=0; $i<count($order_detail_id);$i++)
		{
		   
		    DB::table('order_details')->where('order_details.id','=',$order_detail_id[$i])
				->update([
					'order_details.tcs_invoice_no'=>$rand,
					'order_details.tcs_invoice_date'=>date('Y-m-d H:i:s')
				]);
		}
	    
		
		return Redirect::route('vendor_order_tcs_invoice',[$request->vendor_id,base64_encode($rand)]);
    }
	
	public function vendor_order_tcs_print_invoice(Request $request)
    {

		$id= base64_decode($request->vendor_id);
		$tcs_invoice_no= base64_decode($request->tcs_invoice_no);
		
	 	
		/*$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.order_status'=>1,
					'order_details.order_detail_invoice_num'=>'PHT/BLR/'.$id,
					'order_details.order_detail_invoice_date'=>date('Y-m-d H:i:s')
				]);*/
		
		return Redirect::route('vendor_order_tcs_invoice',[$request->vendor_id,$request->tcs_invoice_no]);
    }
	
	public function vendor_order_tcs_invoice_list(Request $request)
    {
		$page_details=array(
		   "Title"=>"TCS Settlement Invoice List",
		   "Box_Title"=>"TCS Settlement Invoice List",
		   "export"=>""
		 );
	  
	  $id= base64_decode($request->vendor_id);
	  
	  $daterange= $request->daterange;
	  $search= $request->search_string;
	  
	  $Order =Orders::select(
								'orders.id',
								'orders.tax_percent',
								'orders.total_commission_rate',
								'orders.tcs_percentage',
								'orders.order_no',
								'products.vendor_id',
								'order_details.id as order_detail_id',
								'order_details.suborder_no as suborder_no',
								'order_details.order_date',
								'order_details.tcs_invoice_no',
								'order_details.tcs_invoice_date',
								 DB::raw("SUM(order_details.product_qty*order_details.product_price) AS order_amt"),
								 DB::raw("SUM(order_details.tcs_amt) AS tcs_order_amt"),
								'order_details.order_commission_rate',
								'order_details.product_tax',
								'order_details.order_detail_tax_amt',
								/*'order_details.tcs_percentage',	*/
								'order_details.tcs_amt'
							)
							
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->join('products', 'products.id', '=', 'order_details.product_id')
							->where('order_details.order_status',3)
							/*->where('order_details.tcs_invoice_no','!=',NULL)*/
							->whereNotNull('order_details.tcs_invoice_no')
							->where('products.vendor_id',$id)
							//->groupBy('order_details.order_id')
							;
							
		if($daterange!='')
		{
		    $daterange_array=explode('.',$daterange);
            $from= date("Y-m-d", strtotime($daterange_array[0]));
            $to=date("Y-m-d", strtotime($daterange_array[1]));
                    
            $Order->whereDate('order_details.tcs_invoice_date', '>=',$from);
            $Order->whereDate('order_details.tcs_invoice_date', '<=',$to);
                    
            //$Order=$Order->whereBetween('order_details.tcs_invoice_date',[$from,$to]);
    	}
		
		if($search!='')
		{
		   $Order =$Order->where('order_details.tcs_invoice_no',$search);
		}
		
		$Order =$Order->groupBy('order_details.tcs_invoice_no')
					  ->paginate(100);
							
						  /*$vdr_id=43;*/
						   $vdr_id=$id;
						   
                            $vdr=new Vendor();
                            //$vdr_id=$vdr->vendor_id($id,1);
                            
                            $vdr_data=$vdr->getVendorDetails($vdr_id);
							
						
							
			 return view('vendor.orders.tcs_generate_order_invoice_list',['orders'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data,'daterange'=>$daterange,'searchterm'=>$search]);	
    }
    
    public function vendor_order_tcs_invoice(Request $request)
    {
		$page_details=array(
		   "Title"=>"TCS Settlement Invoice",
		   "Box_Title"=>"TCS Settlement Invoice"
		 );
	  
	  $id= base64_decode($request->vendor_id);
	  $tcs_invoice_no= base64_decode($request->tcs_invoice_no);
	  
	  $Order =Orders::select(
								'orders.id',
								'orders.tax_percent',
								'orders.total_commission_rate',
								'orders.tcs_percentage',
								'orders.tds_percentage',

								'orders.order_no',
								'order_details.id as order_detail_id',
								'order_details.suborder_no as suborder_no',
								'order_details.order_date',
								'order_details.tcs_invoice_no',
								'order_details.tcs_invoice_date',
								 DB::raw("SUM(order_details.product_qty*order_details.product_price) AS order_amt"),
								 DB::raw("SUM(order_details.tcs_amt) AS tcs_order_amt"),
								 DB::raw("SUM(order_details.tds_amt) AS tds_order_amt"),
								'order_details.order_commission_rate',
								'order_details.product_tax',
								'order_details.order_detail_tax_amt',
								/*'order_details.tcs_percentage',	*/
								'order_details.tds_amt',
								'order_details.tcs_amt'
							)
							
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->join('products', 'products.id', '=', 'order_details.product_id')
							->where('order_details.order_status',3)
							/*->where('order_details.tcs_invoice_no','!=',NULL)
							->whereNotNull('order_details.tcs_invoice_no')*/
							->where('products.vendor_id',$id)
							->where('order_details.tcs_invoice_no',$tcs_invoice_no)
							->groupBy('order_details.order_id')
							->get()->toarray();
							
						  $vdr_id=43;
						  $vdr_id=$id;
						  
						   
                            $vdr=new Vendor();
                            //$vdr_id=$vdr->vendor_id($id,1);
                            
                            $vdr_data=$vdr->getVendorDetails($vdr_id);
							
							return view('vendor.orders.tcs_invoice_tax',['Order'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data]);	

			
			 
    }


	public function vendor_order_tcs_print_vendor_invoice(Request $request)
    {
		$page_details=array(
		   "Title"=>"Invoice",
		   "Box_Title"=>"Invoice"
		 );
	  
	  $id= base64_decode($request->vendor_id);
	  $tcs_invoice_no= base64_decode($request->tcs_invoice_no);
	  
	  $Order =Orders::select(
								'orders.id',
								'orders.tax_percent',
								'orders.total_commission_rate',
								'orders.tcs_percentage',
								'orders.tds_percentage',

								'orders.order_no',
								'order_details.id as order_detail_id',
								'order_details.suborder_no as suborder_no',
								'order_details.order_date',
								'order_details.tcs_invoice_no',
								'order_details.tcs_invoice_date',
								 DB::raw("SUM(order_details.product_qty*order_details.product_price) AS order_amt"),
								 DB::raw("SUM(order_details.tcs_amt) AS tcs_order_amt"),
								 DB::raw("SUM(order_details.tds_amt) AS tds_order_amt"),
								'order_details.order_commission_rate',
								'order_details.product_tax',
								'order_details.order_detail_tax_amt',
								/*'order_details.tcs_percentage',	*/
								'order_details.tds_amt',
								'order_details.tcs_amt'
							)
							
							->join('order_details', 'orders.id', '=', 'order_details.order_id')
							->join('products', 'products.id', '=', 'order_details.product_id')
							->where('order_details.order_status',3)
							/*->where('order_details.tcs_invoice_no','!=',NULL)
							->whereNotNull('order_details.tcs_invoice_no')*/
							->where('products.vendor_id',$id)
							->where('order_details.tcs_invoice_no',$tcs_invoice_no)
							->groupBy('order_details.order_id')
							->get()->toarray();
							
						  $vdr_id=43;
						  $vdr_id=$id;
						  
						   
                            $vdr=new Vendor();
                            //$vdr_id=$vdr->vendor_id($id,1);
                            
                            $vdr_data=$vdr->getVendorDetails($vdr_id);
							
							return view('vendor.orders.tcs_invoice_commission',['Order'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data]);	

			 }
							
    
	
   
}
