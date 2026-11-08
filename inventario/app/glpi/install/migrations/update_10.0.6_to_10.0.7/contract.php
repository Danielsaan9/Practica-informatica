<?php

/**
 * @var Migration $migration
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();
$migration->addField('glpi_contracts', 'locations_id', "int {$default_key_sign} NOT NULL DEFAULT '0'", ['after' => 'contracttypes_id']);
$migration->addKey('glpi_contracts', 'locations_id', 'locations_id');
