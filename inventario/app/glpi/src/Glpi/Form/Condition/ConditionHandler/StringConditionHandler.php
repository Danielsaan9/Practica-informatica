<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Override;

class StringConditionHandler implements ConditionHandlerInterface
{
    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::EQUALS,
            ValueOperator::NOT_EQUALS,
            ValueOperator::CONTAINS,
            ValueOperator::NOT_CONTAINS,
            ValueOperator::LENGTH_GREATER_THAN,
            ValueOperator::LENGTH_GREATER_THAN_OR_EQUALS,
            ValueOperator::LENGTH_LESS_THAN,
            ValueOperator::LENGTH_LESS_THAN_OR_EQUALS,
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
        switch ($condition->getValueOperator()) {
            case ValueOperator::LENGTH_GREATER_THAN:
            case ValueOperator::LENGTH_GREATER_THAN_OR_EQUALS:
            case ValueOperator::LENGTH_LESS_THAN:
            case ValueOperator::LENGTH_LESS_THAN_OR_EQUALS:
                // For length operators, we want to display a number input.
                return [
                    'attributes' => [
                        'type' => 'number',
                        'step' => 'any',
                    ],
                ];
            default:
                return [];
        }
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        // Normalize strings.
        $a = strtolower(strval($a));
        $b = strtolower(strval($b));

        return match ($operator) {
            ValueOperator::EQUALS          => $a === $b,
            ValueOperator::NOT_EQUALS      => $a !== $b,
            ValueOperator::CONTAINS        => str_contains($a, $b),
            ValueOperator::NOT_CONTAINS    => !str_contains($a, $b),

            // Length comparison operators
            ValueOperator::LENGTH_GREATER_THAN           => mb_strlen($a) > intval($b),
            ValueOperator::LENGTH_GREATER_THAN_OR_EQUALS => mb_strlen($a) >= intval($b),
            ValueOperator::LENGTH_LESS_THAN              => mb_strlen($a) < intval($b),
            ValueOperator::LENGTH_LESS_THAN_OR_EQUALS    => mb_strlen($a) <= intval($b),

            // Unsupported operators
            default => false,
        };
    }
}
