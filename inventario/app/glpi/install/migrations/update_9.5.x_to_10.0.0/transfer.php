<?php

/**
 * @var Migration $migration
 */
// Add certificate entry for transfers.
$migration->addField('glpi_transfers', 'keep_certificate', "int NOT NULL DEFAULT '0'", [
    'update' => "'1'",
]);
$migration->addField('glpi_transfers', 'clean_certificate', "int NOT NULL DEFAULT '0'", [
    'update' => "'1'",
]);
