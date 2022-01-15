<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyCode;
use App\Managers\Message\IMessageManager;
use Illuminate\Http\Request;
use App\Events\SlackNotifyEvent;
use App\Traits\ResponseAPI;

/**
 * @Test Api
 *
 * APIs for Development
 */
class TestApiController extends Controller
{
    use ResponseAPI;
    protected $messageManager;
    /**
     * Create a new class instance.
     *
     * @param  App\Managers\Message\IMessageManager  $messageManager
     * @return void
     */
    public function __construct(IMessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }
    /**
     * test_language
     *
     * 測試使用者的語言
     * 回傳的資料會顯示該用戶的語言.
     * @group Test Api
     * @unauthenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField token 金鑰.
     */
    public function test_language(Request $request)
    {
        return response(trans('messages.greeting'), 200);
    }
    /**
     * test_email
     *
     * 測試Email
     *
     * 回傳的資料會顯示該用戶的語言.
     * @group Test Api
     * @unauthenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     * @responseField token 金鑰.
     */
    public function test_email(Request $request)
    {
        // event( new SlackNotifyEvent('Test Email'));
        try {
            $service = $this->messageManager->make('ESE');
            $message = $service->sendMessage(null, null, null, 'yutengchen0116@gmail.com', new VerifyCode([
                'name' => 'John',
                'verify_code' => '13648',
            ]));
            if ($message) {
                return $this->success("Mail send successfully",null);
            } else {
                return $this->error('Mail send failed');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * test_sms
     *
     * 測試SMS
     *
     * @group Test Api
     * @unauthenticated
     * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
     */
    public function send_sms_notificaition()
    {
        $service = $this->messageManager->make('SMS');
        $message = $service->sendMessage('YT SMS', 886933350536, 'A simple hello message sent from Vonage SMS API');
        return $this->success("SMS message has been delivered.",null);
    }
}
