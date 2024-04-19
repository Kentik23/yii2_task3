<?php

namespace common\models;

class StatusHelper
{
    const BRANDNEW = 10;
    const PUBLISHED = 20;
    const REJECTED = 30;

    public static function getAllStatus(): array
    {
        return array(self::BRANDNEW, self::PUBLISHED, self::REJECTED);
    }

    public static function getStatusAsString($status): int|string
    {
        return match ($status) {
            self::BRANDNEW => 'Новый',
            self::PUBLISHED => 'Опубликован',
            self::REJECTED => 'Отклонён',
            default => 0,
        };
    }

    public static function getAllStatusAsString(): array
    {
        return array(self::BRANDNEW => 'Новый',
            self::PUBLISHED => 'Опубликован',
            self::REJECTED => 'Отклонён');
    }
}