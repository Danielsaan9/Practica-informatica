<?php

namespace Glpi\DBAL\Parts;

use Glpi\DBAL\Operator;

class Where extends BasePart
{
    protected Operator $operator = Operator::NONE;
    protected ?string $clause = 'WHERE';

    public function setOperator(Operator $operator): static
    {
        $this->operator = $operator;
        return $this;
    }

    public function getOperator(): Operator
    {
        return $this->operator;
    }

    public function getQuery(): string
    {
        $sql = parent::getQuery();
        if ($this->operator !== Operator::NONE) {
            $sql = $this->operator->value . ' ' . $sql;
        }
        return $sql;
    }

    public function setQuery(string $sql): static
    {
        parent::setQuery($sql);

        //remove text clause from SQL
        $this->query = trim(str_replace('SELECT * FROM `table` ' . $this->clause, '', $this->query));
        //Handle case where clause is missing - which should not happen.
        $this->query = trim(str_replace('SELECT * FROM `table`', '', $this->query));
        return $this;
    }
}
