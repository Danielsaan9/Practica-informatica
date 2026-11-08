<?php

/**
 * @var Migration $migration
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

$migration->addField('glpi_networknames', 'ipnetworks_id', "int {$default_key_sign} NOT NULL DEFAULT '0'", [
    'after' => 'fqdns_id',
]);
$migration->addKey('glpi_networknames', 'ipnetworks_id', 'ipnetworks_id');
