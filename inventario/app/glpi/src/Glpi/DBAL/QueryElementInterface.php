<?php

namespace Glpi\DBAL;

use Stringable;

/**
 * Common contract for the SQL building blocks that may carry bound parameters.
 *
 * Implementations render themselves to a SQL fragment through {@see self::getValue()} and
 * expose the values to bind for the `?` placeholders that fragment contains through
 * {@see self::getParams()}.
 *
 * Both methods must always be used together: rendering a fragment without collecting its
 * parameters silently drops them, which shifts the positional binding of the whole statement.
 */
interface QueryElementInterface extends Stringable
{
    /**
     * SQL fragment.
     *
     * @return string
     *
     * @psalm-taint-escape sql
     */
    public function getValue(): string;

    /**
     * Values to bind, in the order their placeholders appear in {@see self::getValue()}.
     *
     * @return array<int, mixed>
     */
    public function getParams(): array;

    /**
     * SQL fragment, so that the element can be interpolated in a larger one.
     *
     * @psalm-taint-escape sql
     */
    public function __toString(): string;
}
