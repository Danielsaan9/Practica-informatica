<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Override;

class SimpleValueConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const VALUE = 'value';

    public function __construct(
        protected string $value,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            $data[self::VALUE],
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::VALUE => $this->value,
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
