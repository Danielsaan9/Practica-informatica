<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Glpi\Form\Migration\ConditionHandlerDataConverterInterface;
use Glpi\Form\Migration\UnresolvedConditionValueException;
use Glpi\Form\QuestionType\QuestionTypeSelectableExtraDataConfig;
use Override;

final class SingleChoiceFromValuesConditionHandler implements
    ConditionHandlerInterface,
    ConditionHandlerDataConverterInterface
{
    public function __construct(
        private array $values,
    ) {}

    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::EQUALS,
            ValueOperator::NOT_EQUALS,
            ValueOperator::LESS_THAN,
            ValueOperator::LESS_THAN_OR_EQUALS,
            ValueOperator::GREATER_THAN,
            ValueOperator::GREATER_THAN_OR_EQUALS,
        ];
    }

    #[Override]
    public function getTemplate(): string
    {
        return '/pages/admin/form/condition_handler_templates/dropdown.html.twig';
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return ['values' => $this->values];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        // Replace UUIDs by real values for less/greater than
        if (
            \in_array($operator, [
                ValueOperator::LESS_THAN,
                ValueOperator::LESS_THAN_OR_EQUALS,
                ValueOperator::GREATER_THAN,
                ValueOperator::GREATER_THAN_OR_EQUALS,
            ], true)
            && $config instanceof QuestionTypeSelectableExtraDataConfig
        ) {
            [$a, $b] = $this->mapSelectableValuesToRealValues($a, $b, $config);
        }

        // Normalize values as strings.
        if (is_array($a)) {
            $a = array_pop($a);
        }
        if (is_array($b)) {
            $b = array_pop($b);
        }
        $a = strtolower(strval($a));
        $b = strtolower(strval($b));

        return match ($operator) {
            ValueOperator::EQUALS                 => $a === $b,
            ValueOperator::NOT_EQUALS             => $a !== $b,
            ValueOperator::LESS_THAN              => $a < $b,
            ValueOperator::LESS_THAN_OR_EQUALS    => $a <= $b,
            ValueOperator::GREATER_THAN           => $a > $b,
            ValueOperator::GREATER_THAN_OR_EQUALS => $a >= $b,

            // Unsupported operators
            default => false,
        };
    }

    #[Override]
    public function convertConditionValue(string $value): int
    {
        $index = array_search($value, $this->values, true);
        if ($index === false) {
            throw new UnresolvedConditionValueException();
        }

        return (int) $index;
    }

    /**
     * @return array{string, string}
     */
    private function mapSelectableValuesToRealValues(
        mixed $answer,
        mixed $condition_value,
        QuestionTypeSelectableExtraDataConfig $config,
    ): array {
        $options = $config->getOptions();

        if (is_array($answer)) {
            $answer = array_pop($answer);
        }
        $answer = $options[$answer] ?? $answer;

        if (is_array($condition_value)) {
            $condition_value = array_pop($condition_value);
        }
        $condition_value = $options[$condition_value] ?? $condition_value;

        return [$answer, $condition_value];
    }
}
