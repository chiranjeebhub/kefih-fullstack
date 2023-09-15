<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class VendorContactUs extends Model
{
	
	
    protected $table = 'contact_enquiry_by_vendors';
  
    protected $dates = ['created_at', 'updated_at'];


}
