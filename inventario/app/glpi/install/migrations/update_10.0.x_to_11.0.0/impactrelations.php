<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_impactrelations', 'name', "string", [
    'after'  => 'id',
    'value' => '',
]);

$migration->addKey('glpi_impactrelations', 'name');
