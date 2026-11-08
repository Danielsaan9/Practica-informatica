<?php

namespace Glpi\Cache;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Psr16Cache;

class SimpleCache extends Psr16Cache implements CacheInterface
{
    public function get($key, $default = null): mixed
    {
        $normalized_key = $this->getNormalizedKey($key);
        return parent::get($normalized_key, $default);
    }

    public function set($key, $value, $ttl = null): bool
    {
        $normalized_key = $this->getNormalizedKey($key);
        return parent::set($normalized_key, $value, $ttl);
    }

    public function delete($key): bool
    {
        $normalized_key = $this->getNormalizedKey($key);
        return parent::delete($normalized_key);
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $normalized_keys = array_map([$this, 'getNormalizedKey'], $keys);
        $result = parent::getMultiple($normalized_keys, $default);

        $values_with_real_keys = [];
        foreach ($keys as $key) {
            $normalized_key = $this->getNormalizedKey($key);
            $values_with_real_keys[$key] = $result[$normalized_key] ?? $default;
        }

        return $values_with_real_keys;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        $values_with_normalized_keys = [];
        foreach ($values as $key => $value) {
            $normalized_key = $this->getNormalizedKey($key);
            $values_with_normalized_keys[$normalized_key] = $value;
        }

        return parent::setMultiple($values_with_normalized_keys, $ttl);
    }

    public function deleteMultiple($keys): bool
    {
        $normalized_keys = array_map([$this, 'getNormalizedKey'], $keys);
        return parent::deleteMultiple($normalized_keys);
    }

    public function has($key): bool
    {
        $normalized_key = $this->getNormalizedKey($key);
        return parent::has($normalized_key);
    }

    /**
     * Returns normalized key to ensure compatibility with cache storage.
     *
     * @param string $key
     *
     * @return string
     */
    private function getNormalizedKey(string $key): string
    {
        return sha1($key);
    }
}
