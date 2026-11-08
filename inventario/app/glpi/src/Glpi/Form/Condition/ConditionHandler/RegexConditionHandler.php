<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ConditionValueTransformerInterface;
use Glpi\Form\Condition\ValueOperator;
use Glpi\Form\QuestionType\QuestionTypeInterface;
use Override;

class RegexConditionHandler implements ConditionHandlerInterface
{
    public function __construct(
        private QuestionTypeInterface $questionType,
        private ?JsonFieldInterface $question_config
    ) {}

    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::MATCH_REGEX,
            ValueOperator::NOT_MATCH_REGEX,
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
            'placeholder' => '/regex/',
        ];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        if ($this->questionType instanceof ConditionValueTransformerInterface) {
            $a = $this->questionType->transformConditionValueForComparisons($a, $this->question_config);
        }

        $a = is_array($a) ? $a : [$a];
        $b = strval($b);

        // Handle empty array input: empty values don't match any regex,
        // so MATCH_REGEX returns false and NOT_MATCH_REGEX returns true
        if ($a === []) {
            return $operator === ValueOperator::NOT_MATCH_REGEX;
        }

        $matches_count = 0;
        foreach ($a as $value) {
            $value = strval($value);

            // Note: we do not want to throw warnings here if an invalid regex
            // is configured by the user.
            // There is no clean way to test that a regex is valid in PHP,
            // therefore the simplest way to deal with that is to ignore
            // warnings using the "@" prefix.
            $result = @preg_match($b, $value); // @phpstan-ignore theCodingMachineSafe.function

            if ($result) {
                $matches_count++;
            }
        }

        // MATCH_REGEX: returns true if ALL values match the regex
        // NOT_MATCH_REGEX: returns true if NO values match the regex
        if ($operator === ValueOperator::MATCH_REGEX) {
            return $matches_count === count($a);
        } else {
            return $matches_count === 0;
        }
    }
}
