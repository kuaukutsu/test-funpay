<?php

declare(strict_types=1);

namespace FpDbTest\builder;

use RuntimeException;
use Stringable;
use FpDbTest\exception\SkipException;

final readonly class Query implements Stringable
{
    private string $queryPrepare;

    public function __construct(string $query, QueryArguments $arguments)
    {
        $this->queryPrepare = $this->prepape($query, $arguments);
    }

    public function toString(): string
    {
        return $this->queryPrepare;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @throws RuntimeException
     */
    private function prepape(string $query, QueryArguments $arguments): string
    {
        $inc = 0;

        /**
         * @var array{"template": string, "token": string, "specificator": ?string} $matches
         * @throws RuntimeException
         * @throws SkipException
         */
        $handler = static function (array $matches) use ($arguments, &$inc): string {
            $argument = $arguments->get($inc++);
            if ($argument === null) {
                throw new RuntimeException('Argument must be declared.');
            }

            /** @var array{"template": string, "token": string, "specificator": ?string} $matches */
            return $matches['token']
                . match (strtolower(trim($matches['token']))) {
                    'select', '' => $argument->toExpression(),
                    'set' => $argument->toAssignment(),
                    default => $argument->toCondition(
                        QuerySpecifiers::tryFrom($matches['specificator'] ?? '')
                    )
                };
        };

        $subqueries = [];
        // Условные блоки не могут быть вложенными
        $query = preg_replace_callback(
            '/(?<subquery>\{.*})/U',
            static function ($matches) use (&$subqueries) {
                /** @var array{"subquery": string} $matches */
                $subqueries[] = trim($matches['subquery'], '{}');
                return '[SUBQUERY' . (count($subqueries) - 1) . ']';
            },
            $query
        );

        $query = preg_replace_callback(
            '/(?<template>(?<token>SELECT\s|SET\s|=\s|\(|\s)\?(?<specificator>[adf#])?)/',
            $handler,
            $query
        );

        if ($subqueries !== []) {
            foreach ($subqueries as $key => $subquery) {
                try {
                    $subquery = preg_replace_callback(
                        '/(?<template>(?<token>=\s|\(|\s)\?(?<specificator>[adf#])?)/',
                        $handler,
                        $subquery
                    );
                } catch (SkipException) {
                    $subquery = '';
                }

                $query = (string)str_replace('[SUBQUERY' . $key . ']', $subquery, $query);
            }
        }

        return $query;
    }
}
