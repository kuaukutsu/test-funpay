<?php

declare(strict_types=1);

namespace FpDbTest;

use FpDbTest\exception\SkipException;

interface DatabaseInterface
{
    /**
     * @param array<null|scalar|array<scalar>|array<string, scalar|null>> $args
     */
    public function buildQuery(string $query, array $args = []): string;

    public function skip(): SkipException;
}
