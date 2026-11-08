<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();

if (!$DB->fieldExists('glpi_authldaps', 'tls_certfile')) {
    $migration->addField(
        'glpi_authldaps',
        'tls_certfile',
        'text',
        [
            'after'  => 'inventory_domain',
        ]
    );
}

if (!$DB->fieldExists('glpi_authldaps', 'tls_keyfile')) {
    $migration->addField(
        'glpi_authldaps',
        'tls_keyfile',
        'text',
        [
            'after'  => 'tls_certfile',
        ]
    );
}

if (!$DB->fieldExists('glpi_authldaps', 'use_bind')) {
    $migration->addField(
        'glpi_authldaps',
        'use_bind',
        'bool',
        [
            'after'  => 'tls_keyfile',
            'value' => 1,
        ]
    );
}

if (!$DB->fieldExists('glpi_authldaps', 'timeout')) {
    $migration->addField(
        'glpi_authldaps',
        'timeout',
        'int',
        [
            'after'  => 'use_bind',
            'value' => 10,
        ]
    );
}

if (!$DB->fieldExists('glpi_authldapreplicates', 'timeout')) {
    $migration->addField(
        'glpi_authldapreplicates',
        'timeout',
        'int',
        [
            'after'  => 'name',
            'value' => 10,
        ]
    );
}
