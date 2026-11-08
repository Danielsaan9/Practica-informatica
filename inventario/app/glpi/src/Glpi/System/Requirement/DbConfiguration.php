<?php

namespace Glpi\System\Requirement;

use DBmysql;
use mysqli_result;

/**
 * @since 10.0.0
 */
class DbConfiguration extends AbstractRequirement
{
    /**
     * DB instance.
     *
     */
    private DBmysql $db;

    public function __construct(DBmysql $db)
    {
        parent::__construct(
            __('DB configuration')
        );

        $this->db = $db;
    }

    protected function check()
    {
        $query = 'SELECT @@GLOBAL.' . $this->db->quoteName('innodb_page_size as innodb_page_size');

        /** @var mysqli_result $db_config_res */
        $db_config_res = $this->db->doQuery($query);
        $db_config = $db_config_res->fetch_assoc();

        $incompatibilities = [];
        if ((int) $db_config['innodb_page_size'] < 8192) {
            $incompatibilities[] = '"innodb_page_size" must be >= 8KB.';
        }

        if (count($incompatibilities) > 0) {
            $this->validation_messages = $incompatibilities;
            $this->validated = false;
        } else {
            $this->validation_messages[] = __('Database configuration is OK.');
            $this->validated = true;
        }
    }
}
