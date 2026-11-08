<?php

namespace Glpi\Form\Condition;

use Glpi\Form\Form;
use Glpi\Form\Question;

final class EngineInput
{
    public function __construct(
        private array $answers,
    ) {}

    /**
     * Construct an input using default values from the database.
     * Useful when computing data that was not yet modified by the user.
     */
    public static function fromForm(Form $form): self
    {
        $answers = [];

        // Get questions that can be used as a criteria
        $questions = array_filter(
            $form->getQuestions(),
            fn(Question $q): bool => $q->getQuestionType() instanceof UsedAsCriteriaInterface,
        );

        foreach ($questions as $question) {
            $answers[$question->getID()] = $question->fields['default_value'];
        }

        return new self(answers: $answers);
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }
}
