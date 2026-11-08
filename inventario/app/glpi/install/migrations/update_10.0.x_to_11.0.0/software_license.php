<?php

/**
 * @var Migration $migration
 * @var DBmysql $DB
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if ($DB->fieldExists('glpi_softwarelicenses', 'softwares_id')) {
    $migration->changeField(
        'glpi_softwarelicenses',
        'softwares_id',
        'softwares_id',
        "int {$default_key_sign}",
        [
            'null' => true,
        ]
    );
}
