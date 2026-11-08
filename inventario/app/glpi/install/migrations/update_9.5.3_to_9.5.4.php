<?php

/**
 * Update from 9.5.3 to 9.5.4
 *
 * @return bool
 **/
function update953to954()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult = true;

    $migration->setVersion('9.5.4');

    /* Remove invalid Profile SO */
    $DB->delete('glpi_displaypreferences', ['itemtype' => 'Profile', 'num' => 62]);
    /* /Remove invalid Profile SO */

    /* Add is_default_profile */
    $migration->addField("glpi_profiles_users", "is_default_profile", "bool");
    /* /Add is_default_profile */

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
