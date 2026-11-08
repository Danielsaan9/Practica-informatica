<?php

namespace Glpi\Toolbox;

interface MapperInterface
{
    public function addMappedItem(string $itemtype, string|int $key, int $id): void;

    public function getItemId(string $itemtype, string|int $key): int;
}
