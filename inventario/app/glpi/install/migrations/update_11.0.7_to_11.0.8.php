<?php

use function Safe\preg_match;
use function Safe\scandir;

/**
 * Update from 11.0.7 to 11.0.8
 *
 * @return bool for success (will die for most error)
 **/
function update1107to1108()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult       = true;
    $ADDTODISPLAYPREF   = [];
    $DELFROMDISPLAYPREF = [];
    $update_dir = __DIR__ . '/update_11.0.7_to_11.0.8/';

    $migration->setVersion('11.0.8');

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
