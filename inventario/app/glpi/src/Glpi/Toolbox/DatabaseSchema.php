<?php

namespace Glpi\Toolbox;

use function Safe\realpath;

class DatabaseSchema
{
    /**
     * Return empty schema file path for given version.
     *
     * @param string $version
     *
     * @return null|string
     */
    public static function getEmptySchemaPath(string $version): ?string
    {
        $normalized_version = VersionParser::getNormalizedVersion($version, false);
        $latest_version     = VersionParser::getNormalizedVersion(GLPI_VERSION, false);

        $schema_path = $normalized_version === $latest_version
            ? sprintf('%s/install/mysql/glpi-empty.sql', realpath(GLPI_ROOT))
            : sprintf('%s/install/mysql/glpi-%s-empty.sql', realpath(GLPI_ROOT), $normalized_version);

        return file_exists($schema_path) ? $schema_path : null;
    }
}
