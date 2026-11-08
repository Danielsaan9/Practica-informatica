<?php

namespace Glpi\Form\Destination\CommonITILField;

enum Category: string
{
    case PROPERTIES       = 'properties';
    case ACTORS           = 'actors';
    case TIMELINE         = 'timeline';
    case SERVICE_LEVEL    = 'service_level';
    case ASSOCIATED_ITEMS = 'associated_items';
    case ANALYSIS         = 'analysis';
    case PLANS            = 'plans';

    public function getLabel(): string
    {
        return match ($this) {
            self::PROPERTIES       => __("Properties"),
            self::ACTORS           => __("Actors"),
            self::TIMELINE         => __("Timeline"),
            self::SERVICE_LEVEL    => __("Service levels"),
            self::ASSOCIATED_ITEMS => __("Associated items"),
            self::ANALYSIS         => __("Analysis"),
            self::PLANS            => __("Plans"),
        };
    }

    public function getWeight(): int
    {
        return match ($this) {
            self::PROPERTIES       => 10,
            self::ACTORS           => 20,
            self::TIMELINE         => 30,
            self::SERVICE_LEVEL    => 40,
            self::ASSOCIATED_ITEMS => 50,
            self::ANALYSIS         => 60,
            self::PLANS            => 70,
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PROPERTIES       => 'ti ti-alert-circle',
            self::ACTORS           => 'ti ti-user',
            self::TIMELINE         => 'ti ti-messages',
            self::SERVICE_LEVEL    => 'ti ti-stopwatch',
            self::ASSOCIATED_ITEMS => 'ti ti-link',
            self::ANALYSIS         => 'ti ti-eyeglass',
            self::PLANS            => 'ti ti-checkup-list',
        };
    }
}
