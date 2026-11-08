<?php

namespace Glpi\Form\Destination;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class HasFieldWithQuestionId
{
    public function __construct(
        private string $config_field_key,
        private bool $is_array = false,
        private ?string $list_of_strategies_field = null,
    ) {}

    public function getConfigKey(): string
    {
        return $this->config_field_key;
    }

    public function isArray(): bool
    {
        return $this->is_array;
    }

    public function isArrayOfStrategies(): bool
    {
        return $this->list_of_strategies_field !== null;
    }

    public function getListOfStrategiesField(): ?string
    {
        return $this->list_of_strategies_field;
    }
}
