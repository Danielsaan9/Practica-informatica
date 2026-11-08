<?php

namespace Glpi\System;

/**
 * @since 9.5.4
 */
class Variables
{
    /**
     * Returns list of constants corresponding to directories that contains custom data.
     *
     * @return string[]
     */
    public static function getDataDirectoriesConstants(): array
    {
        return [
            'GLPI_CACHE_DIR',
            'GLPI_CRON_DIR',
            'GLPI_DOC_DIR',
            'GLPI_GRAPH_DIR',
            'GLPI_LOCK_DIR',
            'GLPI_LOG_DIR',
            'GLPI_PICTURE_DIR',
            'GLPI_PLUGIN_DOC_DIR',
            'GLPI_RSS_DIR',
            'GLPI_SESSION_DIR',
            'GLPI_TMP_DIR',
            'GLPI_UPLOAD_DIR',
        ];
    }

    /**
     * Returns list of directories that contains custom data.
     *
     * @return string[]
     */
    public static function getDataDirectories()
    {
        return array_map(
            fn(string $constant) => constant($constant),
            self::getDataDirectoriesConstants()
        );
    }
}
