<?php

/** @var Migration $migration */

$migration->addField('glpi_profiles', 'excluded_searchoptions', 'text', [
    'value'   => null,
    'after'   => 'managed_domainrecordtypes',
]);
$migration->addField('glpi_profiles', 'show_map', 'boolean', [
    'value' => '1',
    'after' => 'excluded_searchoptions',
]);
