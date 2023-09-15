<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Slider extends Authenticatable
{
    use Notifiable;

    protected $table = 'sliders';
    
	//public $timestamps = false;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      // 'name', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      //  'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
/*     protected $casts = [
        'email_verified_at' => 'datetime',
    ]; */
}
