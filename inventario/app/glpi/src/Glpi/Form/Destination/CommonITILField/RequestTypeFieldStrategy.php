<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\AnswersSet;
use Glpi\Form\QuestionType\QuestionTypeRequestType;

enum RequestTypeFieldStrategy: string
{
    case FROM_TEMPLATE = 'from_template';
    case SPECIFIC_VALUE = 'specific_value';
    case SPECIFIC_ANSWER = 'specific_answer';
    case LAST_VALID_ANSWER = 'last_valid_answer';

    public function getLabel(): string
    {
        return match ($this) {
            self::FROM_TEMPLATE     => __("From template"),
            self::SPECIFIC_VALUE    => __("Specific request type"),
            self::SPECIFIC_ANSWER   => __("Answer from a specific question"),
            self::LAST_VALID_ANSWER => __('Answer to last "Request type" question'),
        };
    }

    public function getRequestType(
        RequestTypeFieldConfig $config,
        AnswersSet $answers_set,
    ): ?int {
        return match ($this) {
            self::FROM_TEMPLATE => null, // Let the template apply its default value by itself.
            self::SPECIFIC_VALUE => $config->getSpecificRequestType(),
            self::SPECIFIC_ANSWER => $this->getRequestTypeForSpecificAnswer(
                $config->getSpecificQuestionId(),
                $answers_set
            ),
            self::LAST_VALID_ANSWER => $this->getRequestTypeForLastValidAnswer($answers_set),
        };
    }

    private function getRequestTypeForSpecificAnswer(
        ?int $question_id,
        AnswersSet $answers_set,
    ): ?int {
        if ($question_id === null) {
            return null;
        }

        $answer = $answers_set->getAnswerByQuestionId($question_id);
        if ($answer === null) {
            return null;
        }

        $value = $answer->getRawAnswer();
        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }

    private function getRequestTypeForLastValidAnswer(
        AnswersSet $answers_set,
    ): ?int {
        $valid_answers = array_filter(
            $answers_set->getAnswersByType(
                QuestionTypeRequestType::class
            ),
            fn($answer) => is_numeric($answer->getRawAnswer())
                && (int) $answer->getRawAnswer() > 0
        );

        if (count($valid_answers) == 0) {
            return null;
        }

        $answer = end($valid_answers);
        $value = $answer->getRawAnswer();
        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }
}
