<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_groups', 'code', 'string', ['after' => 'name']);
