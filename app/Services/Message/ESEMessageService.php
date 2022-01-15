<?php
namespace App\Services\Message;

use App\Mail\VerifyCode;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Support\Facades\Log;
use App\Notifications\SendNotification;
use Auth;
class ESEMessageService implements IMessageService
{
    public function sendMessage(        
        string $sms_from=null,
        int $sms_to=null,
        string $sms_message=null,
        string $ese_target=null,
        $ese_template=null
    ): bool
    {

        // $valueArray = [
        //     'name' => 'John',
        //     'verify_code' => '13648',
        // ];
        \Mail::to($ese_target)->send($ese_template);
        Log::info('Message Send By Mail: ' . $ese_target);
        Auth::user()->notify(new SendNotification(Auth::user()->name, 'Message Send By Mail: ' . $ese_target));
        return true;
        // return [
        //     'Send ESE',
        // ];
    }

}
