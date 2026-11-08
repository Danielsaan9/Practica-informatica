<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

final class QuestionTypeUserDevicesConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const IS_MULTIPLE_DEVICES = "is_multiple_devices";

    public function __construct(
        private bool $is_multiple_devices = false,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            is_multiple_devices: $data[self::IS_MULTIPLE_DEVICES] ?? false,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::IS_MULTIPLE_DEVICES => $this->is_multiple_devices,
        ];
    }

    public function isMultipleDevices(): bool
    {
        return $this->is_multiple_devices;
    }
}
