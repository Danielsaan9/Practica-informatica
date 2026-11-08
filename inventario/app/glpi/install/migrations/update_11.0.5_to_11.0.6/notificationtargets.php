<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

// Remove duplicates targets
$duplicates_targets_iterator = $DB->request([
    'SELECT' => [
        'notifications_id',
        'items_id',
        'type',
        'MIN' => 'id AS min_id',
        'COUNT' => '* AS count',
    ],
    'FROM' => 'glpi_notificationtargets',
    'GROUPBY' => ['notifications_id', 'items_id', 'type'],
    'HAVING' => ['count' => ['>', 1]],
]);

foreach ($duplicates_targets_iterator as $target) {
    $DB->delete('glpi_notificationtargets', [
        'notifications_id' => $target['notifications_id'],
        'items_id' => $target['items_id'],
        'type' => $target['type'],
        'id' => ['>', $target['min_id']],
    ]);
}

$migration->dropKey('glpi_notificationtargets', 'notifications_id');
$migration->addKey('glpi_notificationtargets', ['notifications_id', 'items_id', 'type'], 'unicity', 'UNIQUE');
