<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$condition = ['NOT' => ['filepath' => null]];
if (!$DB->fieldExists('glpi_documents', 'filesize', false)) {
    $migration->addField('glpi_documents', 'filesize', 'int DEFAULT NULL');
} else {
    $condition['filesize'] = null;
}

$to_compute = countElementsInTable('glpi_documents', $condition);

if ($to_compute > 0) {
    $migration->addWarningMessage(
        sprintf(__('%d documents do not have their size recorded in the database.'), $to_compute)
        . ' '
        . sprintf(__('Run the "%1$s" command to compute them.'), 'php bin/console migration:compute_documents_size')
    );
}
