<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$fonts_mapping = [
    // xbriyaz => Arabic fonts
    'aealarabiya' => ['xbriyaz'],
    // sun-exta => CJK fonts
    'cid0cs' => ['sun-exta'],
    // TTF font => corresponding Adobe embedded fonts
    'pdfacourier' => ['courier'],
    'pdfahelvetica' => ['helvetica'],
    'pdfasymbol' => ['symbol'],
    'pdfatimes' => ['times'],
    'pdfazapfdingbats' => ['zapfdingbats'],
    // Other unsupported fonts
    'dejavusansextralight' => ['dejavusans'],
];

foreach ($fonts_mapping as $new_value => $old_values) {
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_configs',
            [
                'value' => $new_value,
            ],
            [
                'name'  => 'pdffont',
                'value' => $old_values,
            ]
        )
    );
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_users',
            [
                'pdffont' => $new_value,
            ],
            [
                'pdffont' => $old_values,
            ]
        )
    );
}
