<?php

declare(strict_types=1);

namespace FpDbTest\builder;

use Stringable;

final readonly class ArgumentColumn implements Stringable
{
    private string $column;

    public function __construct(string $columns)
    {
        $this->column = $this->prepare($columns);
    }

    public function __toString(): string
    {
        return $this->column;
    }

    private function prepare(string $colName): string
    {
        return "`" . trim($colName, '`') . "`";
    }
}
