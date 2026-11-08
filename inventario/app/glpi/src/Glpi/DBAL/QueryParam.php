<?php

namespace Glpi\DBAL;

class QueryParam
{
    /**
     * Query parameter value.
     *
     * @return string
     */
    public function getValue()
    {
        return '?';
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
