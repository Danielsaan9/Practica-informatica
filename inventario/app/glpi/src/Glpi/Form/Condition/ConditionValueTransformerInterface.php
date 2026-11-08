<?php

namespace Glpi\Form\Condition;

use Glpi\DBAL\JsonFieldInterface;

/**
 * Items that implements this interface can transform their values for use in condition comparisons.
 */
interface ConditionValueTransformerInterface
{
    /**
     * Transform the value of the answer to a format suitable for condition comparisons.
     *
     * @param mixed $value The value of the answer
     *
     * @return string|array<string> A string or an array of strings for comparison
     */
    public function transformConditionValueForComparisons(mixed $value, ?JsonFieldInterface $question_config): string|array;
}
