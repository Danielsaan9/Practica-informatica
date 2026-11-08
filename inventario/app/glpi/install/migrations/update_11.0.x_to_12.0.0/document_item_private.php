<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

// Add is_private column to glpi_documents_items table
$migration->addField(
    'glpi_documents_items',
    'is_private',
    'bool',
    [
        'after'  => 'timeline_position',
        'value'  => 0,
    ]
);

$migration->addKey('glpi_documents_items', 'is_private');

// Add SEEPRIVATE right (8192) to profiles that have UPDATE right on documents
// This preserves existing behavior where technicians can see all documents
$migration->giveRight('document', 8192, ['document' => UPDATE]);
