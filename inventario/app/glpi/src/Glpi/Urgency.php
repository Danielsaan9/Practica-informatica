<?php

namespace Glpi;

// TODO: refactor usage of raw urgency int values with usage of this enum instead
enum Urgency: int
{
    case VERY_LOW = 1;
    case LOW = 2;
    case MEDIUM = 3;
    case HIGH = 4;
    case VERY_HIGH = 5;

    /**
     * @return array<int, string>
     */
    public static function getUrgencyValuesForDropdown(): array
    {
        return [
            self::VERY_LOW->value  => __('Very low'),
            self::LOW->value       => __('Low'),
            self::MEDIUM->value    => __('Medium'),
            self::HIGH->value      => __('High'),
            self::VERY_HIGH->value => __('Very high'),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function getEnabledUrgencyValuesForDropdown(): array
    {
        global $CFG_GLPI;

        return array_filter(
            array_combine(
                array_map(fn($case) => $case->value, self::cases()),
                array_map(fn($case) => \CommonITILObject::getUrgencyName($case->value), self::cases()),
            ),
            fn($key) => (($CFG_GLPI['urgency_mask'] & (1 << $key)) > 0),
            ARRAY_FILTER_USE_KEY
        );
    }
}
