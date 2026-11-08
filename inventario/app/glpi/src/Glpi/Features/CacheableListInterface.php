<?php

namespace Glpi\Features;

/**
 * Objects lists that can be cached
 **/
interface CacheableListInterface
{
    /**
     * Get cache key
     *
     * @return string
     */
    public function getListCacheKey(): string;

    /**
     * Clean cache
     *
     * @return void
     */
    public function invalidateListCache(): void;
}
