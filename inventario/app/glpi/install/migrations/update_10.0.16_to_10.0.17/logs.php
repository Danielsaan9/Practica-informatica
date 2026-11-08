<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addPostQuery(
    $DB->buildDelete(
        'glpi_logs',
        [
            'itemtype' => 'MailCollector',
            'id_search_option' => [22, 23], // errors and last_collect_date
        ],
    )
);
