<?php

namespace app\infr\Notifier;

interface NotifierInterface
{
    /**
     * @param string $from
     * @param string $to
     * @param array $message
     *
     * @return bool
     */
    public function notify($from, $to, $message);
}