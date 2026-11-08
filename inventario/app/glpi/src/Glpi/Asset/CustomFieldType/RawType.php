<?php

namespace Glpi\Asset\CustomFieldType;

use Glpi\Asset\CustomFieldOption\BooleanOption;
use Glpi\Asset\CustomFieldOption\ProfileRestrictOption;

/**
 * Special type used for native fields that don't fit in any other type, usually because they output raw HTML, but should work with custom assets.
 * This type only exposes options to make the field show in full width, and to hide it for specific profiles.
 */
class RawType extends AbstractType
{
    public static function isAllowedForCustomFields(): bool
    {
        return false;
    }

    public static function getName(): string
    {
        return '';
    }

    public function getOptions(): array
    {
        return [
            new BooleanOption($this->custom_field, 'full_width', __('Full width'), false),
            new ProfileRestrictOption($this->custom_field, 'hidden', __('Hidden for these profiles'), false),
        ];
    }

    public function getFormInput(string $name, mixed $value, ?string $label = null, bool $for_default = false): string
    {
        return '';
    }
}
