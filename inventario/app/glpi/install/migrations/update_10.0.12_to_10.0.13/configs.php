<?php

/**
 * @var Migration $migration
 */
$migration->changeField(
    'glpi_users',
    'timeline_action_btn_layout',
    'timeline_action_btn_layout',
    'tinyint DEFAULT NULL'
);

$migration->changeField(
    'glpi_users',
    'timeline_date_format',
    'timeline_date_format',
    'tinyint DEFAULT NULL'
);
