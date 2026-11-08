<?php

namespace Glpi\Knowbase\Aside;

/**
 * One page of the aside search results.
 */
final class SearchResults
{
    /**
     * @param SearchResult[] $results
     * @param int|null       $next_offset Offset to request the next page with,
     *                                    null when this is the last one.
     */
    public function __construct(
        private readonly array $results,
        private readonly ?int $next_offset = null,
    ) {}

    /** @return SearchResult[] */
    public function getResults(): array
    {
        return $this->results;
    }

    public function getNextOffset(): ?int
    {
        return $this->next_offset;
    }
}
