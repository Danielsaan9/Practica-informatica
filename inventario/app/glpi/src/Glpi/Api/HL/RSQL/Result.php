<?php

namespace Glpi\Api\HL\RSQL;

use Glpi\DBAL\QueryExpression;

class Result
{
    /**
     * @param QueryExpression $where_criteria
     * @param QueryExpression $having_criteria
     * @param array<string, Error> $invalid_filters
     */
    public function __construct(
        private QueryExpression $where_criteria,
        private QueryExpression $having_criteria,
        private array $invalid_filters = []
    ) {}

    public function getSQLWhereCriteria(): QueryExpression
    {
        return $this->where_criteria;
    }

    public function getSQLHavingCriteria(): QueryExpression
    {
        return $this->having_criteria;
    }

    public function getInvalidFilters(): array
    {
        return $this->invalid_filters;
    }
}
