<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

class QuestionTypeSelectableExtraDataConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const OPTIONS = "options";

    public function __construct(
        private array $options,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            options: $data[self::OPTIONS] ?? [],
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::OPTIONS => $this->options,
        ];
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
