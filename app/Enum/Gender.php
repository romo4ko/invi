<?php

namespace App\Enum;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case COUPLE = 'couple';

    public static function translations(): array
    {
        return [
            self::MALE->value => 'Мужской',
            self::FEMALE->value => 'Женский',
            self::COUPLE->value => 'Пара',
        ];
    }

    public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
