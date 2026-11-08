<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_mailcollectors', 'create_user_from_email', 'bool', ['value' => 0]);
$migration->addField('glpi_mailcollectors', 'add_to_to_observer', 'bool', ['value' => 1]);
