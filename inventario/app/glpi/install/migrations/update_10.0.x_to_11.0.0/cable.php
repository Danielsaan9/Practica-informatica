<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_cables", "is_template", false)) {
    $migration->addField('glpi_cables', 'is_template', "tinyint NOT NULL DEFAULT '0'", ['after' => 'is_recursive' ]);
    $migration->addKey('glpi_cables', 'is_template');
}

if (!$DB->fieldExists("glpi_cables", "template_name", false)) {
    $migration->addField('glpi_cables', 'template_name', "varchar(255) DEFAULT NULL", ['after' => 'is_template' ]);
}
