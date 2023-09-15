<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;

//Auth Facade
use Illuminate\Support\Facades\Auth;

//Password Broker Facade
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    //Seller redirect path
    protected $redirectTo = 'customer/dashboard';

    //trait for handling reset Password
    use ResetsPasswords;

    //Show form to customer where they can save new password
    public function showResetForm(Request $request, $token = null)
    {
        return view('customer.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    //returns Password broker of seller
    public function broker()
    {
        return Password::broker('customers');
    }

    //returns authentication guard of seller
    protected function guard()
    {
        return Auth::guard('customer');
    }
}
