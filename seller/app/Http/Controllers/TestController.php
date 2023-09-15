<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Vendor;
use App\Helpers\MsgHelper;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Carbon\Carbon;
use App\Helpers\CommonHelper;
use App\Helpers\PHPMailer;
class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function test()
    {
        $msg = '<tr>
                             <td style="padding:5px 10px;">
                                	<p>Dear <strong> Yogendra,</strong></p>
                                    <p>You have successfully registered yourself on <a href="">18up.in</a></p>
                    
                             </td>
                        </tr>
                       ';
            $data = [
                'to'=>'yogendra@b2cmarketing.in',
                'subject'=>'Query',
                 "body"=>view("emails.email",
                     array(
				    'data'=>array(
				        'message'=>$msg
				        )
				     ) )->render(),
				     'phone'=>'7017734526',
				     'phone_msg'=>'You have successfully registered yourself on 18UP.in'
                 ];
        CommonHelper::SendmailCustom($data);
          CommonHelper::SendMsg($data);
     
//         $mail = new PHPMailer();

// $mail->IsSMTP();
// $mail->Host = "mail.aptechbangalore.com";  /*SMTP server*/

// $mail->SMTPAuth = true;
// //$mail->SMTPSecure = "ssl";
// $mail->Port = 587;
// $mail->Username = "mailto@aptechbangalore.com";  /*Username*/
// $mail->Password = "47#z2NqYMZX";    /**Password**/

// $mail->From = "mailto@aptechbangalore.com";    /*From address required*/
// $mail->FromName = "Test ";
// $mail->AddAddress("yogendra@b2cmarketing.in");
// //$mail->AddReplyTo("mail@mail.com");

// $mail->IsHTML(true);

// $mail->Subject = "Test message from server fdgrhthtrhythjy";
// $mail->Body = "Test Mail<b>in bold!</b>";
// //$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

// $mail->Send();
    }
   
}
