<?php

namespace Glpi\DBAL;

interface PrepareForCloneInterface
{
    /**
     * @param array<int|string, mixed> $data
     *
     * @return array<int|string, mixed>
     */
    public function prepareInputForClone(array $data): array;
}
