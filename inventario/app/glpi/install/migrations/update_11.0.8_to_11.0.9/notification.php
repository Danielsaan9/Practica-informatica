<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_notifications',
        ['event' => 'pendingreason_resolve'], //params
        ['event' => 'pendingreason_close'], //clauses
    )
);
