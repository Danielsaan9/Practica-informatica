<?php

use function Safe\preg_match;
use function Safe\scandir;

/**
 * Update from 11.0.x to 12.0.0
 *
 * @return bool
 */
function update110xto1200()
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
    $update_dir                = __DIR__ . '/update_11.0.x_to_12.0.0/';

    $migration->setVersion('12.0.0');

    $update_scripts = scandir($update_dir);
    foreach ($update_scripts as $update_script) {
        if (preg_match('/\.php$/', $update_script) !== 1) {
            continue;
        }
        require $update_dir . $update_script;
    }

    $device_model_tables = [
        'glpi_devicebatterymodels',
        'glpi_devicecameramodels',
        'glpi_devicecasemodels',
        'glpi_devicedrivemodels',
        'glpi_devicegenericmodels',
        'glpi_devicegraphiccardmodels',
        'glpi_deviceharddrivemodels',
        'glpi_devicememorymodels',
        'glpi_devicemotherboardmodels',
        'glpi_devicenetworkcardmodels',
        'glpi_devicepcimodels',
        'glpi_devicepowersupplymodels',
        'glpi_deviceprocessormodels',
        'glpi_devicesensormodels',
        'glpi_devicesoundcardmodels',
    ];

    foreach ($device_model_tables as $table) {
        $migration->addField($table, 'picture_front', 'text');
        $migration->addField($table, 'picture_rear', 'text');
        $migration->addField($table, 'pictures', 'text');
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
