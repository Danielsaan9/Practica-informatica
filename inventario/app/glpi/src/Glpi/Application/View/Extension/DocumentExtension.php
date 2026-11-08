<?php

namespace Glpi\Application\View\Extension;

use Toolbox;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use function Safe\filesize;

/**
 * @since 10.0.0
 */
class DocumentExtension extends AbstractExtension
{
    /**
     * Static cache for user defined files extensions icons.
     */
    private static ?array $extensionIcon = null;

    public function getFilters(): array
    {
        return [
            new TwigFilter('document_icon', [$this, 'getDocumentIcon']),
            new TwigFilter('document_size', [$this, 'getDocumentSize']),
        ];
    }

    /**
     * Returns icon URL for given document filename.
     *
     * @param string $filename
     *
     * @return string
     */
    public function getDocumentIcon(string $filename): string
    {
        global $CFG_GLPI, $DB;

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (self::$extensionIcon === null) {
            $iterator = $DB->request([
                'SELECT' => [
                    'ext',
                    'icon',
                ],
                'FROM' => 'glpi_documenttypes',
                'WHERE' => [
                    'icon' => ['<>', ''],
                ],
            ]);
            foreach ($iterator as $result) {
                self::$extensionIcon[$result['ext']] = $result['icon'];
            }
        }

        $defaultIcon = '/pics/timeline/file.png';
        $icon = $defaultIcon;

        if (isset(self::$extensionIcon[$extension])) {
            $icon = '/pics/icones/' . self::$extensionIcon[$extension];
        }

        return $CFG_GLPI['root_doc'] . (file_exists(GLPI_ROOT . $icon) ? $icon : $defaultIcon);
    }

    /**
     * Returns human readable size of file matching given path (relative to GLPI_DOC_DIR).
     *
     * @param string $filepath
     *
     * @return null|string
     */
    public function getDocumentSize(string $filepath): ?string
    {
        $fullpath = GLPI_DOC_DIR . '/' . $filepath;

        return is_readable($fullpath) ? Toolbox::getSize(filesize($fullpath)) : null;
    }
}
