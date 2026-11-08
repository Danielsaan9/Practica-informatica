<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\StringConditionHandler;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Glpi\Form\Question;
use Glpi\ItemTranslation\Context\TranslationHandler;
use Override;

final class QuestionTypeShortText extends AbstractQuestionTypeShortAnswer implements
    UsedAsCriteriaInterface,
    TranslationAwareQuestionType
{
    #[Override]
    public function getInputType(): string
    {
        return 'text';
    }

    #[Override]
    public function getName(): string
    {
        return __("Text");
    }

    #[Override]
    public function getIcon(): string
    {
        return 'ti ti-text-size';
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }

    #[Override]
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array {
        return array_merge(parent::getConditionHandlers($question_config), [new StringConditionHandler()]);
    }

    #[Override]
    public function listTranslationsHandlers(Question $question): array
    {
        return [
            new TranslationHandler(
                item: $question,
                key: Question::TRANSLATION_KEY_DEFAULT_VALUE,
                name: __('Default value'),
                value: $question->fields['default_value'] ?? '',
            ),
        ];
    }
}
