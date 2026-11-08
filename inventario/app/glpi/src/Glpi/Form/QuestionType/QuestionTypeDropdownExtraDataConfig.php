<?php

namespace Glpi\Form\QuestionType;

use Override;

final class QuestionTypeDropdownExtraDataConfig extends QuestionTypeSelectableExtraDataConfig
{
    // Unique reference to hardcoded name used for serialization
    public const IS_MULTIPLE_DROPDOWN = "is_multiple_dropdown";

    public function __construct(
        array $options,
        private bool $is_multiple_dropdown = false,
    ) {
        parent::__construct(options: $options);
    }

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            options: $data[self::OPTIONS] ?? [],
            is_multiple_dropdown: $data[self::IS_MULTIPLE_DROPDOWN] ?? false,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                self::IS_MULTIPLE_DROPDOWN => $this->is_multiple_dropdown,
            ]
        );
    }

    public function isMultipleDropdown(): bool
    {
        return $this->is_multiple_dropdown;
    }
}
