<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Override;

/**
 * Handler for conditions that check if another form element is visible or not
 */
class VisibilityConditionHandler implements ConditionHandlerInterface
{
    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::VISIBLE,
            ValueOperator::NOT_VISIBLE,
        ];
    }

    #[Override]
    public function getTemplate(): null
    {
        // No input field needed for visibility conditions
        return null;
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return [];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        $is_visible = (bool) $a;

        return match ($operator) {
            ValueOperator::VISIBLE => $is_visible,
            ValueOperator::NOT_VISIBLE => !$is_visible,

            // Unsupported operators
            default => false,
        };
    }
}
