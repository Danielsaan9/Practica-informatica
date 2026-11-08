<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\SingleChoiceFromValuesConditionHandler;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Glpi\Form\Question;
use InvalidArgumentException;
use Override;

final class QuestionTypeRadio extends AbstractQuestionTypeSelectable implements UsedAsCriteriaInterface
{
    #[Override]
    public function getInputType(?Question $question): string
    {
        return 'radio';
    }

    #[Override]
    public function getCategory(): QuestionTypeCategoryInterface
    {
        return QuestionTypeCategory::RADIO;
    }

    #[Override]
    protected function getExtraInputAttributes(): array
    {
        return ['data-glpi-form-radio-uncheckable' => ''];
    }

    #[Override]
    public function allowsMultipleDefaultValues(?Question $question): bool
    {
        return false;
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
            [new SingleChoiceFromValuesConditionHandler($question_config->getOptions())]
        );
    }
}
