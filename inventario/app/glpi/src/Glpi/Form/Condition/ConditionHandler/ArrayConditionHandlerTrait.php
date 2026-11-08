<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\Form\Condition\ValueOperator;

trait ArrayConditionHandlerTrait
{
    protected function getSupportedArrayValueOperators(): array
    {
        return [
            ValueOperator::EQUALS,
            ValueOperator::NOT_EQUALS,
            ValueOperator::CONTAINS,
            ValueOperator::NOT_CONTAINS,
        ];
    }

    protected function applyArrayValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
    ): bool {
        if (!is_array($a) || !is_array($b)) {
            return false;
        }

        // HTML forms might produce default empty values for optional inputs like
        // checkboxes, we'll need to remove them
        $a = array_filter($a, fn($v) => $v !== "");

        // Normalize values
        $a = array_values($a);
        $b = array_values($b);
        sort($a);
        sort($b);

        return match ($operator) {
            ValueOperator::EQUALS       => $a == $b,
            ValueOperator::NOT_EQUALS   => $a != $b,
            ValueOperator::CONTAINS     => empty(array_diff($b, $a)),
            ValueOperator::NOT_CONTAINS => !empty(array_diff($b, $a)),

            // Unsupported operators
            default => false,
        };
    }
}
