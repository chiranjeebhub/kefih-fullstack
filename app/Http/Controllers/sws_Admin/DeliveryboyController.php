<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\DeliveryBoy;
use App\Vendor;
use Redirect;
use Validator;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\deliveryboyExport;
use URL;
use Auth;
use Hash;
use App\Zone;
class DeliveryboyController extends Controller 
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
     public function assign(Request $request){

       $id =$request->orderid;

   if($request->deliveryid!=''){
       $is_exist=DB::table('order_details')->where('id','=',$id)->first();	
        $quey=DB::table('orders')->select('order_no','grand_total','customer_id','delivery_date')->where('id',$is_exist->order_id)->first();
        $dv=DB::table('tbl_delivery_boy')->select('phone','name')->where('id',$request->deliveryid)->first();
   	if($quey){
		$res=DB::table('order_details')->where('order_details.id','=',$id)
				->update([
					'order_details.deliveryID'=>$request->deliveryid
				]);
				
				$dateexp=explode("_",$quey->delivery_date);
		
		//var_dump($dateexp[1]);die;
$messageotp="Hi ".ucfirst($dv->name).", redliips has been assign a order order date(".$dateexp[1].") and order id (".$is_exist->suborder_no.") please login your account and check order";
 $email_data = ['phone'=>$dv->phone,'phone_msg'=>$messageotp,'otp'=>''];
           CommonHelper::SendMsg($email_data);
 
   	}
   	}

   }
     
    
    public function index(Request $request)
    {
		
		$parameters=$request->str;	
		
		if($parameters!=''){
			$export=route('exportProduct_with_Search',($request->str));
			} else{
			$export=route('exportdeliveryboy');
		}
		$backroute='';
		
		
		
	 	$page_details=array(
			"Title"=>"Delivery Boy",
			"Box_Title"=>"Delivery Boy",
            "search_route"=>URL::to('admin/filters_deliveryboy'),
            'back_route'=>route('deliveryboy'),
			"reset_route"=>route('deliveryboy'),
			 "export"=>$export
				
		);
		
        $list=DeliveryBoy::where('isDeleted',0)->orderBy('id', 'DESC')->paginate(20);
		//$zonelist=DeliveryBoy::orderBy('id', 'DESC')->paginate(20);
	
		$zonelist = Zone::orderBy('id', 'DESC')->get();
		if($parameters!=''){
				
				  $list =$list->Where(function($query) use ($parameters){
				 $query->orWhere('tbl_delivery_boy.name','regexp', $parameters );
				 $query->orWhere('tbl_delivery_boy.email','regexp',  $parameters);
				 $query->orWhere('tbl_delivery_boy.phone','regexp', $parameters);
						 });
		} 
		

		
		return view('admin.deliveryboy.vwdelivery',['list'=>$list,'page_details'=>$page_details,'zonelist'=>$zonelist]);
    }
    
   

	public function multi_delete(Request $request)
    {
		$input=$request->all();
		if($input['status']==1){
			Deliveryboy::whereIn('id', $input['id'])
				->update([
					'status' =>1
				]);
		}
		if($input['status']==2){
			Deliveryboy::whereIn('id', $input['id'])
				->update([
					'status' =>0
				]);
		}
		if($input['status']==3){
		/*deliveryboy::whereIn('id', $input['id'])
				->update([
					'isdeleted' =>1
				]);	*/
				
		Deliveryboy::whereIn('id', $input['id'])->delete();
                    
		}
		
				
				
				
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		
		  return redirect()->route('deliveryboy');
	
    }
	 public function add(Request $request){
    	$page_details=array(
    	"Title"=>"Add Delivery Boy",
		"Method"=>"1",
		"Box_Title"=>"Add Delivery Boy",
		"backurl"=>route("deliveryboy"),
		"Action_route"=>route('add'),
		);
		
		
		if ($request->isMethod('post')) {
		
		     $input=$request->all();
			 $request->validate([
                'name' => 'required',
                ],
                [
            'name.required'=>'deliveryboy is required',
    
               ]
                
                
                );
		$checkIsAlraedy =DeliveryBoy::where(['email'=>trim($input['email']),'phone'=>trim($input['phone']),'isDeleted'=>0])->first();
		if(sizeof($checkIsAlraedy)==0){
			$id = DeliveryBoy::insertGetId($request->all());
			$password = Hash::make(trim($input['password']));
 DeliveryBoy::whereId($id)->update(['password'=>$password]);
		
			   $folder_path = '/uploads/profile/';
                 $image = '';
                 if($request->hasFile('image')) {
                   
                     $photo = $request->file('image');
                   if($photo){
                     $image = time().'-'.$photo->getClientOriginalName(); 
                     $destinationPath = public_path($folder_path);
                     $photo->move($destinationPath, $image);
                   }
		    
		   $data=[
		'image' => $image
		];	 
		    
		$res=DeliveryBoy::where('id', '=',$id)
                ->update(
               $data
                );    
	  }
		 
	return redirect()->route('deliveryboy')->with('success', 'Successfully Added');
		}else{
		 return redirect()->route('add')->with('danger', 'Data alraedy exits!');	
		}
 
 
      
	}
        
        else{
            $zonelist = Zone::orderBy('id', 'DESC')->get();
             $states = CommonHelper::getState('101');
		return view('admin.deliveryboy.vwdelivery',['page_details'=>$page_details,'zonelist'=>$zonelist,'states'=>$states,"cities"=>array()]);	
		}
	 }
 


public function edit(Request $request)
   {
	$id=$request->id;
	$row = deliveryboy::where('id', $id)->first();		
		
 $page_details=array(
       "Title"=>"Edit Delivery Boy",
		"Method"=>"1",
		"Box_Title"=>"Edit Delivery Boy",
		"backurl"=>route("deliveryboy"),
       "Action_route"=>route('edit_deliveryboy', [$id]),
     );
	
	  if ($request->isMethod('post')) {
		        $input=$request->all();
			 $request->validate([
               
                'name' => 'required',
                ],
                [ 
            'name.required'=>'deliveryboy is required',
    
               ]
                
                
                );
		 Deliveryboy::whereId($id)->update($request->all());
	$password = Hash::make(trim($input['password']));
	
			if($input['password']==''){
				DeliveryBoy::where('id',$id)->update(['password'=>$row->password]);
			}
			else{
				DeliveryBoy::where('id',$id)->update(['password'=>$password]);
			}
	

		
			   $folder_path = '/uploads/profile/';
                 $image = '';
                 if($request->hasFile('image')) {
                   
                     $photo = $request->file('image');
                   if($photo){
                     $image = time().'-'.$photo->getClientOriginalName(); 
                     $destinationPath = public_path($folder_path);
                     $photo->move($destinationPath, $image);
                   }
		    
		   $data=[
		'image' => $image
		];	 
		    
		$res=DeliveryBoy::where('id', '=',$id)
                ->update(
               $data
                );    
	  }
return Redirect::route('deliveryboy')->with('success', 'Successfully updated');	

		 
	  }
       
     else{
           $zonelist = Zone::orderBy('id', 'DESC')->get();
           $states = CommonHelper::getState('101');
             $cities = DB::table('cities')->select('cities.name as name', 'states.name as state_name')
             ->join('states', 'states.id', '=', 'cities.state_id')
             ->where('states.name', $row->state)
             ->get();
		return view('admin.deliveryboy.vwdelivery',['page_details'=>$page_details,'zonelist'=>$zonelist,'states'=>$states,'row'=>$row,"cities"=>$cities]);	
	 	
	 }
	}


	
	 public function delete(Request $request)
    {
            $id=base64_decode($request->id);

            //$res=Deliveryboy::where('id',$id)
              //      ->update(['isdeleted' => 1]);
             $res=Deliveryboy::where('id',$id)
                    ->delete();       
                    
            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }
	
	
    public function status(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Deliveryboy::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

          
            return Redirect::back()->with('success', 'Successfully Changed');
    }

  public function searchdata(Request $request)
    {
		
		
		$parameters=$request->search_string;
		$category_id =$request->category_id;
          
	
		
		if($parameters!=''){
			$export=route('exportdeliveryboy',($request->str));
			} else{
			$export=route('exportdeliveryboy');
		}
		
			
		
	 	$page_details=array(
			"Title"=>"deliveryboy List",
			"Box_Title"=>"deliveryboy (s)",
		     "search_route"=>URL::to('admin/filters_deliveryboy'),
			"reset_route"=>route('deliveryboy'),
			'back_route'=>route('deliveryboy'),
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
		
        //$Products= Products::select('products.*')->where('products.isdeleted', 0);
        $Products= deliveryboy::select('tbl_delivery_boy.*');
		
        if( ($category_id!='All' && $category_id!='')  || ($parameters!='All' && $parameters!='')){
		   
		   
				 
				  $Products =$Products
						
						->join('categories','categories.id','=','tbl_delivery_boy.catid');
		} 
		
	
	
       
		if(@$sts!='' && @$sts!='All'){
				$Products=$Products->where('tbl_delivery_boy.status','=',$sts);
		} 
	
		
	
		
		
		if($parameters!='All' && $parameters!=''){
		       $Products =$Products
						   ->Where(function($query) use ($parameters){
							 $query->orWhere('tbl_delivery_boy.name','LIKE', '%' . trim($parameters) . '%');
							 //$query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
							 $query->orWhere('categories.name','LIKE', '%' . trim($parameters) . '%');
						 });
		} 
		if($category_id!='All' && $category_id!=''){
		  	$Products =$Products->where('tbl_delivery_boy.catid',$category_id);
		} 
		
						
		$products=$Products->where('tbl_delivery_boy.isdeleted', 0)->groupBy('tbl_delivery_boy.id')->orderBy('tbl_delivery_boy.id', 'DESC')->paginate(100);
		//echo '<pre>';print_r($products);die;
		return view('admin.product.deliveryboylist',['list'=>$products,'page_details'=>$page_details]);
    }
	

	

	public function exportdata(Request $request)
    {
		$str=$request->str;
	return Excel::download(new deliveryboyExport($str), 'deliveryboy'.date('d-m-Y H:i:s').'.csv');	
    }

	
	 public function bulk_upload(Request $request)
    {
		$inputs=$request->all();
		
		 $request->validate([
                'csv' => 'required'
                
            ],[
			  'csv.mimes' => 'File Type should be CSV Format',
			]
			);
			
			
			$file = $request->file('csv');
			
			
			$file_name=$file->getClientOriginalName();
			$res = FIleUploadingHelper::UploadDoc(Config::get('constants.uploads.csv'),$file,$file_name);
			
			$filepath = Config::get('constants.Url.public_url').'/'.Config::get('constants.uploads.csv').'/'.$res;
			
			$file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
             
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
          fclose($file);
           
		   
		  $j=0;
		  $upload_error=array();
		 
				foreach($importData_arr as $importData){
					
					if($j>0){
					    		         
	$res=deliveryboy::select('id')->where('isdeleted',0)->where(['catid'=>$importData[0],'name'=>$importData[1]])->first();
							
									if(!$res){
					                  
			$checkcat =Category::where(['isdeleted'=>0,'id'=>$importData[0]])->toSql();						  
									
									   if(sizeof($checkcat)!='0')
									   {
											$list = new deliveryboy();  
											$list->catid= $importData[0];  
											$list->name= $importData[1];  
											$list->isdeleted= 0;
											$list->status= 1;
											
									if($list->save()){
									  $lastId = $list->id;
                                       
									// array_push($upload_error,array('row'=>$j,'success_message'=>'Data successfully import')); 
									}
									   }else{
										   
						
					array_push($upload_error,array('row'=>$j,'error_message'=>'Category Not Exist'));
										   continue;
									   }
									  
							} else{
							    array_push($upload_error,array('row'=>$j,'error_message'=>'Already exists')); 
							
									
									
								}  
								
			
				}
				$j++;
    }
				if(count($upload_error)>0)
				{
					MsgHelper::error_session_message('danger',$upload_error,$request);
				}else{
				//	Session::flash('success', 'Data successfully import!'); 
					return Redirect::back()->with('success', 'Data successfully import!');
					//MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				}
				 
				 
				 return Redirect::back();
				 
    }
}
