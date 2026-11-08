<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Drop useless field
$migration->dropKey('glpi_ticketrecurrents', 'is_recursive');
$migration->dropField('glpi_ticketrecurrents', 'is_recursive');
