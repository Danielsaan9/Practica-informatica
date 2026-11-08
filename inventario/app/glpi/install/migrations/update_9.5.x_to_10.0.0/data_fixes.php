<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

/** Drop unused config entry 'use_timezones' */
$migration->addPostQuery(
    $DB->buildDelete(
        'glpi_configs',
        [
            'context'   => 'core',
            'name'      => 'use_timezones',
        ]
    )
);
/** /Drop unused config entry 'use_timezones' */

/** Fix olaticket crontask frequency */
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_crontasks',
        ['frequency' => '300'],
        ['itemtype' => 'OlaLevel_Ticket', 'name' => 'olaticket']
    )
);
/** /Fix olaticket crontask frequency */

/** Fix mixed classes case in DB */
$mixed_case_classes = [
    'DeviceMotherBoardModel' => 'DeviceMotherboardModel',
];
foreach ($mixed_case_classes as $bad_case_classname => $classname) {
    $migration->renameItemtype($bad_case_classname, $classname, false);
}
/** /Fix mixed classes case in DB */
