<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Http\Controllers\Controller;

class FirebaseController extends Controller
{
    public function testnotificatiion(Request $request){
 $optionBuilder = new OptionsBuilder();
$optionBuilder->setTimeToLive(60*20);

$notificationBuilder = new PayloadNotificationBuilder('my title');
$notificationBuilder->setBody('Hello world')
				    ->setSound('default');

$dataBuilder = new PayloadDataBuilder();
$dataBuilder->addData(['a_data' => 'my_data']);

$option = $optionBuilder->build();
$notification = $notificationBuilder->build();
$data = $dataBuilder->build();

$token = "ck5wgXR_yno:APA91bEIX-Syo8xClIOIN1Mz5AhjpBC7a2TGCA61U6gGbpaNGXdwP9TWk0a3BRr1M8a-Rs516QdhvytRK8JXTzNmx4nKwHyzMwKXDYnWDgIr87T5_165o1f7sAi5_cTqj8opbDbnMpmX";

$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

echo $downstreamResponse->numberSuccess();
echo $downstreamResponse->numberFailure();
$downstreamResponse->numberModification();

// return Array - you must remove all this tokens in your database
$downstreamResponse->tokensToDelete();

// return Array (key : oldToken, value : new token - you must change the token in your database)
$downstreamResponse->tokensToModify();

// return Array - you should try to resend the message to the tokens in the array
$downstreamResponse->tokensToRetry();

// return Array (key:token, value:error) - in production you should remove from your database the tokens
$downstreamResponse->tokensWithError();

echo "sended";
    }

}
