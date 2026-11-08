<?php

namespace Glpi\Form\QuestionType;

use CommonITILObject;
use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\UrgencyConditionHandler;
use Glpi\Form\Condition\ConditionValueTransformerInterface;
use Glpi\Form\Condition\UsedAsCriteriaInterface;
use Glpi\Form\Migration\FormQuestionDataConverterInterface;
use Glpi\Form\Question;
use Glpi\Urgency;
use Override;

final class QuestionTypeUrgency extends AbstractQuestionType implements UsedAsCriteriaInterface, FormQuestionDataConverterInterface, ConditionValueTransformerInterface
{
    /**
     * Retrieve the default value for the urgency question type
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
                urgency_levels,
                '',
                {
                    'init'                : init,
                    'no_label'            : true,
                    'display_emptychoice' : true,
                    'mb'                  : '',
                }
            ) }}
TWIG;

        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'init'               => $question != null,
            'value'              => $this->getDefaultValue($question),
            'urgency_levels'     => Urgency::getEnabledUrgencyValuesForDropdown(),
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
            urgency_levels,
            '',
            {
                'no_label'            : true,
                'display_emptychoice' : true,
                'aria_label'          : label,
                'mb'                  : '',
            }
        ) }}
TWIG;

        $twig = TemplateRenderer::getInstance();
        return $twig->renderFromStringTemplate($template, [
            'value'              => $this->getDefaultValue($question),
            'question'           => $question,
            'urgency_levels'     => Urgency::getEnabledUrgencyValuesForDropdown(),
            'label' => $question->fields['name'],
        ]);
    }

    #[Override]
    public function formatRawAnswer(mixed $answer, Question $question): string
    {
        return CommonITILObject::getUrgencyName($answer);
    }

    #[Override]
    public function getCategory(): QuestionTypeCategoryInterface
    {
        return QuestionTypeCategory::URGENCY;
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
            'very low', 'verylow' => "1",
            'low' => "2",
            'medium' => "3",
            'high' => "4",
            'very high', 'veryhigh' => "5",
            default => null,
        };
    }

    #[Override]
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array {
        return array_merge(parent::getConditionHandlers($question_config), [new UrgencyConditionHandler()]);
    }

    #[Override]
    public function transformConditionValueForComparisons(mixed $value, ?JsonFieldInterface $question_config): string
    {
        if (empty($value)) {
            return '';
        }

        return strval($value);
    }

    #[Override]
    public function convertDefaultValue(array $rawData): ?int
    {
        if (!isset($rawData['default_values'])) {
            return null;
        }

        return (int) $rawData['default_values'];
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
}
