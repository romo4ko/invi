<?php

namespace App\Enum;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public static function translations(): array
    {
        return [
            self::MALE->value => 'Мужской',
            self::FEMALE->value => 'Женский',
        ];
    }

    public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
