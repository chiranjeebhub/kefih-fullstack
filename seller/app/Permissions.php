<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Permissions extends Model
{
	
	
    protected $table = 'permissions';
  
    protected $dates = ['created_at', 'updated_at'];

   
  
}
