<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		
        $this->middleware('guest')->except('logout');
		
    }
    
      public function showLoginForm()
    {
        return view('auth.login');
    }
    public function admin_forgot_password(Request $request){
         return view('auth.forgotpassword');
    }
	public function login(Request $request)
    {
   
        
      
        $is_remember = ($request->remember)?true:false;
  
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if($this->guard()->validate($this->credentials($request))) {
       
            // Auth::attempt(['email' => $request->email, 'password' => $request->password,'user_role' => 0]
           /* if($request->remember===null){
               // dd(1);
                setcookie('email',$request->email,100);
                setcookie('pass',$request->password,100);
             }
             else{
              //  dd(2);
                setcookie('email',$request->email,time()+60*60*24*100);
                setcookie('pass',$request->password,time()+60*60*24*100);
 
             }*/
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $is_remember)) {
                  return redirect()->intended('admin/dashboard');
            }  else {
                $this->incrementLoginAttempts($request);
              
				
				 return Redirect::back()->withErrors(['Credentials do not match our database.']);
            }
        } else {
            // dd('ok');
            $this->incrementLoginAttempts($request);
			return Redirect::back()->withErrors(['Credentials do not match our database.']);
           
        }
    }
}
