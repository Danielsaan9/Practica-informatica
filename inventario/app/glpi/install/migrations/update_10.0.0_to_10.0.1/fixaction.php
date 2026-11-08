<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_rulecriterias',
        [
            'pattern' => '/(.*)[,|\/]/',
        ],
        [
            'id' => 19,
            'pattern' => '/(.*),/',
        ]
    )
);
