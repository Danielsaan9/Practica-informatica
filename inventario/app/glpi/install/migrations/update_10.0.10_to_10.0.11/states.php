<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 * @var array $ADDTODISPLAYPREF
 */
if (!$DB->fieldExists('glpi_states', 'is_visible_unmanaged')) {
    $migration->addField('glpi_states', 'is_visible_unmanaged', 'bool', [
        'value' => 1,
        'after' => 'is_visible_cable',
    ]);
}
$migration->addKey('glpi_states', 'is_visible_unmanaged');
