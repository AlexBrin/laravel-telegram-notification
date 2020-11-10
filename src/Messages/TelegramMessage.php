<?php

namespace TelegramNotification\Messages;

/**
 * Class TelegramMessage
 *
 * @package App\Modules\TelegramNotification\Messages
 *
 * @author  Alexander Gorenkov <g.a.androidjc2@ya.ru> <Tg:@alex_brin>
 * @version 1.0.0
 * @since   1.0.0
 */
class TelegramMessage extends TelegramEntity
{
    protected array $required = [
        'text'
    ];

    protected array $optional = [
        'parse_mode',
        'disable_web_page_preview',
        'disable_notification'
    ];
}