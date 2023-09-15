<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

//Notification for Seller
use App\Notifications\CustomerResetPasswordNotification;

//Auth Facade
use Illuminate\Support\Facades\Auth;
use DB;
//Password Broker Facade
use Illuminate\Support\Facades\Password;

class Customer extends Authenticatable
{
 
 // This trait has notify() method defined
  use Notifiable;

    protected $table = 'customers';
    
	//public $timestamps = false;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
  //hidden attributes
  protected $hidden = [
      'password', 'remember_token',
  ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
/*     protected $casts = [
        'email_verified_at' => 'datetime',
    ]; */
	public static function productInWishlist($user_id,$prd_id){
      
           $dd=DB::table('tbl_wishlist')
           ->where('fld_user_id',$user_id)
            ->where('fld_product_id',$prd_id)
           ->first();
           if($dd){
               return true;
           } else{
               return false;
           }
           
	}
	
	public static function productInCart($user_id,$prd_id,$color_id='',$size_id=''){
      
			$dd=DB::table('cart')
						->where('user_id',$user_id)
						->where('prd_id',$prd_id);
				
			if($color_id!='')
			{
				$dd->where('color_id',$color_id);
			}
			
			if($size_id!='')
			{
				$dd->where('size_id',$size_id);
			}
				
			$dd=$dd->first();
				
			if($dd){
               return true;
			} else{
               return false;
			}
	}
	
	public static function productInCartAPI($user_id,$prd_id){
      
           $dd=DB::table('cart')
           ->where('user_id',$user_id)
            ->where('prd_id',$prd_id)
           ->first();
           if($dd){
               return 1;
           } else{
               return 0;
           }
           
	}
	
	  //Send password reset notification
  public function sendPasswordResetNotification($token)
  {
      $this->notify(new CustomerResetPasswordNotification($token));
  }
}
