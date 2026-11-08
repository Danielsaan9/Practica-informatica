<?php

namespace Glpi\Form\Condition;

use Override;

enum ValidationStrategy: string implements StrategyInterface
{
    case NO_VALIDATION = 'no_validation';
    case VALID_IF      = 'valid_if';
    case INVALID_IF    = 'invalid_if';

    #[Override]
    public function getLabel(): string
    {
        return match ($this) {
            self::NO_VALIDATION => __('No validation'),
            self::VALID_IF      => __('Valid if...'),
            self::INVALID_IF    => __('Invalid if...'),
        };
    }

    #[Override]
    public function getIcon(): string
    {
        return match ($this) {
            self::NO_VALIDATION => 'ti ti-filter',
            self::VALID_IF      => 'ti ti-filter-cog',
            self::INVALID_IF    => 'ti ti-filter-x',
        };
    }

    #[Override]
    public function showEditor(): bool
    {
        return match ($this) {
            self::NO_VALIDATION => false,
            self::VALID_IF      => true,
            self::INVALID_IF    => true,
        };
    }

    public function mustBeValidated(bool $conditions_result): bool
    {
        return match ($this) {
            self::NO_VALIDATION => true,
            self::VALID_IF      => $conditions_result,
            self::INVALID_IF    => !$conditions_result,
        };
    }
}
