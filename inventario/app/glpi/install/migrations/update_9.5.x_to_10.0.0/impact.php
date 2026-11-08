<?php

/**
 * @var Migration $migration
 */

/** Impact Relations improvements */

$migration->changeField('glpi_impactcontexts', 'positions', 'positions', 'mediumtext', [
    'after' => 'id',
    'value' => '',
]);
