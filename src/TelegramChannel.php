<?php

namespace TelegramNotification;

use TelegramNotification\Messages\TelegramEntity;
use TelegramNotification\Messages\TelegramCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use Illuminate\Validation\ValidationException;

/**
 * Class TelegramChannel
 *
 * @package App\Modules\TelegramNotification
 *
 * @author  Alexander Gorenkov <g.a.androidjc2@ya.ru> <Tg:@alex_brin>
 * @version 1.0.0
 * @since   1.0.0
 */
class TelegramChannel
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => sprintf("https://api.telegram.org/bot%s/", config('telegram.bot_token')),
        ]);
    }

    public function makeUri(TelegramEntity $message)
    {
        return sprintf("send%s", str_replace('Telegram', '', class_basename($message)));
    }

    private function buildPayload($chatId, TelegramEntity $message): array
    {
        return [
            'json' => array_merge(
                $message->toArray(),
                ['chat_id' => $chatId],
            ),
        ];
    }

    /**
     * @param $notifiable
     * @param  Notification  $notification
     *
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function send($notifiable, Notification $notification): void
    {
        $chatId = $notifiable->routeNotificationFor('telegram');

        if (!$chatId) {
            return;
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $entity     = $notification->toTelegram($notifiable);
        $collection = $entity instanceof TelegramCollection ? $entity : new TelegramCollection([$entity]);

        $collection
            ->validate()
            ->map(function (TelegramEntity $message) use ($chatId) {
                $this->client->post(
                    $this->makeUri($message),
                    $this->buildPayload($chatId, $message)
                );
            });
    }
}