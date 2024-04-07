<?php

declare(strict_types=1);

namespace FpDbTest\builder;

use LogicException;
use FpDbTest\exception\SkipException;

final readonly class Argument
{
    /**
     * @param null|scalar|array<scalar>|array<string, scalar|null>|SkipException $value
     */
    public function __construct(private mixed $value)
    {
    }

    /**
     * @throws LogicException
     */
    public function toExpression(): string
    {
        if (is_array($this->value)) {
            $expressionString = '';
            foreach ($this->value as $value) {
                if (is_string($value)) {
                    $expressionString .= new ArgumentColumn($value) . ', ';
                }
            }

            return rtrim($expressionString, ', ');
        }

        if (is_string($this->value)) {
            return (string)new ArgumentColumn($this->value);
        }

        throw new LogicException('Expression unexpected value type.');
    }

    /**
     * @throws LogicException
     */
    public function toAssignment(): string
    {
        if (is_array($this->value)) {
            $assignmentString = '';
            foreach ($this->value as $key => $value) {
                $assignmentString .= sprintf('%s = %s, ', new ArgumentColumn($key), new ArgumentValue($value));
            }

            return rtrim($assignmentString, ', ');
        }

        throw new LogicException('Assignment list unexpected value type.');
    }

    /**
     * @throws SkipException
     */
    public function toCondition(?QuerySpecifiers $specifiers = null): string
    {
        if ($this->value === null) {
            return 'NULL';
        }

        if ($this->value instanceof SkipException) {
            throw $this->value;
        }

        if (is_array($this->value)) {
            $string = '';
            foreach ($this->value as $value) {
                $argument = new self($value);
                $string .=  $argument->toCondition() . ', ';
            }

            return rtrim($string, ', ');
        }

        return (string)new ArgumentValue(
            $specifiers instanceof QuerySpecifiers
                ? $specifiers->castValue($this->value)
                : $this->value
        );
    }
}
