<?php
namespace App\Services\Message;

use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Support\Facades\Log;
use App\Notifications\SendNotification;
use Auth;
class SMSMessageService implements IMessageService
{
    public function sendMessage(
        string $sms_from=null,
        int $sms_to=null,
        string $sms_message=null,
        string $ese_target=null,
        $ese_template=null
    ): bool
    {
        $basic  = new \Nexmo\Client\Credentials\Basic(env('SMS_Nexmo_Key', '6b175858'), env('SMS_Nexmo_Secret', 'dJzkWbtqlb1gIUlx'));
        $client = new \Nexmo\Client($basic);
 
        // $message = $client->message()->send([
        //     'to' => $sms_to,
        //     'from' => $sms_from,
        //     'text' => $sms_message
        // ]);
        // Log::info('Message Send Id: ' . $message['message-id']);
        // Auth::user()->notify(new SendNotification(Auth::user()->name, 'Message Send Id: : ' . $message['message-id']));
        return true;
        // return [
        //     'Send SMS'
        // ];
    }
}