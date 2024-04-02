<?php

namespace NotificationChannels\GoogleChat;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\GoogleChat\Exceptions\CouldNotSendNotification;

class GoogleChatAlert
{
    /**
     * The Http Client.
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Initialise a new Google Chat Channel instance.
     *
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param \NotificationChannels\GoogleChat\Messages\GoogleChatMessage $message
     *        The Google Chat message to be sent.
     * @param string|null $space
     *        Provide a specific space to be used. (Optional) If not provided, 
     *        the default space will be used by calling $message->getSpace().
     *
     * @throws \NotificationChannels\GoogleChat\Exceptions\CouldNotSendNotification
     *         If the notification could not be sent.
     */
    public function send(GoogleChatMessage $message, ?string $space = null)
    {
        $space = $space
            ?? $message->getSpace()
            ?? config('google-chat.space');

        if (!$endpoint = config("google-chat.spaces.$space", $space)) {
            throw CouldNotSendNotification::webhookUnavailable();
        }

        try {
            $this->client->request(
                'post',
                $endpoint,
                [
                    'json' => $message->toArray(),
                ]
            );
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::clientError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::unexpectedException($exception);
        }

        return $this;
    }
}
