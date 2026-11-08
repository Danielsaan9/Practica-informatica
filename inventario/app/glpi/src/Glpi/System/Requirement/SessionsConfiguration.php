<?php

namespace Glpi\System\Requirement;

use function Safe\ini_get;
use function Safe\session_name;

/**
 * @since 9.5.0
 */
class SessionsConfiguration extends AbstractRequirement
{
    public function __construct()
    {
        parent::__construct(
            __('Sessions configuration')
        );
    }

    protected function check()
    {
        // Check session extension
        if (!$this->isExtensionLoaded()) {
            $this->validated = false;
            $this->validation_messages[] = __('session extension is not installed.');
            return;
        }

        // Check configuration values
        $is_autostart_on   = $this->isAutostartOn();

        if ($is_autostart_on) {
            $this->validation_messages[] = __('"session.auto_start" must be set to off.');
            $this->validated = false;
            return;
        }

        $this->validated = true;
        $this->validation_messages[] = __s('Sessions configuration is OK.');
    }

    protected function isExtensionLoaded(): bool
    {
        return extension_loaded('session');
    }

    protected function isAutostartOn(): bool
    {
        return ini_get('session.auto_start') == 1;
    }

    protected function isUsetranssidOn(): bool
    {
        return ini_get('session.use_trans_sid') == 1 || isset($_POST[session_name()]) || isset($_GET[session_name()]);
        ;
    }
}
