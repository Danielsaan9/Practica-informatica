<?php

namespace Glpi\System\Requirement;

use DBmysql;

final class DatabaseTablesEngine extends AbstractRequirement
{
    /**
     * DB instance.
     *
     */
    private DBmysql $db;

    public function __construct(DBmysql $db)
    {
        parent::__construct(
            __('Database tables engine')
        );

        $this->db = $db;
    }

    protected function check(): void
    {
        $this->validated = true;
        $tables_count = count($this->db->getMyIsamTables());

        // Fail if at least one MyIsam table is found
        if ($tables_count > 0) {
            $this->validated = false;
            $this->validation_messages[] = sprintf(
                __('The database contains %1$d table(s) using the unsupported MyISAM engine. Please run the "%2$s" command to migrate them to the InnoDB engine.'),
                $tables_count,
                'php bin/console migration:myisam_to_innodb'
            );
        }
    }
}
