<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

final class QuestionTypeItemDefaultValueConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const KEY_ITEMS_IDS = "items_ids";

    /**
     * @param array<int|string>|null $items_ids Must accept a string because the foreign key handler
     *                              replaces the ID with the item name during serialization.
     */
    public function __construct(
        private ?array $items_ids = null
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $items_ids = $data[self::KEY_ITEMS_IDS] ?? null;
        if ($items_ids !== null && !is_array($items_ids)) {
            $items_ids = [$items_ids];
        }

        return new self(items_ids: $items_ids);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::KEY_ITEMS_IDS => $this->items_ids,
        ];
    }

    /** @return array<int|string>|null */
    public function getItemsIds(): ?array
    {
        return $this->items_ids;
    }
}
