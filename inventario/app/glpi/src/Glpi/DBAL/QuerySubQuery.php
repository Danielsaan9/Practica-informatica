<?php

namespace Glpi\DBAL;

use AbstractQuery;
use DBmysqlIterator;
use RuntimeException;

/**
 *  Sub query class
 **/
class QuerySubQuery extends AbstractQuery
{
    private DBmysqlIterator $dbiterator;

    /**
     * Create a sub query
     *
     * @param array   $crit Array of query criteria. Any valid DBmysqlIterator parameters are valid.
     * @param ?string $alias Alias for the whole subquery
     */
    public function __construct(array $crit, $alias = null)
    {
        global $DB;

        parent::__construct($alias);
        if ($crit === []) {
            throw new RuntimeException('Cannot build an empty subquery');
        }

        $this->dbiterator = new DBmysqlIterator($DB);
        $this->dbiterator->buildQuery($crit);
    }

    /**
     *
     * Get SQL query
     *
     * @return string
     */
    public function getQuery()
    {
        global $DB;

        $sql = "(" . $this->dbiterator->getSql() . ")";

        if ($this->alias !== null) {
            $sql .= ' AS ' . $DB->quoteName($this->alias);
        }
        return $sql;
    }

    /**
     * @return array<int, mixed>
     */
    public function getParams(): array
    {
        return $this->dbiterator->getValues();
    }

    public function __toString()
    {
        return $this->getQuery();
    }
}
