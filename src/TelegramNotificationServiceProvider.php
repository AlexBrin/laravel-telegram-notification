<?php

namespace TelegramNotification;

use Illuminate\Support\ServiceProvider;

/**
 * Class TelegramNotificationServiceProvider
 *
 * @package TelegramNotification
 *
 * @author  Alexander Gorenkov <g.a.androidjc2@ya.ru> <Tg:@alex_brin>
 * @version 1.0.0
 * @since   1.0.0
 */
class TelegramNotificationServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->publishes([
            sprintf("%s/../config/telegram.php", __DIR__) => config_path('telegram.php')
        ]);
    }
}