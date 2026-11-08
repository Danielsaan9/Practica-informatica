<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_states', 'is_helpdesk_visible', 'bool', ['value' => 1]);
$migration->addKey('glpi_states', 'is_helpdesk_visible');
