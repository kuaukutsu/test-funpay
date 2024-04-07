<?php

declare(strict_types=1);

namespace FpDbTest\builder;

/**
 * ?d - конвертация в целое число
 * ?f - конвертация в число с плавающей точкой
 */
enum QuerySpecifiers: string
{
    case string = 's';
    case integer = 'd';
    case double = 'f';

    /**
     * @param string | int | float $value
     */
    public function castValue(mixed $value): string | int | float
    {
        return match ($this) {
            self::string => (string)$value,
            self::integer => (int)$value,
            self::double => (float)$value,
        };
    }
}
