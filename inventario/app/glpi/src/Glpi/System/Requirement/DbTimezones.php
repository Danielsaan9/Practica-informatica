<?php

namespace Glpi\System\Requirement;

use DBmysql;

/**
 * @since 9.5.0
 */
class DbTimezones extends AbstractRequirement
{
    /**
     * DB instance.
     *
     */
    private DBmysql $db;

    public function __construct(DBmysql $db)
    {
        parent::__construct(
            __('DB timezone data'),
            __('Enable usage of timezones.'),
            true
        );

        $this->db = $db;
    }

    protected function check()
    {
        $available_timezones = $this->db->getTimezones();

        if (count($available_timezones) === 0) {
            $this->validated = false;
            $this->validation_messages[] = sprintf(__('Timezones seems not loaded, see %1$s'), 'https://glpi-install.readthedocs.io/en/latest/timezones.html.');
            return;
        }

        $this->validated = true;
        $this->validation_messages[] = __('Timezones seems loaded in database.');
    }
}
