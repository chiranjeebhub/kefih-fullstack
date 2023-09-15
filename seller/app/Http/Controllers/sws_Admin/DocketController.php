<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Docket;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\XpressbeesHelper;
use Redirect;
use Validator;
use URL;
use DB;
use Config;
use App\Exports\DocketExport;
use App\Exports\DocExport;
use Maatwebsite\Excel\Facades\Excel;
class DocketController extends Controller
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
     
    public function docket(Request $request)
    {
        
        $page=0;
      $full_url= url()->full();

        if (strpos($full_url, 'page') !== false) {
            $url_params=explode('?',$full_url);
         if(sizeof($url_params)>1){
             $page_params=@$url_params[1];
             $page_params_array=explode('=',@$url_params[1]);
             $page=@$page_params_array[1];
         }
        }
        
    	$page_details=array(
           "Title"=>"Docket Generation",
           "Box_Title"=>"Docket Generation",
           "search_route"=>URL::to('admin/filters_docket'),
           "export"=>URL::to('admin/exportDocket')."?page=".$page,
    	    "base_route"=>'',
    		"reset_route"=>''
         );
	
		$dockets=Docket::paginate(100);
		
		return view('admin.docket.listing',['dockets'=>$dockets,'page_details'=>$page_details]);
    }
    
    public function filters_docket(Request $request){
         $full_url= url()->full();
          $page=0;

        if (strpos($full_url, 'page') !== false) {
            $url_params=explode('?',$full_url);
         if(sizeof($url_params)>1){
             $page_params=@$url_params[1];
             $page_params_array=explode('=',@$url_params[1]);
             $page=@$page_params_array[1];
         }
        }
        
        
       
         
        
		$sts = $request->sts;	
        $dtsts = $request->dtsts;	
	    $docket_no = $request->docket_no;
    	$export_serach_docket_url = 'admin/exportDocket_with_Search/'.$sts.'/'.$dtsts.'/'.$docket_no."?page=".$page;
    	
    	 $page_details=array(
               "Title"=>"Docket Generation",
               "Box_Title"=>"Docket Generation",
               "search_route"=>URL::to('admin/filters_docket'),
               "base_route"=>URL::to('admin/docket'),
               "export"=>URL::to($export_serach_docket_url),
               "sts"=>$sts,
               "dtsts"=>$dtsts,
               "docket_no"=>$docket_no,
        		"reset_route"=>''
             );
       
         $wherecondition = array();
         if($sts != 'All'){
             
             $wherecondition['status'] = $sts;
            
         }
         
          if($dtsts != 'All'){
            
              $wherecondition['docket_type'] = $dtsts;
         }
         
          if($docket_no != 'All'){
              $wherecondition['docket_number']=$docket_no;
         }
      
         $dockets=Docket::where($wherecondition)->paginate(100);
		 return view('admin.docket.listing',['dockets'=>$dockets,'page_details'=>$page_details]);
    }
    
    
    public function adddocket(Request $request)
    {
        $page_details=array(
            "Title"=>"Add Docket",
	        "Method"=>"1",
            "Box_Title"=>"Add Docket",
            "Action_route"=>route('adddocket'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                // 'label'=>'Name',
                // 'type'=>'text',
                // 'name'=>'text',
                // 'id'=>'text',
                // 'classes'=>'form-control',
                // 'placeholder'=>'Text',
                // 'value'=>old('text'),
                // 'disabled'=>''
                
                            'label'=>'Name *',
							'type'=>'select',
							'name'=>'text',
							'id'=>'text',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"COD",
							"name"=>"COD",
							),(object)array(
							    "id"=>"Online",
							"name"=>"Online",
							)
							),
							'disabled'=>'',
							'selected'=>old('text')
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
                      'text' => 'max:50',
                    ]
        			);
                   
                $inputs['delivery_type']=$input['text'];
		        $ss=XpressbeesHelper::AWBRequest($inputs);
		        $docket_batch=$ss->BatchID;
		        $docket_list=$ss->AWBNoSeries;
		        
		        for($i=0; $i<count($docket_list);$i++)
		        {
		            $Docket = new Docket;
		            $Docket->docket_type = $input['text'];
                    $Docket->docket_batch_id =$docket_batch;
                    $Docket->docket_number =$docket_list[$i];
		            
                      /* save the following details */
                      if($Docket->save()){
                		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
                      } else{
                		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
                      }
                }
              return Redirect::back();
        }
    
        return view('admin.docket.form',['page_details'=>$page_details]);
    }
    
    public function exportDocket(Request $request){
      $type = '0';
     $page=0;
      $full_url= url()->full();

        if (strpos($full_url, 'page') !== false) {
            $url_params=explode('?',$full_url);
         if(sizeof($url_params)>1){
             $page_params=@$url_params[1];
             $page_params_array=explode('=',@$url_params[1]);
             $page=@$page_params_array[1];
         }
        }
     return Excel::download(new DocketExport($type,'','','',$page), 'Dockets'.date('d-m-Y H:i:s').'.csv');
	
    }
    
    public function exportDocket_with_Search(Request $request){
        $type = '1';
		$sts = $request->sts;	
        $dtsts = $request->dtsts;	
	    $docket_no = $request->docket_no;
	    $page=0;
	     $full_url= url()->full();

        if (strpos($full_url, 'page') !== false) {
            $url_params=explode('?',$full_url);
         if(sizeof($url_params)>1){
             $page_params=@$url_params[1];
             $page_params_array=explode('=',@$url_params[1]);
             $page=@$page_params_array[1];
         }
        }
    	return Excel::download(new DocketExport($type, $sts, $dtsts, $docket_no,$page), 'Dockets'.date('d-m-Y H:i:s').'.csv');
    }
   

}
