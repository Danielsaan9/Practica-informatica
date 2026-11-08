<?php

namespace Glpi\DBAL;

use Glpi\Exception\Database\QueryException;

/**
 * A database scalar value used in a statement.
 *
 * Renders as a `?` placeholder and carries the value to bind for it, so the value travels with
 * the SQL fragment instead of being inlined into it:
 * ```php
 * QueryFunction::dateAdd(new QueryIdentifier('date_creation'), new QueryValue(3), 'DAY')
 * ```
 */
final class QueryValue implements QueryElementInterface
{
    /**
     * @param scalar|null $value Value to bind.
     */
    public function __construct(private readonly mixed $value)
    {
        if ($value !== null && !is_scalar($value)) {
            throw new QueryException(
                sprintf('A query value must be a scalar, %s given', get_debug_type($value))
            );
        }
    }

    public function getValue(): string
    {
        return '?';
    }

    public function getParams(): array
    {
        return [$this->value];
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
