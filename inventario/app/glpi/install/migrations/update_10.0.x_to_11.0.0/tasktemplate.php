<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addField(
    'glpi_tasktemplates',
    'use_current_user',
    'bool',
    [
        'after' => 'users_id_tech',
    ]
);
