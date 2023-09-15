<?php

namespace App\Http\Controllers\sws_Admin;

use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Illuminate\Http\Request;
use App\Orders;
use App\OrdersDetail;
use App\Payments;
use App\Vendor;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use App\Exports\SubOrderExport;
use App\Exports\LedgerExport;
use App\Exports\LedgerVendorExport;
use App\Exports\VendorPaymentExport;
use URL;

class LedgerController extends Controller
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
	 public function ledger_export(Request $request)
    {
		$str=$request->str;
		$daterange=$request->daterange;
		return Excel::download(new LedgerExport($str,$daterange), 'Ledger'.date('d-m-Y H:i:s').'.csv');	
    }
    
    public function index(Request $request)
    {
		$parameters=$request->search_string;	
		if($parameters!=''){
			$export=URL::to('admin/filter_ledger_export').'/'.$request->search_string.'/'.$request->daterange;
			} else{
				$export=URL::to('admin/ledger_export');
			
		}
		$str=$request->search_string;
		$daterange=$request->daterange;
	
		$page_details=array(
			   "Title"=>"Ledger",
			   "Box_Title"=>"List",
			   "search_route"=>route('ledger_search'),
			   "export"=>$export,
				"reset_route"=>route('ledger')
				 );
				 	
	 
		$Vendors =OrdersDetail::select(
							'vendors.public_name',
							'vendors.id',
							'vendor_tax_info.gst_no',
							DB::raw('SUM(round((order_details.product_price*order_details.product_qty)*order_details.order_commission_rate)/100) AS total_commission_amt'),
							DB::raw("SUM(order_details.tcs_amt) AS tcs_amt"),
							DB::raw("SUM(order_details.order_vendor_shipping_charges) AS total_order_shipping_charges"),
							DB::raw("SUM(order_details.tds_amt) AS tds_amt"),							
							DB::raw('SUM(order_details.product_price*order_details.product_qty) AS total_product_amt'),
							DB::raw('SUM(order_details.order_detail_invoice_type) AS order_detail_invoice_type'),
							DB::raw('SUM(order_details.order_detail_tax_amt) AS order_detail_tax_amt')
						)
					->join('products', 'products.id', '=', 'order_details.product_id')
					//->join('product_categories', 'product_categories.product_id', '=', 'order_details.product_id')
					//->join('categories', 'categories.id', '=', 'product_categories.cat_id')
					->join('vendors', 'vendors.id', '=', 'products.vendor_id')
					->rightJoin('vendor_tax_info', function($join) {
					  $join->on('products.vendor_id', '=', 'vendor_tax_info.vendor_id');
					})
					->where('order_details.order_status', '=', '3');
				
				if(Auth::guard('vendor')->check()){
					$vdr_id=auth()->guard('vendor')->user()->id;
					$Vendors=$Vendors->where('vendors.id', '=', $vdr_id);
				}
					
				if($str!='' && $str!='All'){
					  $Vendors=$Vendors
							->Where(function($query) use ($str){
								 $query->orwhere('vendors.public_name','LIKE',$str.'%');
								 $query->orWhere('vendors.email','LIKE',$str.'%');
								 $query->orWhere('vendors.phone','LIKE',$str.'%');
								 $query->orWhere('vendors.username','LIKE',$str.'%');
								 $query->orWhere('vendors.id','LIKE',$str.'%');
							 });
				} 
				
				if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('/',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
							 
							  $Vendors=$Vendors
							 ->whereBetween('order_details.order_date',[$from,$to]);
                }
					
		$Vendors=$Vendors->groupBy('products.vendor_id')->orderBy('vendors.id','desc')->paginate(100); 
		// dd($Vendors);

	  return view('admin.ledger.list',['ledgers'=>$Vendors,'str'=>$str,'daterange'=>$request->daterange,'page_details'=>$page_details]);
    }
	
	public static function tax_type($vendor_id,$tax_type,$str='',$daterange='')
    {
		
		$Vendors =OrdersDetail::select(
							DB::raw('SUM(order_details.order_detail_tax_amt) AS order_detail_tax_amt')
						)
					->join('products', 'products.id', '=', 'order_details.product_id')
					//->join('product_categories', 'product_categories.product_id', '=', 'order_details.product_id')
					//->join('categories', 'categories.id', '=', 'product_categories.cat_id')
					->join('vendors', 'vendors.id', '=', 'products.vendor_id')
					->where('products.vendor_id', '=', $vendor_id)
					->where('order_details.order_detail_invoice_type', '=', $tax_type)
					->where('order_details.order_status', '=', '1');
					
				if($str!=''){
					  $Vendors=$Vendors
							->Where(function($query) use ($str){
								 $query->orwhere('vendors.public_name','LIKE',$str.'%');
								 $query->orWhere('vendors.email','LIKE',$str.'%');
								 $query->orWhere('vendors.phone','LIKE',$str.'%');
								 $query->orWhere('vendors.username','LIKE',$str.'%');
							 });
				} 
				
				if($daterange!=''){
					$daterange=explode('--',$daterange);
					$daterange[0] = explode("-", $daterange[0]);
					$from = $daterange[0][2] . "-" . $daterange[0][1] . "-" . $daterange[0][0]; 

					$daterange[1] = explode("-", $daterange[1]);
					$to = $daterange[1][2] . "-" . $daterange[1][1] . "-" . $daterange[1][0];
					
					  $Vendors=$Vendors
							 ->whereBetween('order_details.order_date',[$from,$to]);
				} 
					
		$Vendors=$Vendors->groupBy('products.vendor_id')->orderBy('vendors.id','desc')->first(); 
		
		return $Vendors;
    }
	
	public function ledger_vendor_export(Request $request)
    {
		$vendor_id=$request->vendor_id;
		$date_range=$request->date_range;
		return Excel::download(new LedgerVendorExport($vendor_id,$date_range), 'vendor_ledger'.date('d-m-Y H:i:s').'.csv');	
    }
	public function ledger_detail(Request $request)
    {
		
	
		 $daterange=$request->date_range;	
	
		 
				$export=URL::to('admin/ledger_vendor_export')."/".$request->vendor_id;
				if($daterange!='All' && $daterange!=''){
				   	$export=URL::to('admin/ledger_vendor_export_search')."/".$request->vendor_id.'/'.$daterange;
                }
	
		
		$page_details=array(
				   "Title"=>"Ledger Detail",
				   "Box_Title"=>"Detail",
				   "search_route"=>URL::to('admin/ledger_detail_serach')."/".$request->vendor_id,
				   "export"=>$export,
					"reset_route"=>URL::to('admin/ledger_detail')."/".$request->vendor_id,
				 );
		
	
		$vendor_id=base64_decode($request->vendor_id);	
		
		$Vendors =OrdersDetail::select(
							'vendors.id',
							'vendors.public_name',
							'vendors.email',
							'vendors.phone',
							'products.sku',
							'order_details.product_name',
							'order_details.product_qty',
							'order_details.product_price',
							'order_details.size',
							'order_details.suborder_no as subID',
							'orders.order_no as masterID',
							'order_details.color',
							'order_details.order_commission_rate',
							'order_details.order_detail_invoice_type',
							'order_details.order_detail_tax_amt',
							'order_details.order_date',
							'order_details.tcs_amt',
							'order_details.tds_amt',
							'order_details.order_vendor_shipping_charges',
							'order_details.reverse_order_shipping_charge',
							'order_details.order_status'
						)
						->join('orders', 'order_details.order_id', '=', 'orders.id')

					->join('products', 'products.id', '=', 'order_details.product_id')
					->join('vendors', 'vendors.id', '=', 'products.vendor_id')
					->where('vendors.id','=',$vendor_id)
					->where('products.vendor_id','=',$vendor_id)
				->whereIn('order_details.order_status', ['3', '5', '6']);
					if($daterange!='All' && $daterange!=''){
				     $export=URL::to('admin/customers_pay_export_dt').'/'.$daterange;
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $Vendors=$Vendors
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
					
		$Vendors=$Vendors->orderBy('order_details.id','desc')->paginate(100); 
		$vendor_info=Vendor::where('id',$vendor_id)->first();
		
	  return view('admin.ledger.detail',['ledger_details'=>$Vendors,'daterange'=>$daterange,'page_details'=>$page_details,'vendor_info'=>$vendor_info]);
    }
	
	public function vendor_pay(Request $request)
    {
		$vendor_id=base64_decode($request->vendor_id);	
		
		$page_details=array(
				   "Title"=>"Payment",
				   "Box_Title"=>"Add Payment",
				   "Action_route"=>route('vendor_pay',$request->vendor_id),
				   "Form_data"=>array(

					 "Form_field"=>array(
					   
					   "text_field"=>array(
							'label'=>'Payment Mode',
							'type'=>'text',
							'name'=>'payment_mode',
							'id'=>'payment_mode',
							'classes'=>'form-control',
							'placeholder'=>'Payment Mode',
							'value'=>'',
							'disabled'=>''
						   ),
						"receipt_field"=>array(
							'label'=>'Transaction ID',
							'type'=>'text',
							'name'=>'receipt_no',
							'id'=>'receipt_no',
							'classes'=>'form-control',
							'placeholder'=>'Enter Transaction ID',
							'value'=>'',
							'disabled'=>''
						   ),
						"amt_field"=>array(
							'label'=>'Amount',
							'type'=>'text',
							'name'=>'amt',
							'id'=>'amt',
							'classes'=>'form-control',
							'placeholder'=>'Amount',
							'value'=>'',
							'disabled'=>''
						   ),
						"date_field"=>array(
							'label'=>'Payment date',
							'type'=>'text',
							'name'=>'pay_date',
							'id'=>'pay_date',
							'classes'=>'form-control',
							'placeholder'=>'Date',
							'value'=>'',
							'disabled'=>''
						   ),
						"narration_field"=>array(
							'label'=>'Narration',
							'type'=>'text',
							'name'=>'narration',
							'id'=>'narration',
							'classes'=>'form-control',
							'placeholder'=>'Narration',
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
						)
					 )
				   )
				 );
		
		
			if ($request->isMethod('post')) {
				$input=$request->all();
				  
					  $request->validate([
						  'payment_mode' => 'required|max:255',
						  'input_type' => 'max:60000'
						],
						[
							'payment_mode.required'=> Config::get('messages.payment.error_msg.name_required'),
							'payment_mode.max'=>Config::get('messages.payment.error_msg.name_max'),
						]
						);
				   
				  $Payments = new Payments;
				  $Payments->vendor_id = $vendor_id;
				  $Payments->vendor_payment_mode = $input['payment_mode'];
				  $Payments->vendor_payment_receipt = $input['receipt_no'];
				  $Payments->vendor_payment_amt = $input['amt'];
				  $Payments->vendor_payment_date = $input['pay_date'];
				  $Payments->payment_date = date('Y-m-d',strtotime($input['pay_date']));
				  $Payments->vendor_payment_narration = $input['narration'];
				  
				  /* save the following details */
				  if($Payments->save()){
					  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				  } else{
					   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
				  }
				  return Redirect::back();
			}
			
			$Vendors =DB::table('vendors')
					->select(
							'vendors.id',
							'vendors.public_name',
							'vendors.email',
							'vendors.phone'
						)
					->where('vendors.id',$vendor_id)->first();
		
		
	  return view('admin.ledger.pay_form',['page_details'=>$page_details,'vendor_info'=>$Vendors]);
	  
    }
	
	public function vendor_payment_export(Request $request)
    {
            $vendor_id=$request->vendor_id;
            $str=$request->str;
            $daterange=$request->daterange;
		return Excel::download(new VendorPaymentExport($vendor_id,$str,$daterange), 'Vendor_Payment'.date('d-m-Y H:i:s').'.csv');	
    }
	public function vendor_payment_history(Request $request)
    {
			$str=$request->str;

			if($str == ''){
				$str = 'All';
			}
		$daterange=$request->daterange;	
			$export=URL::to('admin/vendor_payment_export')."/".$request->vendor_id;
			
			if(($daterange!='All' && $daterange!='') || ($str!='All' && $str!='')){
			    	$export=URL::to('admin/vendor_payment_search_export')."/".$request->vendor_id.'/'.$str.'/'.$daterange;
			}
		$page_details=array(
				   "Title"=>"Vendor Payment History",
				   "Box_Title"=>"Payment History Detail",
				   "search_route"=>URL::to('admin/vendor_payment_search')."/".$request->vendor_id,
				  "export"=>$export,
					"reset_route"=>URL::to('admin/vendor_payment')."/".$request->vendor_id,
				 );
		
		
		$vendor_id=base64_decode($request->vendor_id);	
		
		$Payments =DB::table('tbl_vendor_payment')
					->select(
							'vendors.id',
							'vendors.public_name',
							'vendors.email',
							'vendors.phone',
							'tbl_vendor_payment.vendor_payment_mode',
							'tbl_vendor_payment.vendor_payment_receipt',
							'tbl_vendor_payment.vendor_payment_amt',
							'tbl_vendor_payment.vendor_payment_date',
							'tbl_vendor_payment.vendor_payment_narration'
						)
					->join('vendors', 'vendors.id', '=', 'tbl_vendor_payment.vendor_id')
					->where('tbl_vendor_payment.vendor_id',$vendor_id);
					// dd($str);
				if($str!='' && $str!='All'){
					  $Payments=$Payments
							->Where(function($query) use ($str){
								 $query->orwhere('tbl_vendor_payment.vendor_payment_mode','LIKE',$str.'%');
								 $query->orwhere('tbl_vendor_payment.vendor_payment_receipt','LIKE',$str.'%'); 
							 });
				} 
				if($daterange!='All' && $daterange!=''){
			
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));                
						// $from=DB::raw('STR_TO_DATE("'.$from.'", "%d-%m-%Y")');
						// $to=DB::raw('STR_TO_DATE("'.$to.'", "%d-%m-%Y")');
					
					$Payments=$Payments
							 ->whereBetween('tbl_vendor_payment.payment_date',[$from,$to]);
							//  ->whereBetween(DB::raw('STR_TO_DATE(tbl_vendor_payment.vendor_payment_date, "%Y-%m-%d")'),[$from,$to]);
                }
				
			
					
		$Payments=$Payments->orderBy('tbl_vendor_payment.fld_vendor_payment_id','desc')->paginate(100);
			$vendor_info=Vendor::where('id',$vendor_id)->first();
	  return view('admin.ledger.payment_history',['payment_history'=>$Payments,'str'=>$str,'daterange'=>$request->daterange,'page_details'=>$page_details,'vendor_info'=>$vendor_info]);
    }
	
   
}
