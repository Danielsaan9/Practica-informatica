<?php

namespace Glpi\Form\Export\Specification;

final class CommentContentSpecification
{
    public int $id;
    public string $uuid;
    public string $name;
    public string $description;
    public int $vertical_rank;
    public ?int $horizontal_rank = null;
    public int $section_id;
    public string $visibility_strategy;

    /** @var ConditionDataSpecification[] $conditions */
    public array $conditions;
}
