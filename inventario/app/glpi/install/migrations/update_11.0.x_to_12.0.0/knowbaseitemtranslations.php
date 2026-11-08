<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$table = 'glpi_knowbaseitemtranslations';

$migration->displayMessage("Add unicity key on knowbase item translations");

$duplicates_iterator = $DB->request([
    'SELECT' => [
        'knowbaseitems_id',
        'language',
        'COUNT' => '* AS count',
    ],
    'FROM' => $table,
    'GROUPBY' => ['knowbaseitems_id', 'language'],
    'HAVING' => ['count' => ['>', 1]],
]);

if (count($duplicates_iterator) > 0) {
    throw new RuntimeException(
        "Duplicate knowbase item translations found for the same language. "
        . "Please remove duplicates before upgrading."
    );
}

$migration->dropKey($table, 'item');
$migration->addKey($table, ['knowbaseitems_id', 'language'], 'unicity', 'UNIQUE');
