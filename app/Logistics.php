<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Logistics extends Model
{
	protected $table = 'logistic_partner';
	protected $dates = ['created_at', 'updated_at'];
}
