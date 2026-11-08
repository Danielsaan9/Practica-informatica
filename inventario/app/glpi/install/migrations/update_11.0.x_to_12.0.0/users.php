<?php

/**
 * @var Migration $migration
 */

$migration->addField('glpi_users', 'middlename', 'varchar(255)', [
    'after' => 'firstname',
]);
$migration->addKey('glpi_users', 'middlename');
