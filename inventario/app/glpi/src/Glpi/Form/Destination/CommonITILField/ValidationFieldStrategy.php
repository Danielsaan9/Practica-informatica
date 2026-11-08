<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\AnswersSet;
use Glpi\Form\QuestionType\QuestionTypeItem;
use ITILValidationTemplate;

enum ValidationFieldStrategy: string
{
    case NO_VALIDATION    = 'no_validation';
    case SPECIFIC_VALUES  = 'specific_values';
    case SPECIFIC_ACTORS  = 'specific_actors';
    case SPECIFIC_ANSWERS = 'specific_answers';

    public function getLabel(): string
    {
        return match ($this) {
            self::NO_VALIDATION    => __("No approval"),
            self::SPECIFIC_VALUES  => __("Specific Approval templates"),
            self::SPECIFIC_ACTORS  => __("Specific actors"),
            self::SPECIFIC_ANSWERS => __("Answer from specific questions"),
        };
    }

    public function getValidation(
        ValidationFieldStrategyConfig $config,
        AnswersSet $answers_set,
    ): ?array {
        return match ($this) {
            self::NO_VALIDATION    => null,
            self::SPECIFIC_VALUES  => $this->getActorsForSpecificValues(
                $config->getSpecificValidationTemplateIds()
            ),
            self::SPECIFIC_ACTORS  => $this->getActorsFromSpecificActors(
                $config->getSpecificActors()
            ),
            self::SPECIFIC_ANSWERS => $this->getActorsForSpecificAnswers(
                $config->getSpecificQuestionIds(),
                $answers_set
            ),
        };
    }

    private function isValidAnswer(mixed $value): bool
    {
        if (is_array($value) && is_array(current($value))) {
            foreach ($value as $item) {
                if (!$this->isValidAnswer($item)) {
                    return false;
                }
            }

            return true;
        }

        return isset($value['itemtype']) && is_string($value['itemtype'])
            && isset($value['items_id']) && is_numeric($value['items_id']);
    }

    private function getActorsForSpecificAnswers(
        array $question_ids,
        AnswersSet $answers_set,
    ): ?array {
        if ($question_ids === []) {
            return null;
        }

        $actors = [];
        foreach ($question_ids as $question_id) {
            $actors_to_add = $this->getActorsForSpecificAnswer($question_id, $answers_set);

            if (is_array($actors_to_add) && is_array(current($actors_to_add))) {
                $actors = array_merge($actors, $actors_to_add);
            } elseif ($actors_to_add !== null) {
                $actors[] = $actors_to_add;
            }
        }

        return $actors;
    }

    /**
     * Get actors for specific validation templates
     *
     * @param ?array<int> $specific_values Validation template IDs
     */
    private function getActorsForSpecificValues(
        ?array $specific_values,
    ): ?array {
        if (empty($specific_values)) {
            return null;
        }

        $actors = [];
        foreach ($specific_values as $validation_template_id) {
            $validation_template = ITILValidationTemplate::getById($validation_template_id);
            if (!$validation_template) {
                continue;
            }

            $actors[] = [
                '_template_id' => $validation_template->getId(),
            ];
        }

        return $actors;
    }

    private function getActorsForSpecificAnswer(
        ?int $question_id,
        AnswersSet $answers_set,
    ): ?array {
        if ($question_id === null) {
            return null;
        }

        $answer = $answers_set->getAnswerByQuestionId($question_id);
        if ($answer === null) {
            return null;
        }

        $value = $answer->getRawAnswer();

        $raw_question_type = $answer->getRawType();
        if (is_a($raw_question_type, QuestionTypeItem::class, true)) {
            if (is_array($value) && isset($value['itemtype']) && isset($value['items_ids'])) {
                $itemtype = $value['itemtype'];
                $items_ids = $value['items_ids'];
                if (!is_array($items_ids)) {
                    $items_ids = [$items_ids];
                }

                $value = [];
                foreach ($items_ids as $items_id) {
                    if (is_numeric($items_id)) {
                        $value[] = [
                            'itemtype' => $itemtype,
                            'items_id' => $items_id,
                        ];
                    }
                }
            }
        }

        if (!$this->isValidAnswer($value)) {
            return null;
        }

        return $value;
    }

    private function getActorsFromSpecificActors(
        array $specific_actors,
    ): ?array {
        if ($specific_actors === []) {
            return null;
        }

        $actors = [];
        foreach ($specific_actors as $itemtype => $actor_ids) {
            foreach ($actor_ids as $actor_id) {
                $actors[] = [
                    'itemtype' => $itemtype,
                    'items_id' => $actor_id,
                ];
            }
        }

        return $actors;
    }
}
