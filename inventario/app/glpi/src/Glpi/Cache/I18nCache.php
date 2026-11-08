<?php

namespace Glpi\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * Cache class used to be able to use a symfony/cache instance in overrided laminas-i18n Translator service.
 *
 * /!\ For internal use only.
 */
class I18nCache
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function setItem($key, $value)
    {
        return $this->cache->set($key, $value);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function removeItem($key)
    {
        return $this->cache->delete($key);
    }
}
