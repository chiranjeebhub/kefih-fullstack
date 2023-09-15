<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//Trait
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

//Auth Facade
use Illuminate\Support\Facades\Auth;

//Password Broker Facade
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
     //Sends Password Reset emails
    use SendsPasswordResetEmails;
	
	    //Shows form to request password reset
    public function showLinkRequestForm()
    {
        return view('customer.passwords.email');
    }
	
	    //Password Broker for Seller Model
    public function broker()
    {
         return Password::broker('customers');
    }
}
