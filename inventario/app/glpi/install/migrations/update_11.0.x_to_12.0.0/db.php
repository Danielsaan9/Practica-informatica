<?php

use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\rename;

if (file_exists(GLPI_CONFIG_DIR . '/config_db_slave.php')) {
    //check if new file exist, if not create it with the content of the old one
    if (!file_exists(GLPI_CONFIG_DIR . '/config_db_replica.php')) {
        rename(GLPI_CONFIG_DIR . '/config_db_slave.php', GLPI_CONFIG_DIR . '/config_db_replica.php');
        $contents = file_get_contents(GLPI_CONFIG_DIR . '/config_db_replica.php');
        $contents = str_replace(
            [
                'DBSlave',
                '$slave',
            ],
            [
                'DBReplica', // rename class
                '$replica', //rename property
            ],
            $contents
        );
        file_put_contents(GLPI_CONFIG_DIR . '/config_db_replica.php', $contents);
    } else {
        //if new file exist, rename the old one
        rename(GLPI_CONFIG_DIR . '/config_db_slave.php', GLPI_CONFIG_DIR . '/config_db_slave.php.bkp');
    }
}

global $CFG_GLPI;
$current_replica_config = $CFG_GLPI['use_slave_for_search'] ?? 0;
/**
 * @var Migration $migration
 */
$migration->addConfig(['use_replica_for_search' => $current_replica_config]);
$migration->removeConfig(['use_slave_for_search']);
