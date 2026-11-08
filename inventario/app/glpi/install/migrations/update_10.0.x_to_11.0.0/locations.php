<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_locations', 'code', 'string', ['after' => 'name']);
$migration->addField('glpi_locations', 'alias', 'string', ['after' => 'code']);
