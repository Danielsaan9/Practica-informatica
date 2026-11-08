<?php

namespace Glpi\Toolbox;

use function Safe\gethostname;

class FrontEnd
{
    /**
     * Provide a cache key that can be use in URLs for given version, without actually exposing
     * the version to everyone.
     *
     * @param string $version
     *
     * @return string
     */
    public static function getVersionCacheKey(string $version): string
    {
        // using both gethostname() and GLPI_ROOT will provide a hardly predictable but stable token
        return sha1($version . gethostname() . GLPI_ROOT);
    }
}
