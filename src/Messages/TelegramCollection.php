<?php

namespace TelegramNotification\Messages;

use TelegramNotification\Messages\TelegramEntity;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Class TelegramCollection
 *
 * @package App\Modules\TelegramNotification\src\Messages
 *
 * @author  Alexander Gorenkov <g.a.androidjc2@ya.ru> <Tg:@alex_brin>
 * @version 1.0.0
 * @since   1.0.0
 */
class TelegramCollection
{
    protected array $messages = [];

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function put(TelegramEntity $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return $this
     * @throws ValidationException
     */
    public function validate(): self
    {
        $this->map(static function(TelegramEntity $message) {
            $message->validate();
        });

        return $this;
    }

    public function map($callback): self {
        foreach($this->messages as $message) {
            $callback($message);
        }

        return $this;
    }

    public function __call($method, $args): self
    {
        $messageClass = sprintf("%s\\Telegram%s", __NAMESPACE__, Str::studly($method));

        if (class_exists($messageClass)) {
            $this->put(new $messageClass(...$args));
        }

        return $this;
    }
}