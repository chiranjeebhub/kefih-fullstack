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
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\City;
use App\ProductCategories;
use App\ProductImages;
use URL;
use Config;

class StateController extends Controller {
	public $successStatus = 200;
	public $site_base_path='http://aptechbangalore.com/test/';
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	
	public function index(){
		$input = json_decode(file_get_contents('php://input'), true);
		$state =DB::table('states')->where('country_id',101)->orderBy('name','ASC')->get();
		
		 if((sizeof($state))>0){
		        echo json_encode(
                 array(
                            "status"=>true,
							"statusCode"=>201,
                            "message"=>"State List",
                            "state_data"=>$state,
					 )
                 );
		    } 
        else{
		        echo json_encode(
                 array(
                            "status"=>false,
							"statusCode"=>401,
                            "message"=>"no state found",
                            "state_data"=>array(),
                     )
                 );
		    }
	}

 public function avl_cities(Request $request){
         try{
            $page=1;
             $this->validate($request, [
                    'page' => 'integer'
            ]);
               if($request->page){
                    $page=$request->page;
               }
               $name=$request->name;
                $per_page=100;
            
             $datas = City::where(function($query)use ($name){
                                                 if($name){
                                                      $query->where('cities.name','LIKE', '%' . $name . '%');
                                                 } 
                                                $query->where('cities.status',1);
                                                $query->where('cities.isdeleted',0);
                                                
                                            })
                                          ->orderBy('cities.name', 'asc')
		                                   ->select(
                                                'cities.id',
                                                'cities.name'
                                                )
		                                  ->paginate($per_page,
                                                    [],
                                                    'page',
		                                             $page
		                                             )
		                                   ->toarray();
       
           $next_page = (int) filter_var($datas['next_page_url'], FILTER_SANITIZE_NUMBER_INT);
		       
		     
          
                $data=array(
                "data"=>$datas['data'],
                "next_page"=>$next_page
               
                 );
                  return response()->json([
                            "status" => true,
                             'msg'=>'data loaded',
                             "data"=>$data
                           
                        ]);
         }
         catch(Exception $e){
                return response()->json([
                        'msg'=>trans('api.something_went_wrong'),
                        'status'=>false,
                        "data"=>null
                    ], 500);
        }
     }

    public function city(Request $request){
		$input = json_decode(file_get_contents('php://input'), true);
	    $stateid=$input['stateid'];
		$city =DB::table('cities')->where('state_id',$stateid)->where('isdeleted',0)->orderBy('name','ASC')->get();
		
		 if((sizeof($city))>0){
		        echo json_encode(
                 array(
                            "status"=>true,
							"statusCode"=>201,
                            "message"=>"City List",
                            "city_data"=>$city,
					 )
                 );
		    } 
        else{
		        echo json_encode(
                 array(
                            "status"=>false,
							"statusCode"=>401,
                            "message"=>"no state found",
                            "city_data"=>array(),
                     )
                 );
		    }
	}
}


?>