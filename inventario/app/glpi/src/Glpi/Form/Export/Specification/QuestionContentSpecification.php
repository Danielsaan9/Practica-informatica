<?php

namespace Glpi\Form\Export\Specification;

final class QuestionContentSpecification implements ContentSpecificationInterface
{
    public int $id;
    public string $uuid;
    public string $name;
    public string $type;
    public bool $is_mandatory;
    public int $vertical_rank;
    public ?int $horizontal_rank = null;
    public ?string $description = null;
    public mixed $default_value;
    public ?array $extra_data = null;
    public int $section_id;
    public string $visibility_strategy;
    public string $validation_strategy;

    /** @var ConditionDataSpecification[] $conditions */
    public array $conditions;
    /** @var ConditionDataSpecification[] $validation_conditions */
    public array $validation_conditions;

}
