<?php

namespace app\infr\Notifier;

class NotifierFactory
{
    CONST EMAIL = 0;
    CONST SMS = 1;

    /**
     * @param int $notificationType
     * @return NotifierInterface
     * @throws \Exception
     */
    public static function create($notificationType)
    {
        switch ($notificationType) {
            case self::EMAIL:
                return new EmailNotifier();
            case self::SMS:
                return new SmsNotifier();
            default:
                throw new \Exception('Unknown notification type');
        }
    }
}