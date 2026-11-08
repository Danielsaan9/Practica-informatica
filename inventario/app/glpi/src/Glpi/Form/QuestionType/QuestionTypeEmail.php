<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\StringConditionHandler;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Glpi\Form\Question;
use Glpi\Form\ValidationResult;
use GLPIMailer;
use Override;
use Session;

final class QuestionTypeEmail extends AbstractQuestionTypeShortAnswer implements UsedAsCriteriaInterface, QuestionTypeValidationInterface
{
    #[Override]
    public function getInputType(): string
    {
        return 'email';
    }

    #[Override]
    public function getName(): string
    {
        return _n('Email', 'Emails', Session::getPluralNumber());
    }

    #[Override]
    public function getIcon(): string
    {
        return 'ti ti-mail';
    }

    #[Override]
    public function getWeight(): int
    {
        return 20;
    }

    #[Override]
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array {
        return array_merge(parent::getConditionHandlers($question_config), [new StringConditionHandler()]);
    }

    #[Override]
    public function validateAnswer(
        Question $question,
        mixed $answer
    ): ValidationResult {
        $result = new ValidationResult(true);
        if (!is_string($answer)) {
            // Should never happen
            $result->addError($question, __("Unexpected value"));
            return $result;
        }

        if (!GLPIMailer::validateAddress($answer)) {
            $result->addError($question, __("Please enter a valid email"));
            return $result;
        }

        return $result;
    }
}
