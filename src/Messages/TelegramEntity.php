<?php

namespace TelegramNotification\Messages;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Validator;

/**
 * Class TelegramEntity
 *
 * @package App\Modules\TelegramNotification\Messages
 *
 * @author  Alexander Gorenkov <g.a.androidjc2@ya.ru> <Tg:@alex_brin>
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class TelegramEntity
{
    protected array $required = [];

    protected array $optional = [];

    protected array $parameters = [];


    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function __call($method, $args)
    {
        $parameter = Str::snake($method);

        if (count($args) === 1 && (in_array($parameter, $this->required, false) || in_array($parameter, $this->optional, false))) {
            $this->parameters[$parameter] = reset($args);
        }

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function validate(): void
    {
        Validator::make($this->parameters, array_fill_keys($this->required, 'required'))->validate();
    }

    public function toArray(): array
    {
        return $this->parameters;
    }
}