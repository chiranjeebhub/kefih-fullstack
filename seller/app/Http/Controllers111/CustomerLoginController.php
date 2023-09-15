<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Redirect ;
use App\Customer;
use Illuminate\Support\Facades\Input;
class CustomerLoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function getCustomerLogin()
    {
        
      
        if (auth()->guard('customer')->user()) return redirect()->route('customer.dashboard');
        return view('customerLogin');
    }
    public function customerAuth(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (auth()->guard('customer')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            return redirect()->route('customer.dashboard');
        }else{
     $errors = new MessageBag(['password' => ['Email or password invalid.']]); // if Auth::attempt fails (wrong credentials) create a new message bag instance.

     return Redirect::back()->withErrors($errors)->withInput(Input::except('password'));
			
			//$errorMessage='your username and password are wrong.';
           // return redirect()->back()->with(['errors'=>$errorMessage]);
		//dd('your username and password are wrong.');
        }
    }
}