<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\NumberConditionHandler;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Override;

final class QuestionTypeNumber extends AbstractQuestionTypeShortAnswer implements UsedAsCriteriaInterface, CustomMandatoryMessageInterface
{
    #[Override]
    public function getInputType(): string
    {
        return 'number';
    }

    #[Override]
    public function getName(): string
    {
        return __("Number");
    }

    #[Override]
    public function getIcon(): string
    {
        return 'ti ti-number-123';
    }

    #[Override]
    public function getWeight(): int
    {
        return 30;
    }

    #[Override]
    public function getInputAttributes(): array
    {
        return ['step' => 'any'];
    }

    #[Override]
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array {
        return array_merge(parent::getConditionHandlers($question_config), [new NumberConditionHandler()]);
    }

    #[Override]
    public function getCustomMandatoryErrorMessage(): string
    {
        // On some browsers, filling text into a `number` input is allowed but
        // the payload will be an empty string on the backend.
        // In this case, the default mandatory message is not clear for the
        // user because the input is filled on the client.
        // The server has no idea this is the case because it receives an
        // empty string.
        // The simplest way to deal with this is to use a generic message that
        // work for both cases (missing or wrong value).
        return __('Please enter a valid number');
    }
}
