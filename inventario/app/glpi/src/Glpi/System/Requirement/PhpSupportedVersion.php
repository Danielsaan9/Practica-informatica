<?php

namespace Glpi\System\Requirement;

use function Safe\preg_replace;

/**
 * @since 10.0.7
 */
class PhpSupportedVersion extends AbstractRequirement
{
    /**
     * Minimal version of PHP version that still get security fixes.
     *
     * @var string
     * @see https://www.php.net/supported-versions
     */
    private const MIN_SECURITY_SUPPORTED_VERSION = '8.2';

    public function __construct()
    {
        parent::__construct(
            __('PHP maintained version'),
            __('A PHP version maintained by the PHP community should be used to get the benefits of PHP security and bug fixes.'),
            true,
            true
        );
    }

    protected function check()
    {
        $php_version =  preg_replace('/^(\d+\.\d+)\..*$/', '$1', $this->getPHPVersion());

        if (version_compare($php_version, self::MIN_SECURITY_SUPPORTED_VERSION, '>=')) {
            $this->validated = true;
            // No validation message as we cannot be sure that PHP is up-to-date.
        } else {
            $this->validated = false;
            $this->validation_messages[] = sprintf(__('PHP %s is no longer maintained by its community.'), $php_version);
            $this->validation_messages[] = __('Even if GLPI still supports this PHP version, an upgrade to a more recent PHP version is recommended.');
            $this->validation_messages[] = __('Indeed, this PHP version may contain unpatched security vulnerabilities.');
        }
    }

    protected function getPHPVersion(): string
    {
        return phpversion();
    }
}
