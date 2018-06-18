<?php

namespace app\infr\Notifier;

class EmailNotifier implements NotifierInterface
{
    public function notify($from, $to, $message)
    {
        return \Yii::$app->mailer->compose()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject(isset($message['subject']) ? $message['subject'] : '')
            ->setTextBody(isset($message['text']) ? $message['text'] : '')
            ->send();
    }
}