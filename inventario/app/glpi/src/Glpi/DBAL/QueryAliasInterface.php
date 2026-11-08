<?php

namespace Glpi\DBAL;

/**
 * Common contract for the SQL alias.
 */
interface QueryAliasInterface
{
    public function withAlias(bool $with = true): static;
}
