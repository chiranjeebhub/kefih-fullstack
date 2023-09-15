<?php



namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;

use App\City;

use App\Helpers\CustomFormHelper;

use App\Helpers\MsgHelper;


use Redirect;

use Validator;

use URL;

use DB;

use Config;

use App\Helpers\CommonHelper;



class CityController extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('auth' or 'auth:userole');

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Contracts\Support\Renderable

     */

     

    

    public function lists(Request $request)

    {

		$parameters=$request->str;

	    	$page_details=array(

            "Title"=>"City",

            "Box_Title"=>"City(s)",

            "search_route"=>URL::to('admin/city_search'),

            "reset_route"=>route('city'),

 

     );

	 

	 

	    $City= City::select('cities.*')->join('states', 'cities.state_id', '=', 'states.id')->where('cities.isdeleted', 0);

	 

		if($parameters!=''){

		  $City=$City

				->where('cities.name','LIKE',$parameters.'%');

		} 

		

		$City=$City->where('states.country_id','101')->orderBy('cities.name', 'ASC')->groupBy('cities.id')->paginate(100);

		

        return view('admin.city.list',['City'=>$City,'page_details'=>$page_details,'parameters'=>$parameters]);

    }



   public function add(Request $request){

     $page_details=array(

       "Title"=>"Add City",

	     "Method"=>"1",

       "Box_Title"=>"Add City",

       "Action_route"=>route('addcity'),

       "Form_data"=>array(

         "Form_field"=>array(

           "text_field"=>array(

                'label'=>'City Name*',

                'type'=>'text',

                'name'=>'name',

                'id'=>'city_name',

                'classes'=>'form-control onlyalphanumspace',

                'placeholder'=>'Name',

                'value'=>'',

    			'disabled'=>''

           ),

          

            "state_field"=>array(

                                'label'=>'State*',

                                'type'=>'selectcustom',

                                'name'=>'state',

                                'id'=>'selectState',

                                'classes'=>'form-control',

                                'placeholder'=>'Select State',

                                'value'=>CommonHelper::getState('101'),

                    			'disabled'=>'',

                    			'selected'=>''

                               ),

          

                                

            

             "submit_button_field"=>array(

              'label'=>'',

              'type'=>'submit',

              'name'=>'submit',

              'id'=>'submit',

              'classes'=>'btn btn-danger createbtn',

              'placeholder'=>'',

              'value'=>'Save'

            )

         )

       )

     );



if ($request->isMethod('post')) {

    $input=$request->all();

      

          $request->validate([

              'name' => 'required|unique:cities,name,1,isdeleted|max:255',

              'input_type' => 'max:60000',

              'state' => 'required',

     ],

			[

				'name.required'=> Config::get('messages.city.error_msg.name_required'),

				'name.unique'=>Config::get('messages.city.error_msg.name_unique'),

				'name.max'=>Config::get('messages.city.error_msg.name_max'),

				'state.required'=>Config::get('messages.city.error_msg.state_required'),

			]

			);

        

        $insert_data['name'] = $input['name'];

        $insert_data['state_id'] = $input['state'];

        

       $data=City::insert($insert_data);

          MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);

          return Redirect::back();

}

    

    return view('admin.city.form',['page_details'=>$page_details]);

   }





   public function edit(Request $request)

   {

	   

	    $id=base64_decode($request->id);

		$detail_info = City::where('id', $id)->first();

	   $page_details=array(

       "Title"=>"Edit City",

	    "Method"=>"2",

       "Box_Title"=>"Edit City",

        "Action_route"=>route('editcity', base64_encode($id)),

       "Form_data"=>array(



         "Form_field"=>array(

           

           "text_field"=>array(

              'label'=>'City Name*',

            'type'=>'text',

            'name'=>'name',

            'id'=>'city_name',

            'classes'=>'form-control onlyalphanumspace',

            'placeholder'=>'Name',

            'value'=>$detail_info['name'],

				'disabled'=>''

           ),

        

          

            "state_field"=>array(

                                'label'=>'State*',

                                'type'=>'selectcustom',

                                'name'=>'state',

                                'id'=>'selectState',

                                'classes'=>'form-control',

                                'placeholder'=>'State',

                                'value'=>CommonHelper::getState('101'),

                    			'disabled'=>'',

                    			'selected'=>$detail_info['state_id']

                               ),

                               

                 "submit_button_field"=>array(

				  'label'=>'',

                  'type'=>'submit',

                  'name'=>'submit',

                  'id'=>'submit',

                  'classes'=>'btn btn-danger createbtn',

                  'placeholder'=>'',

                  'value'=>'Save'

            )

         )

       )

     );

	 if ($request->isMethod('post')) {

		 $input=$request->all();

        $id=base64_decode($request->id);

         $City = City::find($id);

         $request->validate([

            'name' => 'required|unique:cities,name,'.$id.',id,isdeleted,0',

         

         ],[

				'name.required'=> Config::get('messages.city.error_msg.name_required'),

				'name.unique'=>Config::get('messages.city.error_msg.name_unique'),

				'name.max'=>Config::get('messages.city.error_msg.name_max'),

			]

        );

        $update_data['name'] = $input['name'];

        $update_data['state_id'] = $input['state'];

        

       $data=City::where('id',$id)->update($update_data);

        MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_update_success'),$request);

  

   return Redirect::back();

	 }

           

		return view('admin.city.form',['page_details'=>$page_details ,'City_detail'=>$detail_info]);



   }





     public function del(Request $request)

    {

        $id=base64_decode($request->id);
        $data=City::where('id',$id)->update(['isdeleted' =>1]);
        MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_delete_success'),$request);
        return Redirect::route('city');

    }

    

    public function multi_delete_city(Request $request)

    {

			$input=$request->all();

			City::whereIn('id', $input['city_id'])

    ->update([

        'isdeleted' =>1

    ]);

	

	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_delete_success'),$request);

		   return redirect()->route('city');

			

    }

	

    public function city_sts(Request $request)

    {

            $id=base64_decode($request->id);

            $sts=base64_decode($request->sts);



            $res=City::where('id',$id)

                    ->update(['status' => ($sts==0) ? 1 : 0]);



            if ($res) {

                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_status_success'),$request);

            } else {

                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);

            }



            return Redirect::back();

    }

    

   

    

   



}

