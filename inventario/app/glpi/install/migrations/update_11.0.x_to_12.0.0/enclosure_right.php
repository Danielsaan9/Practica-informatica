<?php

/**
 * @var DBmysql $DB
 */

$datacenter_rights = $DB->request([
    'SELECT' => ['profiles_id', 'rights'],
    'FROM'   => 'glpi_profilerights',
    'WHERE'  => ['name' => 'datacenter'],
]);

$existing_enclosure_rights = $DB->request([
    'SELECT' => ['profiles_id'],
    'FROM'   => 'glpi_profilerights',
    'WHERE'  => ['name' => 'enclosure'],
]);
$profiles_with_enclosure_right = array_fill_keys(
    array_column(iterator_to_array($existing_enclosure_rights), 'profiles_id'),
    true,
);

foreach ($datacenter_rights as $datacenter_right) {
    if (isset($profiles_with_enclosure_right[$datacenter_right['profiles_id']])) {
        continue;
    }

    $DB->insert('glpi_profilerights', [
        'profiles_id' => $datacenter_right['profiles_id'],
        'name'        => 'enclosure',
        'rights'      => $datacenter_right['rights'],
    ]);
}
