<?php

declare(strict_types=1);

namespace FpDbTest;

use mysqli;
use LogicException;
use RuntimeException;

final readonly class MysqlFactory
{
    /**
     * @throws LogicException invalid argument
     * @throws RuntimeException connection error
     */
    public function createConnection(array $args): mysqli
    {
        if (
            array_key_exists('DB_HOST', $args) === false
            || array_key_exists('DB_USER', $args) === false
            || array_key_exists('DB_PASSWORD', $args) === false
            || array_key_exists('DB_NAME', $args) === false
        ) {
            throw new LogicException('Argument "DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME" must be set');
        }

        $mysqli = @new mysqli($args['DB_HOST'], $args['DB_USER'], $args['DB_PASSWORD'], $args['DB_NAME'], 3306);
        if ($mysqli->connect_errno) {
            throw new RuntimeException($mysqli->connect_error ?? 'unknown error');
        }

        return $mysqli;
    }
}
