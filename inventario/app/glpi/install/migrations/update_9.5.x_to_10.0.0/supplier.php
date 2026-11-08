<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_suppliers", "registration_number")) {
    $migration->addField(
        "glpi_suppliers",
        "registration_number",
        "string",
        [
            'after'     => "suppliertypes_id",
        ]
    );
}
