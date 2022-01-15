<?php
namespace App\Services\Message;

use phpDocumentor\Reflection\Types\Boolean;

interface IMessageService
{
    public function sendMessage(
        string $sms_from=null,
        int $sms_to=null,
        string $sms_message=null,
        string $ese_target=null,
        $ese_template=null
        ): bool;
}