<?php

/**
 * @var Migration $migration
 */

// Per-user list of KB aside articles the user has unfolded.
$migration->addField(
    'glpi_users',
    'unfolded_knowbaseitems',
    'json DEFAULT NULL',
    ['after' => 'itil_layout']
);
