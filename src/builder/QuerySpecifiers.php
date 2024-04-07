<?php

declare(strict_types=1);

namespace FpDbTest\builder;

use LogicException;

enum QuerySpecifiers: string
{
    case string = 's';
    case integer = 'd';
    case double = 'f';
    case array = 'a';

    /**
     * @param string | int | float $value
     */
    public function castValue(mixed $value): string | int | float
    {
        return match ($this) {
            self::string => (string)$value,
            self::integer => (int)$value,
            self::double => (float)$value,
            default => throw new LogicException('Specifiers unexpected value type.')
        };
    }
}
