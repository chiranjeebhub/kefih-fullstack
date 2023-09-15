<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Advertise extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_advertise';
    
}
