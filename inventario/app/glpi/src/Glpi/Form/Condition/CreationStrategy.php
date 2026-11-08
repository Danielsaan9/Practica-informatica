<?php

namespace Glpi\Form\Condition;

use Override;

enum CreationStrategy: string implements StrategyInterface
{
    case ALWAYS_CREATED = 'always_created';
    case CREATED_IF = 'created_if';
    case CREATED_UNLESS = 'created_unless';

    #[Override]
    public function getLabel(): string
    {
        return match ($this) {
            self::ALWAYS_CREATED => __("Always created"),
            self::CREATED_IF     => __("Created if..."),
            self::CREATED_UNLESS => __("Created unless..."),
        };
    }

    #[Override]
    public function getIcon(): string
    {
        return match ($this) {
            self::ALWAYS_CREATED => 'ti ti-plus',
            self::CREATED_IF     => 'ti ti-code-plus',
            self::CREATED_UNLESS => 'ti ti-code-plus',
        };
    }

    #[Override]
    public function showEditor(): bool
    {
        return match ($this) {
            self::ALWAYS_CREATED => false,
            self::CREATED_IF     => true,
            self::CREATED_UNLESS => true,
        };
    }

    public function mustBeCreated(bool $conditions_result): bool
    {
        return match ($this) {
            self::ALWAYS_CREATED => true,
            self::CREATED_IF     => $conditions_result,
            self::CREATED_UNLESS => !$conditions_result,
        };
    }
}
