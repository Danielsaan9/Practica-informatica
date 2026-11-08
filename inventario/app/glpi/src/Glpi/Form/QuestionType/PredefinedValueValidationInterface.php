<?php

namespace Glpi\Form\QuestionType;

use Glpi\Form\Question;

/**
 * Question types whose predefined values (set through GET parameters) depend on
 * the question configuration must implement this interface.
 *
 * An invalid predefined value is discarded, leaving the default value that was
 * configured in the form editor untouched.
 */
interface PredefinedValueValidationInterface
{
    /**
     * Check whether a formatted predefined value can be applied to a question.
     *
     * @param string   $value    The value returned by `formatPredefinedValue()`
     * @param Question $question The question the value would be applied to
     */
    public function isValidPredefinedValue(string $value, Question $question): bool;
}
