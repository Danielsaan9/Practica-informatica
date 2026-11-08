<?php

use function Safe\preg_match;
use function Safe\scandir;

/**
 * Update from 10.0.x to 11.0.0
 *
 * @return bool
 */
function update100xto1100()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult              = true;
    $ADDTODISPLAYPREF          = [];
    $ADDTODISPLAYPREF_HELPDESK = [];
    $DELFROMDISPLAYPREF        = [];
    $update_dir                = __DIR__ . '/update_10.0.x_to_11.0.0/';

    $migration->setVersion('11.0.0');

    $update_scripts = scandir($update_dir);
    foreach ($update_scripts as $update_script) {
        if (preg_match('/\.php$/', $update_script) !== 1) {
            continue;
        }
        require $update_dir . $update_script;
    }

    // ************ Keep it at the end **************
    $migration->updateDisplayPrefs($ADDTODISPLAYPREF, $DELFROMDISPLAYPREF);

    // @phpstan-ignore foreach.emptyArray (populated from child files)
    foreach ($ADDTODISPLAYPREF_HELPDESK as $type => $tab) {
        $rank = 1;
        foreach ($tab as $newval) {
            $DB->updateOrInsert(
                'glpi_displaypreferences',
                [
                    'rank'      => $rank++,
                ],
                [
                    'users_id'  => '0',
                    'itemtype'  => $type,
                    'num'       => $newval,
                    'interface' => 'helpdesk',
                ]
            );
        }
    }

    $migration->executeMigration();

    return $updateresult;
}
