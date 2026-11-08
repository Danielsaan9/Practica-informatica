<?php

namespace Glpi\System\Requirement;

use Glpi\Toolbox\VersionParser;

use function Safe\preg_replace;

/**
 * @since 9.5.0
 */
class PhpVersion extends AbstractRequirement
{
    /**
     * Minimal required PHP version (inclusive).
     *
     */
    private string $min_version;

    /**
     * Maximum required PHP version (inclusive).
     *
     */
    private string $max_version;

    /**
     * @param string $min_version  Minimal required PHP version (inclusive)
     * @param string $max_version  Maximum required PHP version (inclusive)
     */
    public function __construct(string $min_version, string $max_version)
    {
        parent::__construct(
            __('PHP Parser')
        );

        $this->min_version = $min_version;
        $this->max_version = $max_version;
    }

    protected function check()
    {
        // Remove potential stability flag used in version strings
        $min_version = VersionParser::getNormalizedVersion($this->min_version, false);
        $max_version = VersionParser::getNormalizedVersion($this->max_version, false);

        // Accept any version between first stable release of min version (i.e. X.Y.0) and any release of the max version (i.e. X.Y.999).
        $this->validated = version_compare(PHP_VERSION, preg_replace('/^(\d+)\.(\d+)\./', '$1.$2.0', $min_version), '>=')
            && version_compare(PHP_VERSION, preg_replace('/^(\d+)\.(\d+)\./', '$1.$2.999', $max_version), '<');

        $this->validation_messages[] = $this->validated
            ? sprintf(__('PHP version (%s) is supported.'), PHP_VERSION)
            : sprintf(__('PHP version must be between %s and %s.'), $this->min_version, $this->max_version);
    }
}
