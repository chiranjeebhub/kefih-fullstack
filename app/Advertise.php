<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class Advertise extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_advertise';


    public static function getPopupAds(){
        $position7=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',7)
					->where('tbl_advertise.status',1)->first();
                    return $position7; 
    }
    
}
