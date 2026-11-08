<?php

namespace Glpi\Knowbase\Aside;

final class Article
{
    /** @var Article[] */
    private array $children = [];

    /**
     * @param bool $has_children    Whether the article has children to show,
     *                              whether or not they are loaded.
     * @param bool $children_loaded Whether `getChildren()` holds them. A folded
     *                              article renders without its children, which
     *                              the aside fetches when the reader unfolds it.
     * @param bool $is_root         Whether this is the root article, which the
     *                              aside renders as the label of its children.
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $illustration,
        public readonly string $link,
        public readonly bool $is_current = false,
        public readonly bool $collapsed = false,
        public readonly bool $has_children = false,
        public readonly bool $children_loaded = true,
        public readonly bool $is_root = false,
    ) {}

    public function addChild(self $child): void
    {
        $this->children[] = $child;
    }

    /** @return Article[] */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return $this->has_children || $this->children !== [];
    }
}
