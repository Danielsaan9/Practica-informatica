<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

class QuestionTypeItemExtraDataConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const ITEMTYPE             = "itemtype";
    public const ROOT_ITEMS_ID        = "root_items_id";
    public const SUBTREE_DEPTH        = "subtree_depth";
    public const SELECTABLE_TREE_ROOT = "selectable_tree_root";
    public const IS_MULTIPLE_ITEMS    = "is_multiple_items";

    public function __construct(
        private ?string $itemtype          = null,
        private int $root_items_id         = 0,
        private int $subtree_depth         = 0,
        private bool $selectable_tree_root = false,
        private bool $is_multiple_items    = false,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            itemtype            : $data[self::ITEMTYPE] ?? null,
            root_items_id       : $data[self::ROOT_ITEMS_ID] ?? 0,
            subtree_depth       : $data[self::SUBTREE_DEPTH] ?? 0,
            selectable_tree_root: $data[self::SELECTABLE_TREE_ROOT] ?? false,
            is_multiple_items   : $data[self::IS_MULTIPLE_ITEMS] ?? false,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::ITEMTYPE             => $this->itemtype,
            self::ROOT_ITEMS_ID        => $this->root_items_id,
            self::SUBTREE_DEPTH        => $this->subtree_depth,
            self::SELECTABLE_TREE_ROOT => $this->selectable_tree_root,
            self::IS_MULTIPLE_ITEMS    => $this->is_multiple_items,
        ];
    }

    public function getItemtype(): ?string
    {
        return $this->itemtype;
    }

    public function getRootItemsId(): int
    {
        return $this->root_items_id;
    }

    public function getSubtreeDepth(): int
    {
        return $this->subtree_depth;
    }

    public function isSelectableTreeRoot(): bool
    {
        return $this->selectable_tree_root;
    }

    public function isMultipleItems(): bool
    {
        return $this->is_multiple_items;
    }
}
