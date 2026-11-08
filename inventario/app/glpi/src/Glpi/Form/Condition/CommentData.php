<?php

namespace Glpi\Form\Condition;

final class CommentData
{
    public function __construct(
        private string $uuid,
        private string $name,
        private ?string $section_uuid = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getSectionUuid(): ?string
    {
        return $this->section_uuid;
    }
}
