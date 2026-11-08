<?php

/**
 * @var DBmysql $DB
 */

//remove wrong iftype entry (was a CSV file header)
$DB->delete(
    'glpi_networkporttypes',
    [
        'name' => 'Name',
        'comment' => 'Description References',
    ]
);
