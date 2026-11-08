<?php

namespace Glpi\DBAL\Parts;

class LeftJoin extends BasePart
{
    public function setQuery(string $sql): static
    {
        parent::setQuery($sql);

        //remove FROM clause from SQL
        $this->query = trim(str_replace('SELECT * FROM `table`', '', $this->query));
        return $this;
    }
}
