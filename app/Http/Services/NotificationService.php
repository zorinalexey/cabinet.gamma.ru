<?php

namespace App\Http\Services;

class NotificationService
{

    private static $notifications = [];

    public static function set(string $notice, string|null $key = null): void
    {
        if ($key) {
            self::$notifications[$key] = $notice;
        } else {
            self::$notifications[] = $notice;
        }
    }

    public static function get(): array
    {
        return self::$notifications;
    }
}
