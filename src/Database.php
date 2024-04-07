<?php

declare(strict_types=1);

namespace FpDbTest;

use mysqli;
use FpDbTest\builder\QueryPrepare;
use FpDbTest\builder\QueryArguments;
use FpDbTest\exception\SkipException;

final readonly class Database implements DatabaseInterface
{
    private QueryPrepare $queryPrepare;

    public function __construct(mysqli $mysqli)
    {
        $this->queryPrepare = new QueryPrepare();
    }

    public function buildQuery(string $query, array $args = []): string
    {
        return $this->queryPrepare->prepape($query, new QueryArguments($args));
    }

    public function skip(): SkipException
    {
        return new SkipException();
    }
}
