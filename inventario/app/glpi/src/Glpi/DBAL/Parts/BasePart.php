<?php

namespace Glpi\DBAL\Parts;

use DBmysqlIterator;

abstract class BasePart
{
    protected string $query;
    /** @var array<int, mixed> */
    protected array $params;
    protected ?string $clause = null;

    /**
     * @param array<string, mixed> $criteria
     */
    public function withCriteria(array $criteria): static
    {
        global $DB;

        if ($criteria === []) {
            return $this;
        }

        $iterator = new DBmysqlIterator($DB);
        $it_criteria = ['FROM' => 'table'];
        if ($this->clause !== null) {
            $it_criteria += [$this->clause => $criteria];
        } else {
            $it_criteria += $criteria;
        }
        $iterator->buildQuery($it_criteria);

        return $this
            ->setQuery($iterator->getSql())
            ->setParams($iterator->getValues())
        ;
    }

    public function setQuery(string $query): static
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query ?? '';
    }

    /**
     * @param array<int, mixed> $values
     */
    public function setParams(array $values): static
    {
        $this->params = $values;
        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getParams(): array
    {
        return $this->params ?? [];
    }

    public function __toString()
    {
        return $this->getQuery();
    }
}
