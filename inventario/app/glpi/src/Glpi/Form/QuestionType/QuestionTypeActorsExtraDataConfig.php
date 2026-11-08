<?php

namespace Glpi\Form\QuestionType;

use CommonDBTM;
use Glpi\DBAL\JsonFieldInterface;
use Override;

final class QuestionTypeActorsExtraDataConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const IS_MULTIPLE_ACTORS = "is_multiple_actors";
    public const ENABLED_TYPES = "enabled_types";

    /**
     * @param array<class-string<CommonDBTM>> $enabled_types
     */
    public function __construct(
        private bool $is_multiple_actors = false,
        private ?array $enabled_types = null,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            is_multiple_actors: $data[self::IS_MULTIPLE_ACTORS] ?? false,
            enabled_types     : $data[self::ENABLED_TYPES] ?? null,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::IS_MULTIPLE_ACTORS => $this->is_multiple_actors,
            self::ENABLED_TYPES      => $this->enabled_types,
        ];
    }
    public function isMultipleActors(): bool
    {
        return $this->is_multiple_actors;
    }

    public function isTypeEnabled(string $type): bool
    {
        if ($this->enabled_types === null || $this->enabled_types === []) {
            return true;
        }

        return \in_array($type, $this->enabled_types);
    }
}
