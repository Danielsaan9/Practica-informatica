<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_items_operatingsystems', 'company', "varchar(255) NULL DEFAULT NULL");
$migration->addField('glpi_items_operatingsystems', 'owner', "varchar(255) NULL DEFAULT NULL");
$migration->addField('glpi_items_operatingsystems', 'hostid', "varchar(255) NULL DEFAULT NULL");
