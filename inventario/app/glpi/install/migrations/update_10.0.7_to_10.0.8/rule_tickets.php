<?php

/**
 * @var DBmysql $DB
 */
$DB->update(
    'glpi_rulecriterias',
    [
        'criteria' => '_locations_id_of_item',
    ],
    [
        'criteria' => 'items_locations',
    ]
);
