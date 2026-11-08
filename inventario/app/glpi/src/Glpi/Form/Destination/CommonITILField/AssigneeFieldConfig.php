<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Destination\HasFieldWithQuestionId;
use Override;

#[HasFieldWithQuestionId(self::SPECIFIC_QUESTION_IDS, is_array: true)]
final class AssigneeFieldConfig extends ITILActorFieldConfig
{
    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            strategies: self::deserializeStrategies($data, ITILActorFieldStrategy::FROM_TEMPLATE),
            specific_itilactors_ids: $data[self::SPECIFIC_ITILACTORS_IDS] ?? [],
            specific_question_ids: $data[self::SPECIFIC_QUESTION_IDS] ?? [],
        );
    }
}
