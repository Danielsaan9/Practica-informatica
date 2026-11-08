<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
/* Add `previous_status` to glpi_pendingreasons_items */
if (!$DB->fieldExists('glpi_pendingreasons_items', 'previous_status')) {
    $migration->addField('glpi_pendingreasons_items', 'previous_status', "int DEFAULT NULL");
}
