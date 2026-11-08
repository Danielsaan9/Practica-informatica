<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ConditionValueTransformerInterface;
use Glpi\Form\Condition\ValueOperator;
use Glpi\Form\QuestionType\QuestionTypeInterface;
use Override;

/**
 * Handler for conditions that check if another form element is empty or not
 */
class EmptyConditionHandler implements ConditionHandlerInterface
{
    public function __construct(
        private QuestionTypeInterface $question_type,
        private ?JsonFieldInterface $question_config
    ) {}

    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::EMPTY,
            ValueOperator::NOT_EMPTY,
        ];
    }

    #[Override]
    public function getTemplate(): null
    {
        // No input field needed for empty conditions
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
        if ($this->question_type instanceof ConditionValueTransformerInterface) {
            $a = $this->question_type->transformConditionValueForComparisons($a, $this->question_config);
        }

        return match ($operator) {
            ValueOperator::EMPTY     => $this->isConsideredEmpty($a),
            ValueOperator::NOT_EMPTY => !$this->isConsideredEmpty($a),

            // Unsupported operators
            default => false,
        };
    }

    /**
     * An answer is empty only when no value was provided.
     *
     * Unlike empty(), a value of 0 or "0" is a real answer (for instance a
     * number question answered with zero) and must not be treated as empty.
     * Question types that use a "no selection" placeholder normalize it to an
     * empty string or an empty array through their condition value transformer
     * before this check runs.
     */
    private function isConsideredEmpty(mixed $value): bool
    {
        return $value === null
            || $value === ''
            || (is_array($value) && count($value) === 0);
    }
}
