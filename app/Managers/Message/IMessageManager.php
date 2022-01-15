<?php
namespace App\Managers\Message;
use App\Services\Message\IMessageService;

interface IMessageManager
{
    public function make($name): IMessageService;
}