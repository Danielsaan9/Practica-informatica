<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_rulecriterias',
        [
            'criteria' => 'users_id_recipient',
        ],
        [
            'criteria' => '_users_id_recipient',
        ]
    )
);
