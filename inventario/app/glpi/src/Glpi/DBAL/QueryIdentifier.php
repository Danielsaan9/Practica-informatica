<?php

namespace Glpi\DBAL;

use DBmysql;
use Glpi\Exception\Database\QueryException;

/**
 * A database identifier (table or field name) that has to be quoted.
 */
final class QueryIdentifier implements QueryElementInterface, QueryAliasInterface
{
    use QueryAliasTrait;

    /**
     * @param string  $name  Identifier to quote. May be qualified (`table.field`), the `*`
     *                       wildcard, or already quoted.
     * @param ?string $alias Alias, quoted along with the identifier.
     */
    public function __construct(
        private readonly string $name,
        private readonly ?string $alias = null,
    ) {
        if ($name === '') {
            throw new QueryException('Cannot build an empty identifier');
        }
        if ($alias === '') {
            throw new QueryException('Cannot build an empty alias');
        }
    }

    public function getValue(): string
    {
        $sql = DBmysql::quoteName($this->name);
        if ($this->alias !== null) {
            $sql .= ' AS ' . DBmysql::quoteName($this->alias);
        }
        return $sql;
    }

    public function getParams(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
