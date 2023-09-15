<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;

class SliderController extends Controller
{
    public function getSlider()
      {
	   $data['sliderData'] = DB::table('sliders')
       ->select('sliders.*', 'sliders.image','sliders.description','sliders.url')
       ->orderby('id', 'desc')->get();
      	return view('fronted.index', $data);
    }
}
