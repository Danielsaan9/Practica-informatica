<?php

namespace Glpi\Form\QuestionType;

use Glpi\Form\Question;
use Glpi\Form\ValidationResult;

/**
 * Questions type that require extra validation must implement this interface
 */
interface QuestionTypeValidationInterface
{
    public function validateAnswer(
        Question $question,
        mixed $answer,
    ): ValidationResult;
}
