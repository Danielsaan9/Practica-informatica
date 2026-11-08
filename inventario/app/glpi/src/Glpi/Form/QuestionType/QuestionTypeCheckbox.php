<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\MultipleChoiceFromValuesConditionHandler;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Glpi\Form\Question;
use InvalidArgumentException;
use Override;

final class QuestionTypeCheckbox extends AbstractQuestionTypeSelectable implements UsedAsCriteriaInterface
{
    #[Override]
    public function getInputType(?Question $question): string
    {
        return 'checkbox';
    }

    #[Override]
    public function getCategory(): QuestionTypeCategoryInterface
    {
        return QuestionTypeCategory::CHECKBOX;
    }

    #[Override]
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array {
        if (!$question_config instanceof QuestionTypeSelectableExtraDataConfig) {
            throw new InvalidArgumentException();
        }

        return array_merge(
            parent::getConditionHandlers($question_config),
            [new MultipleChoiceFromValuesConditionHandler($question_config->getOptions())]
        );
    }
}
