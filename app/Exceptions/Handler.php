<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Helpers\PHPMailer;
use Config;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $exception)
    // {
    //     return parent::render($request, $exception);
    // }
    
                public function render($request, Exception $exception)
            {
                   
                if ($this->isHttpException($exception)) {
                        //  $this->sendEmail($exception); // sends an email
                    
                    if ($exception->getStatusCode() == 404) {
                        return response()->view('errors.' . '404', [], 404);
                    }
                    if ($exception->getStatusCode() == 505) {
                        return response()->view('errors.' . '404', [], 404);
                    }
                }
             
                return parent::render($request, $exception);
            }


        public function sendEmail(Exception $exception)
        {
           
        $email_data = [
                        'to'=>Config::get('constants.email.site_error_email_send_to'),
                        'subject'=>'Site Error',
                         "body"=>view("emails_template.error_reporting",
                             array(
                    	    'data'=>array(
                    	        'message'=>$exception
                    	        )
                    	     ) )->render(),
                    	     'phone'=>'',
                    	     'phone_msg'=>''
                         ];
        
             $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->Host = Config::get('constants.email.host');
                    $mail->SMTPAuth = true;
                    //$mail->SMTPSecure = "ssl";
                    $mail->Port =Config::get('constants.email.port');
                    $mail->Username = Config::get('constants.email.username');
                    $mail->Password = Config::get('constants.email.password');
                    
                    $mail->From = "mailto@aptechbangalore.com";    /*From address required*/
                    $mail->FromName = Config::get('constants.email.admin_from_name');
                    $mail->AddAddress($email_data['to']);
                    //$mail->AddReplyTo("mail@mail.com");
                    $mail->IsHTML(true);
                    $mail->Subject = $email_data['subject'];
                    $mail->Body = $email_data['body'];
                    $mail->Send();
        }
}
