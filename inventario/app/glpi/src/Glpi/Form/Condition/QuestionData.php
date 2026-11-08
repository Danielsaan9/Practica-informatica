<?php

namespace Glpi\Form\Condition;

use Glpi\Form\QuestionType\QuestionTypeInterface;

final class QuestionData
{
    public function __construct(
        private string $uuid,
        private string $name,
        private QuestionTypeInterface $type,
        private ?array $extra_data,
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

    public function getType(): QuestionTypeInterface
    {
        return $this->type;
    }

    public function getExtraData(): ?array
    {
        return $this->extra_data;
    }

    public function getSectionUuid(): ?string
    {
        return $this->section_uuid;
    }
}
