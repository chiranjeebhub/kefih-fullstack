<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Customer; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use App\Notification;
use App\Products;
use URL;
use Config;

class NotificationController extends Controller 
{
	public $successStatus = 200;
	
	public $site_base_path='https://phaukat.com/';
	
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
    }
    public function test(Request $request){
     
            $page_no=$request->page;
            
            $prd_img='https://www.phaukat.com/uploads/products/';
           
            $per_page=20;
         $myschedule = Products:: where(function($query) {
                                              $query->where('products.status', '=',1);
                                             $query->where('products.isdeleted', '=',0);
                                           
                                           
                                     })
                                            ->orderBy('products.id', 'desc')
		                                   ->select(
                                                'products.id as styleid',
                                                'products.name as product_additional_info',
                                                 DB::raw("'phaukat' as brands_filter_facet"),
                                                'products.price as price',
                                                DB::raw("CONCAT('$prd_img',default_image) AS search_image")
                                               
                                                )
                                        ->paginate($per_page,
                                                            [],
                                                            'page',
        		                                             $page_no
        		                                             )
        		                            ->toarray();
                                    
                 
                      $next_page = (int) filter_var($myschedule['next_page_url'], FILTER_SANITIZE_NUMBER_INT);
                      $last_page_url = (int) filter_var($myschedule['last_page_url'], FILTER_SANITIZE_NUMBER_INT);
          
                $notifications_data=array(
                    "products"=>$myschedule['data'],
                    "next_page"=>$next_page,
                    "last_page"=>$last_page_url
               
                 );
                 
                                
                 
                    return response()->json([
                        'msg'=>"All requests listed",
                        'status'=>true,
                        "data"=>$notifications_data
                    ]);
    }
    public function getNotification(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
		
		$user_id=(int)$input['fld_user_id'];
		$page_no=(((int)$input['fld_page'])==0?1:(int)$input['fld_page']);
		
		$notification_data = Notification::where(function($query) use ($user_id){
											    $query->orwhere('user_id', '=', $user_id);
											    $query->orwhere('user_id', '=', null);
											    $query->whereDate('notfication_data', '=', date('Y-m-d'));
										     })
		                                   ->orderBy('id', 'desc')
		                                   ->select(
                                                'tbl_notification.title as fld_notification_title',
                                                'tbl_notification.body as fld_notification_body',
                                                'tbl_notification.payload_url as fld_notification_url',
                                                'tbl_notification.payload_type as fld_notification_type',
                                                 DB::raw("'0' as fld_notification_readed"),
                                                  DB::raw("'https://cdn.pixabay.com/photo/2015/04/19/08/32/marguerite-729510__340.jpg' as fld_notification_image")
                                                )
		                                  ->paginate(10,
                                                    [],
                                                    'page',
		                                             $page_no
		                                             )
		                                   ->toarray();
		      
		$res=array(
            "status"=>false,
            "statusCode"=>404,
            "message"=>"Something went wrong",
            "notification_data"=>array(),
            "total_page"=>0,
		    );
		    
		if(sizeof($notification_data['data'])>0){
		    $res=array(
            "status"=>true,
            "statusCode"=>201,
            "message"=>"Notification listed",
            "notification_data"=>$notification_data['data'],
            "total_page"=>$notification_data['last_page'],
		    );
		} else{
		     $res=array(
            "status"=>true,
            "statusCode"=>201,
            "message"=>"No Notification found",
            "notification_data"=>array(),
            "total_page"=>0,
		    );
		} 
		
		echo json_encode($res);
		
    }
	
	

}