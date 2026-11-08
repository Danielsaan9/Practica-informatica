<?php

namespace Glpi\Form\Condition;

final class SectionData
{
    public function __construct(
        private string $uuid,
        private string $name,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
