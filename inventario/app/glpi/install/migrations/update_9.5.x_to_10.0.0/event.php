<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

/** Replace -1 values for glpi_events.items_id field */
// Migration may have been missed if user installed 10.x version before 9.5.7 release date.
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_events',
        ['items_id' => '0'],
        ['items_id' => '-1', 'type' => 'system']
    )
);
/** /Replace -1 values for glpi_events.items_id field */
