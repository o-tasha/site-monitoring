<?php

namespace app\infr\Notifier;

class SmsNotifier implements NotifierInterface
{
    public function notify($from, $to, $message)
    {
        //код отправки SMS-сообщения
        return true;
    }
}