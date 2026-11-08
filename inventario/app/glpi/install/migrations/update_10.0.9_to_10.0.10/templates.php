<?php

/**
 * @var Migration $migration
 */
$rich_text_fields = [
    'glpi_itilfollowuptemplates'          => 'content',
    'glpi_planningexternaleventtemplates' => 'text',
    // already a longtext 'glpi_projecttasktemplates'           => 'description',
    'glpi_solutiontemplates'              => 'content',
    'glpi_tasktemplates'                  => 'content',
];
foreach ($rich_text_fields as $table => $field) {
    $migration->changeField(
        $table,
        $field,
        $field,
        'mediumtext',
    );
}
