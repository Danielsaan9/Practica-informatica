<?php

namespace Glpi\Form\QuestionType;

use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\RequestTypeConditionHandler;
use Glpi\Form\Condition\ConditionValueTransformerInterface;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Glpi\Form\Migration\FormQuestionDataConverterInterface;
use Glpi\Form\Question;
use Override;
use Ticket;

final class QuestionTypeRequestType extends AbstractQuestionType implements UsedAsCriteriaInterface, FormQuestionDataConverterInterface, ConditionValueTransformerInterface
{
    /**
     * Retrieve the default value for the request type question type
     *
     * @param Question|null $question The question to retrieve the default value from
     * @return int
     */
    public function getDefaultValue(?Question $question): int
    {
        if (
            $question !== null
            && isset($question->fields['default_value'])
        ) {
            if (is_int($question->fields['default_value'])) {
                return $question->fields['default_value'];
            } elseif (is_numeric($question->fields['default_value'])) {
                return (int) $question->fields['default_value'];
            }
        }

        return 0;
    }

    #[Override]
    public function renderAdministrationTemplate(?Question $question): string
    {
        $template = <<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}

            {{ fields.dropdownArrayField(
                'default_value',
                value,
                request_types,
                '',
                {
                    'init'               : init,
                    'no_label'           : true,
                    'display_emptychoice': true,
                    'mb'                 : '',
                }
            ) }}
TWIG;

        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'init'               => $question != null,
            'value'              => $this->getDefaultValue($question),
            'request_types'      => Ticket::getTypes(),
        ]);
    }

    #[Override]
    public function renderEndUserTemplate(Question $question): string
    {
        $template = <<<TWIG
        {% import 'components/form/fields_macros.html.twig' as fields %}

        {{ fields.dropdownArrayField(
            question.getEndUserInputName(),
            value,
            request_types,
            '',
            {
                'no_label'           : true,
                'display_emptychoice': true,
                'aria_label'         : label,
                'mb'                 : '',
            }
        ) }}
TWIG;

        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'value'              => $this->getDefaultValue($question),
            'question'           => $question,
            'request_types'      => Ticket::getTypes(),
            'label'              => $question->fields['name'],
        ]);
    }

    #[Override]
    public function formatRawAnswer(mixed $answer, Question $question): string
    {
        return Ticket::getTicketTypeName($answer);
    }

    #[Override]
    public function getCategory(): QuestionTypeCategoryInterface
    {
        return QuestionTypeCategory::REQUEST_TYPE;
    }

    #[Override]
    public function isAllowedForUnauthenticatedAccess(): bool
    {
        return true;
    }

    #[Override]
    public function formatPredefinedValue(string $value): ?string
    {
        $value = strtolower($value);

        return match ($value) {
            'incident' => (string) Ticket::INCIDENT_TYPE,
            'request' => (string) Ticket::DEMAND_TYPE,
            default => null,
        };
    }

    #[Override]
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array {
        return array_merge(parent::getConditionHandlers($question_config), [new RequestTypeConditionHandler()]);
    }

    #[Override]
    public function convertDefaultValue(array $rawData): ?int
    {
        return $rawData['default_values'] ?? null;
    }

    #[Override]
    public function convertExtraData(array $rawData): null
    {
        return null;
    }

    #[Override]
    public function getTargetQuestionType(array $rawData): string
    {
        return self::class;
    }


    #[Override]
    public function beforeConversion(array $rawData): void {}

    #[Override]
    public function transformConditionValueForComparisons(mixed $value, ?JsonFieldInterface $question_config): string
    {
        if ($value == 0) {
            return '';
        }

        return $value;
    }
}
