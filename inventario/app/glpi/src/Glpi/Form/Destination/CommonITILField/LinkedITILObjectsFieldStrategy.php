<?php

namespace Glpi\Form\Destination\CommonITILField;

use CommonDBTM;
use CommonITILObject;
use Glpi\Form\AnswersSet;

enum LinkedITILObjectsFieldStrategy: string
{
    case SPECIFIC_DESTINATIONS  = 'specific_destinations';
    case SPECIFIC_VALUES        = 'specific_values';
    case SPECIFIC_ANSWERS       = 'specific_answers';

    public function getLabel(): string
    {
        return match ($this) {
            self::SPECIFIC_DESTINATIONS  => __("An other destination of this form"),
            self::SPECIFIC_VALUES        => __("An existing assistance object"),
            self::SPECIFIC_ANSWERS       => __("Assistance object from specific questions"),
        };
    }

    /**
     * Get the linked ITIL objects based on the strategy and configuration.
     *
     * @param LinkedITILObjectsFieldStrategyConfig $config
     * @param AnswersSet $answers_set
     * @param array<int, CommonDBTM[]> $created_objects Array mapping destination_id to created objects
     *
     * @return array|null
     */
    public function getLinkedITILObjects(
        LinkedITILObjectsFieldStrategyConfig $config,
        AnswersSet $answers_set,
        array $created_objects = []
    ): ?array {
        return match ($this) {
            self::SPECIFIC_DESTINATIONS => $this->getLinkedITILObjectsFromSpecificDestinations(
                $config->getLinktype(),
                $config->getSpecificDestinationIds(),
                $created_objects,
            ),
            self::SPECIFIC_VALUES => $this->getLinkedITILObjectsFromSpecificValues(
                $config->getLinktype(),
                $config->getSpecificItilObjectItemtype(),
                $config->getSpecificItilObjectItemsId(),
            ),
            self::SPECIFIC_ANSWERS => $this->getLinkedITILObjectsForSpecificAnswers(
                $config->getLinktype(),
                $config->getSpecificQuestionIds(),
                $answers_set
            ),
        };
    }

    private function getLinkedITILObjectsFromSpecificDestinations(
        string $linktype,
        array $specific_destination_ids,
        array $created_objects = []
    ): ?array {
        if ($specific_destination_ids === []) {
            return null;
        }

        $linked_itil_objects = [];
        foreach ($specific_destination_ids as $destination_id) {
            if (!isset($created_objects[$destination_id])) {
                continue;
            }

            foreach ($created_objects[$destination_id] as $item) {
                if ($item instanceof CommonITILObject) {
                    $linked_itil_objects[] = [
                        'itemtype' => $item::class,
                        'items_id' => $item->getID(),
                        'linktype' => $linktype,
                    ];
                }
            }
        }

        return $linked_itil_objects ?: null;
    }

    private function getLinkedITILObjectsFromSpecificValues(
        string $linktype,
        ?string $itemtype,
        ?int $items_id
    ): ?array {
        if (
            empty($itemtype)
            || !is_a($itemtype, CommonITILObject::class, true)
            || empty($items_id)
        ) {
            return null;
        }

        return [
            [
                'itemtype' => $itemtype,
                'items_id' => $items_id,
                'linktype' => $linktype,
            ],
        ];
    }

    private function getLinkedITILObjectsForSpecificAnswers(
        string $linktype,
        array $specific_question_ids,
        AnswersSet $answers_set
    ): ?array {
        if ($specific_question_ids === []) {
            return null;
        }

        $linked_itil_objects = [];
        foreach ($specific_question_ids as $question_id) {
            if ($question_id === null) {
                continue;
            }

            $answer = $answers_set->getAnswerByQuestionId($question_id);
            if ($answer === null) {
                return null;
            }

            $value = $answer->getRawAnswer();

            $linked_itil_objects[] = [
                'itemtype' => $value['itemtype'],
                'items_id' => $value['items_id'],
                'linktype' => $linktype,
            ];
        }

        return $linked_itil_objects ?: null;
    }
}
