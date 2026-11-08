<?php

/**
 * @var DBmysql $DB
 */
// Replace old rule criteria itilcategories_id_cn
$DB->update(
    'glpi_rulecriterias',
    [
        'criteria' => 'itilcategories_id',
    ],
    [
        'criteria' => 'itilcategories_id_cn',
    ]
);
