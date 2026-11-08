<?php

namespace Glpi\Form\Condition;

use JsonSerializable;
use Override;

final class EngineValidationOutput implements JsonSerializable
{
    /** @var array<int, ConditionData[]> */
    private array $questions_validation = [];

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'questions_validation' => $this->questions_validation,
        ];
    }

    public function setQuestionValidation(int $question_id, array $not_met_conditions): void
    {
        $this->questions_validation[$question_id] = $not_met_conditions;
    }

    /**
     * @param int $question_id
     * @return ConditionData[]
     */
    public function getQuestionValidation(int $question_id): array
    {
        if (!isset($this->questions_validation[$question_id])) {
            return [];
        }

        return $this->questions_validation[$question_id];
    }

    public function isQuestionValid(int $question_id): bool
    {
        if (!isset($this->questions_validation[$question_id])) {
            return false;
        }

        return empty($this->questions_validation[$question_id]);
    }
}
