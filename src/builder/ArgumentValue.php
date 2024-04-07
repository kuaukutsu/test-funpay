<?php

declare(strict_types=1);

namespace FpDbTest\builder;

use Stringable;

final readonly class ArgumentValue implements Stringable
{
    private string $value;

    public function __construct(string | int | float | bool | null $value)
    {
        $this->value = match (gettype($value)) {
            'NULL' => 'NULL',
            'boolean' => $value ? '1' : '0',
            'string' => "'" . $this->prepare($value) . "'",
            default => (string)$value,
        };
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function prepare(string $stringValue): string
    {
        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]~u', '\\\$0', $stringValue);
    }
}
