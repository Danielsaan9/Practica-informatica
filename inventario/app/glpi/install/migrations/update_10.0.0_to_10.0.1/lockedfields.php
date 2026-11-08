<?php

/**
 * @var array $ADDTODISPLAYPREF
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_lockedfields", "is_global", false)) {
    $migration->addField('glpi_lockedfields', 'is_global', "tinyint NOT NULL DEFAULT '0'", ['after' => 'date_creation' ]);
    $migration->addKey('glpi_lockedfields', 'is_global');
}

$ADDTODISPLAYPREF['Lockedfield'] = [7];
