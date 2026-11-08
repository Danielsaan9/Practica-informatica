<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_savedsearches_alerts', 'frequency', "int NOT NULL DEFAULT '0'");
