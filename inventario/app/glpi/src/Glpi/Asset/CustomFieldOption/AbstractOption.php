<?php

namespace Glpi\Asset\CustomFieldOption;

use Glpi\Asset\CustomFieldDefinition;

abstract class AbstractOption implements OptionInterface
{
    public function __construct(
        protected CustomFieldDefinition $custom_field,
        protected string $key,
        protected string $name,
        protected bool $apply_to_default = true,
        protected mixed $default_value = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setValue(mixed $value): void
    {
        $this->custom_field->fields['field_options'][$this->key] = $this->normalizeValue($value);
    }

    public function getValue(): mixed
    {
        return $this->custom_field->fields['field_options'][$this->key] ?? $this->default_value;
    }

    public function normalizeValue(mixed $value): mixed
    {
        return $value;
    }

    public function getApplyToDefault(): bool
    {
        return $this->apply_to_default;
    }
}
