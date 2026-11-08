<?php

use function Safe\preg_match;
use function Safe\scandir;

/**
 * Update from 12.0.0 to 12.0.1
 *
 * @return bool for success (will die for most error)
 **/
function update1200to1201()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult       = true;
    $ADDTODISPLAYPREF   = [];
    $DELFROMDISPLAYPREF = [];
    $update_dir = __DIR__ . '/update_12.0.0_to_12.0.1/';

    $migration->setVersion('12.0.1');

    $update_scripts = scandir($update_dir);
    foreach ($update_scripts as $update_script) {
        if (preg_match('/\.php$/', $update_script) !== 1) {
            continue;
        }
        require $update_dir . $update_script;
    }

    // ************ Keep it at the end **************
    $migration->updateDisplayPrefs($ADDTODISPLAYPREF, $DELFROMDISPLAYPREF);

    $migration->executeMigration();

    return $updateresult;
}
