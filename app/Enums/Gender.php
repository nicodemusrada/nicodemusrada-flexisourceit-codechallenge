<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum Gender
 * @package App\Enums
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.7
 */
enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public function toString(): string
    {
        return match($this) {
            self::Male => 'male',
            self::Female => 'female',
        };
    }
}
