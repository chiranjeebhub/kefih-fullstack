<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Country extends Model
{
	
	
    protected $table = 'countries';
  
    public static function GetCountryCodeList(){
        return Country::where('status',1)->get(); 
    }
    
   
  
}
