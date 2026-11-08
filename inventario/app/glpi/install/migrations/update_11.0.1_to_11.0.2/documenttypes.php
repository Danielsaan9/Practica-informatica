<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// see #21511
$existing = $DB->request([
    'SELECT' => 'id',
    'FROM'   => 'glpi_documenttypes',
    'WHERE'  => [
        'ext'    => 'webp',
    ],
]);

if ($existing->numrows() == 0) {
    $migration->addPostQuery(
        $DB->buildInsert(
            'glpi_documenttypes',
            [
                'name' => 'WebP',
                'ext' => 'webp',
                'icon' => 'webp-dist.png',
            ]
        )
    );
}
