<?php

namespace Glpi\System\Requirement;

/**
 * Check that the security key files stored in the configuration directory can
 * be used, i.e. existing ones can be read, and missing ones can be generated.
 */
class SecurityKeysAccess extends AbstractRequirement
{
    /**
     * Security key files names, relative to the configuration directory.
     *
     * @var string[]
     */
    private const KEY_FILES = [
        'glpicrypt.key', // see \GLPIKey class
        'oauth.pem',     // see \Glpi\OAuth\Server class
        'oauth.pub',     // see \Glpi\OAuth\Server class
    ];

    /**
     * Configuration directory path.
     *
     * @var string
     */
    private $config_dir;

    /**
     * @param string $config_dir Configuration directory path.
     */
    public function __construct(string $config_dir = GLPI_CONFIG_DIR)
    {
        parent::__construct(
            __('Permissions for security keys files')
        );

        $this->config_dir = $config_dir;
    }

    protected function check()
    {
        $this->validated = true;

        // Missing keys are generated during the installation/update process.
        $is_dir_writable = is_writable($this->config_dir);

        foreach (self::KEY_FILES as $filename) {
            $file_path = $this->config_dir . '/' . $filename;

            if (!file_exists($file_path)) {
                if (!$is_dir_writable) {
                    $this->validated = false;
                    $this->validation_messages[] = sprintf(__('The security key file %s could not be created.'), $file_path);
                }
            } elseif (!is_readable($file_path)) {
                $this->validated = false;
                $this->validation_messages[] = sprintf(__('The security key file %s is not readable.'), $file_path);
            }
        }

        if ($this->validated) {
            $this->validation_messages[] = __('Security key files are accessibles.');
        }
    }
}
