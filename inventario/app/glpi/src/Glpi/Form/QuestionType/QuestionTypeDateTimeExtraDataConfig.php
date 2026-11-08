<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

final class QuestionTypeDateTimeExtraDataConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const IS_DEFAULT_VALUE_CURRENT_TIME = "is_default_value_current_time";
    public const IS_DATE_ENABLED               = "is_date_enabled";
    public const IS_TIME_ENABLED               = "is_time_enabled";

    public function __construct(
        private bool $is_default_value_current_time = false,
        private bool $is_date_enabled = true,
        private bool $is_time_enabled = false,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            is_default_value_current_time: $data[self::IS_DEFAULT_VALUE_CURRENT_TIME] ?? false,
            is_date_enabled              : $data[self::IS_DATE_ENABLED] ?? true,
            is_time_enabled              : $data[self::IS_TIME_ENABLED] ?? false,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::IS_DEFAULT_VALUE_CURRENT_TIME => (int) $this->is_default_value_current_time,
            self::IS_DATE_ENABLED               => (int) $this->is_date_enabled,
            self::IS_TIME_ENABLED               => (int) $this->is_time_enabled,
        ];
    }

    public function isDefaultValueCurrentTime(): bool
    {
        return $this->is_default_value_current_time;
    }

    public function isDateEnabled(): bool
    {
        return $this->is_date_enabled;
    }

    public function isTimeEnabled(): bool
    {
        return $this->is_time_enabled;
    }
}
