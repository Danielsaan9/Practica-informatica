<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

//remove wrong iftype entry (was a CSV file header)
$DB->delete(
    'glpi_networkporttypes',
    [
        'name' => 'Name',
        'comment' => 'Description References',
    ]
);
