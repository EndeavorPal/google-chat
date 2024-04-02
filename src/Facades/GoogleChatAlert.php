<?php

namespace NotificationChannels\GoogleChat\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void send(\NotificationChannels\GoogleChat\GoogleChatMessage $message, null|string $space = null)
 */
class GoogleChatAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'google-chat-alerts';
    }
}