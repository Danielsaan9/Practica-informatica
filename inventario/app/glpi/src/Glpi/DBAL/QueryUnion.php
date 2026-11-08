<?php

namespace Glpi\DBAL;

use AbstractQuery;
use DBmysql;
use RuntimeException;

/**
 * UNION query class
 **/
class QueryUnion extends AbstractQuery
{
    /** @var QuerySubQuery[] */
    private array $queries = [];

    /**
     * Create a sub query
     *
     * @param array<int|string, QuerySubQuery|array<string, mixed>> $queries An array of queries to union. Either SubQuery objects
     *                          or an array of criteria to build them.
     * @param bool $distinct Include duplicates or not. Turning on may have
     *                          huge cost on queries performances.
     * @param string $alias Union ALIAS. Defaults to null.
     * @see addQuery
     */
    public function __construct(array $queries = [], private $distinct = false, $alias = null)
    {
        parent::__construct($alias);

        foreach ($queries as $query) {
            $this->addQuery($query);
        }
    }

    /**
     * Add a query
     *
     * @param QuerySubQuery|array<string,mixed> $query Either a SubQuery object
     *                                   or an array of criteria to build it.
     *
     * @return void
     */
    public function addQuery($query)
    {
        if (!$query instanceof QuerySubQuery) {
            $query = new QuerySubQuery($query);
        }
        $this->queries[] = $query;
    }


    /**
     * Get queries
     *
     * @return QuerySubQuery[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     *
     * Get SQL query
     *
     * @return string
     */
    public function getQuery()
    {
        $union_queries = $this->getQueries();
        if (
            empty($union_queries)
        ) {
            throw new RuntimeException('Cannot build an empty union query');
        }

        $queries = [];
        foreach ($union_queries as $uquery) {
            $queries[] = $uquery->getQuery();
        }

        $keyword = 'UNION';
        if (!$this->distinct) {
            $keyword .= ' ALL';
        }
        $query = '(' . implode(" $keyword ", $queries) . ')';

        $alias = $this->alias ?? 'union_' . md5($query);
        $query .= ' AS ' . DBmysql::quoteName($alias);

        return $query;
    }

    public function getParams(): array
    {
        $values = [];
        foreach ($this->queries as $query) {
            $values = array_merge($values, $query->getParams());
        }
        return $values;
    }
}
