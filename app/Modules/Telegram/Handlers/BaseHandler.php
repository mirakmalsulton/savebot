<?php

namespace App\Modules\Telegram\Handlers;

use App\Modules\Telegram\Entities\User\TgUser;

abstract class BaseHandler
{
    const ?string COMMAND = null;

    public static function command(string $command = null): string
    {
        if ($command) {
            return __('_commands.' . $command);
        }

        if (defined('static::COMMAND') && !empty(static::COMMAND)) {
            return __('_commands.' . static::COMMAND);
        }

        return static::class;
    }

    public static function checkByString(string $requestCommand, $handlerCommand = null): bool
    {
        if ($handlerCommand) {
            return $requestCommand === self::command($handlerCommand);
        }

        return self::command() === $requestCommand;
    }

    public static function checkByState(TgUser $user): bool
    {
        if (empty($user->state)) {
            return false;
        }

        return $user->state->getHandler() === self::command();
    }
}
