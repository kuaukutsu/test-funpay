<?php

declare(strict_types=1);

namespace FpDbTest\builder;

use FpDbTest\exception\SkipException;

final readonly class QueryArguments
{
    /** @var array<Argument> */
    private array $arguments;

    /**
     * @param array<null|scalar|array<scalar>|array<string, scalar|null>|SkipException> $args
     */
    public function __construct(array $args)
    {
        $this->arguments = array_map(
            static fn (mixed $arg): Argument => new Argument($arg),
            $args
        );
    }

    public function get(int $index): ?Argument
    {
        return $this->arguments[$index] ?? null;
    }
}
