<?php

namespace Glpi\Form\QuestionType;

use Override;

final class QuestionTypeItemDropdownExtraDataConfig extends QuestionTypeItemExtraDataConfig
{
    // Unique reference to hardcoded name used for serialization
    public const CATEGORIES_FILTER = "categories_filter";

    public function __construct(
        private ?string $itemtype          = null,
        private array $categories_filter   = [],
        private int $root_items_id         = 0,
        private int $subtree_depth         = 0,
        private bool $selectable_tree_root = false,
        private bool $is_multiple_items    = false,
    ) {
        parent::__construct(
            itemtype            : $itemtype,
            root_items_id       : $root_items_id,
            subtree_depth       : $subtree_depth,
            selectable_tree_root: $selectable_tree_root,
            is_multiple_items   : $is_multiple_items,
        );
    }

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            itemtype            : $data[self::ITEMTYPE] ?? null,
            categories_filter   : $data[self::CATEGORIES_FILTER] ?? [],
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
            self::CATEGORIES_FILTER    => $this->categories_filter,
            self::ROOT_ITEMS_ID        => $this->root_items_id,
            self::SUBTREE_DEPTH        => $this->subtree_depth,
            self::SELECTABLE_TREE_ROOT => $this->selectable_tree_root,
            self::IS_MULTIPLE_ITEMS    => $this->is_multiple_items,
        ];
    }

    public function getCategoriesFilter(): array
    {
        return $this->categories_filter;
    }
}
