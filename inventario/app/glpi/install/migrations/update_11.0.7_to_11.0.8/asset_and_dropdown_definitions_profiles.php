<?php

use function Safe\json_decode;
use function Safe\json_encode;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

// Remove orphaned profile IDs left in the `profiles` JSON field of asset and dropdown
// definitions when a profile was purged before this cleanup was added.
$definitions_tables = [
    'glpi_assets_assetdefinitions',
    'glpi_dropdowns_dropdowndefinitions',
];

$existing_profiles = array_column(
    iterator_to_array($DB->request(['FROM' => 'glpi_profiles', 'FIELDS' => ['id']])),
    'id'
);

foreach ($definitions_tables as $table) {
    foreach ($DB->request(['FROM' => $table, 'FIELDS' => ['id', 'profiles']]) as $row) {
        $profiles = json_decode($row['profiles'] ?? '[]', associative: true);
        if (!is_array($profiles)) {
            continue;
        }

        $cleaned_profiles = array_intersect_key(
            $profiles,
            array_flip($existing_profiles)
        );

        if (count($cleaned_profiles) !== count($profiles)) {
            $DB->update(
                $table,
                ['profiles' => json_encode($cleaned_profiles)],
                ['id' => $row['id']]
            );
        }
    }
}
