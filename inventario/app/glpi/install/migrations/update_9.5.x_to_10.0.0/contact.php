<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_contacts", "registration_number")) {
    $migration->addField(
        "glpi_contacts",
        "registration_number",
        "string",
        [
            'after'     => "firstname",
        ]
    );
}
