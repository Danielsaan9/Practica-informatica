<?php

use function Safe\preg_match;
use function Safe\scandir;

/**
 * Update from 9.5.x to 10.0.0
 *
 * @return bool
 **/
function update95xto1000()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult       = true;
    $ADDTODISPLAYPREF   = [];
    $DELFROMDISPLAYPREF = [];
    $update_dir = __DIR__ . '/update_9.5.x_to_10.0.0/';

    $migration->setVersion('10.0.0');

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

    $migration->addWarningMessage(
        '"utf8mb4" support requires additional migration which can be performed via the "php bin/console migration:utf8mb4" command.'
    );

    return $updateresult;
}
