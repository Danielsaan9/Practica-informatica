<?php

namespace Glpi\Form\Condition;

use Override;

enum VisibilityStrategy: string implements StrategyInterface
{
    case ALWAYS_VISIBLE = 'always_visible';
    case VISIBLE_IF = 'visible_if';
    case HIDDEN_IF = 'hidden_if';

    #[Override]
    public function getLabel(): string
    {
        return match ($this) {
            self::ALWAYS_VISIBLE => __('Always visible'),
            self::VISIBLE_IF     => __('Visible if...'),
            self::HIDDEN_IF      => __("Hidden if..."),
        };
    }

    #[Override]
    public function getIcon(): string
    {
        return match ($this) {
            self::ALWAYS_VISIBLE => 'ti ti-eye',
            self::VISIBLE_IF     => 'ti ti-eye-cog',
            self::HIDDEN_IF      => 'ti ti-eye-off',
        };
    }

    #[Override]
    public function showEditor(): bool
    {
        return match ($this) {
            self::ALWAYS_VISIBLE => false,
            self::VISIBLE_IF     => true,
            self::HIDDEN_IF      => true,
        };
    }

    public function mustBeVisible(bool $conditions_result): bool
    {
        return match ($this) {
            self::ALWAYS_VISIBLE => true,
            self::VISIBLE_IF     => $conditions_result,
            self::HIDDEN_IF      => !$conditions_result,
        };
    }
}
