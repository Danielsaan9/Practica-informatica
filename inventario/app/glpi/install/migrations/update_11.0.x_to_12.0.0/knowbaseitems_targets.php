<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$migration->displayMessage("Add unicity keys on knowbase item targets");

$targets = [
    'glpi_knowbaseitems_users' => ['knowbaseitems_id', 'users_id'],
    'glpi_groups_knowbaseitems' => ['knowbaseitems_id', 'groups_id', 'entities_id'],
    'glpi_knowbaseitems_profiles' => ['knowbaseitems_id', 'profiles_id', 'entities_id'],
    'glpi_entities_knowbaseitems' => ['knowbaseitems_id', 'entities_id'],
];

foreach ($targets as $table => $fields) {
    $has_recursivity = $DB->fieldExists($table, 'is_recursive');

    // Clean up duplicate entries that may exist from 11.0 (where duplicates were allowed in the UI).
    // For tables with is_recursive, prefer keeping the row with is_recursive = 1.
    $duplicates_iterator = $DB->request([
        'SELECT'  => array_merge($fields, ['COUNT' => '* AS count']),
        'FROM'    => $table,
        'GROUPBY' => $fields,
        'HAVING'  => ['count' => ['>', 1]],
    ]);

    foreach ($duplicates_iterator as $duplicate_group) {
        $conditions = [];
        foreach ($fields as $field) {
            $conditions[$field] = $duplicate_group[$field];
        }

        $order = $has_recursivity
            ? ['is_recursive DESC', 'id ASC']
            : ['id ASC'];

        $rows_iterator = $DB->request([
            'SELECT' => ['id'],
            'FROM'   => $table,
            'WHERE'  => $conditions,
            'ORDER'  => $order,
        ]);

        $ids_to_delete = [];
        foreach ($rows_iterator as $row) {
            $ids_to_delete[] = $row['id'];
        }
        array_shift($ids_to_delete); // Keep the first (best) entry

        if ($ids_to_delete !== []) {
            $DB->delete($table, ['id' => $ids_to_delete]);
        }
    }

    $migration->dropKey($table, 'knowbaseitems_id');
    $migration->addKey($table, $fields, 'unicity', 'UNIQUE');
}
