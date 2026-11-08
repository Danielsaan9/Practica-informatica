<?php

/**
 * Update from 9.4.6 to 9.4.7
 *
 * @return bool
 **/
function update946to947()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

    $migration->setVersion('9.4.7');

    $DB->update('glpi_events', ['type'   => 'dcrooms'], ['type' => 'serverroms']);

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
