<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Testimonials extends Model
{
	
	
    protected $table = 'testimonials';
  
    protected $dates = ['created_at', 'updated_at'];

   
    public function support_image($path,$name)
    {
        $path=Config::get('constants.Url.public_url').$path.'/'.$name;
        return  $image='<img src="'.$path.'" class="img-thumbnail" alt="'.$this->name.'" width="50" height="50">';
    }
}
