<?php

namespace Glpi\Knowbase\Aside;

/**
 * A single article matched by the aside search, as one row of the results list.
 */
final class SearchResult
{
    /**
     * @param string $excerpt Plain text preview of the article's content, see
     *                        `SearchResultsBuilder::buildExcerpt()`.
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $illustration,
        public readonly string $link,
        public readonly string $excerpt,
        public readonly bool $is_current = false,
    ) {}
}
