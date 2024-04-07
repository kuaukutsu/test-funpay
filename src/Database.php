<?php

declare(strict_types=1);

namespace FpDbTest;

use mysqli;
use FpDbTest\builder\Query;
use FpDbTest\builder\QueryArguments;
use FpDbTest\exception\SkipException;

final readonly class Database implements DatabaseInterface
{
    public function __construct(mysqli $mysqli)
    {
    }

    public function buildQuery(string $query, array $args = []): string
    {
        return (new Query($query, new QueryArguments($args)))->toString();
    }

    public function skip(): SkipException
    {
        return new SkipException();
    }
}
