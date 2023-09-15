<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
class ProductQuestions extends Model
{
	
    protected $table = 'product_question_answer';
    protected $dates = ['created_at', 'updated_at'];

   public static function productQuestions($id){
	  
		$res=DB::table('product_question_answer')->where('product_id',$id)->get()->toArray();
		
		return $res;
	}
  
}
