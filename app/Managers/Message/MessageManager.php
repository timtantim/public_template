<?php
namespace App\Managers\Message;
use App\Services\Message\ESEMessageService;
use App\Services\Message\SMSMessageService;
use App\Services\Message\IMessageService;
use Illuminate\Support\Arr;

class MessageManager implements IMessageManager
{
    private $messages = [];
    public function make($name): IMessageService
    {
        $service = Arr::get($this->messages, $name);
        // No need to create the service every time
        if ($service) {
            return $service;
        }
        $createMethod = 'create' . ucfirst($name) . 'MessageService';
        if (!method_exists($this, $createMethod)) {
            throw new \Exception("Message $name is not supported");
        }
        $service = $this->{$createMethod}();
        $this->messages[$name] = $service;
        return $service;
    }
    private function createSMSMessageService(): SMSMessageService
    {
        $service = new SMSMessageService();
        return $service;
    }
    private function createESEMessageService(): ESEMessageService
    {
        $service = new ESEMessageService();
        return $service;
    }
}