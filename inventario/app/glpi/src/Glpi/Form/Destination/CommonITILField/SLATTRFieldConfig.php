<?php

namespace Glpi\Form\Destination\CommonITILField;

use Override;

final class SLATTRFieldConfig extends SLMFieldConfig
{
    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = SLMFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = SLMFieldStrategy::FROM_TEMPLATE;
        }

        return new self(
            strategy       : $strategy,
            specific_slm_id: $data[self::SLM_ID] ?? null,
            question_id    : $data[self::QUESTION_ID] ?? null,
            time_offset    : $data[self::TIME_OFFSET] ?? null,
            time_definition: $data[self::TIME_DEFINITION] ?? null,
        );
    }
}
