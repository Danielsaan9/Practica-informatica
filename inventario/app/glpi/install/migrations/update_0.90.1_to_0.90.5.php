<?php

/**
 * Update from 0.90.1 to 0.90.5
 *
 * @return bool
 **/
function update0901to0905()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

    $migration->setVersion('0.90.5');

    // fix https://github.com/glpi-project/glpi/issues/820
    // remove empty suppliers in tickets
    $DB->delete("glpi_suppliers_tickets", [
        'suppliers_id'       => 0,
        'alternative_email'  => "",
    ]);

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
