<?php

namespace Glpi\DBAL;

/**
 * Trait to manage alias.
 */
trait QueryAliasTrait
{
    protected bool $with_alias = true;

    public function withAlias(bool $with = true): static
    {
        $this->with_alias = $with;
        return $this;
    }

}
