<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Override;

class NumberConditionHandler implements ConditionHandlerInterface
{
    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::EQUALS,
            ValueOperator::NOT_EQUALS,
            ValueOperator::GREATER_THAN,
            ValueOperator::GREATER_THAN_OR_EQUALS,
            ValueOperator::LESS_THAN,
            ValueOperator::LESS_THAN_OR_EQUALS,
        ];
    }

    #[Override]
    public function getTemplate(): string
    {
        return '/pages/admin/form/condition_handler_templates/input.html.twig';
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return [
            'attributes' => [
                'type' => 'number',
                'step' => 'any',
            ],
        ];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        // Normalize values.
        $a = (float) $a;
        $b = (float) $b;

        return match ($operator) {
            ValueOperator::EQUALS                 => $a === $b,
            ValueOperator::NOT_EQUALS             => $a !== $b,
            ValueOperator::GREATER_THAN           => $a > $b,
            ValueOperator::GREATER_THAN_OR_EQUALS => $a >= $b,
            ValueOperator::LESS_THAN              => $a < $b,
            ValueOperator::LESS_THAN_OR_EQUALS    => $a <= $b,

            // Unsupported operators
            default => false,
        };
    }
}
