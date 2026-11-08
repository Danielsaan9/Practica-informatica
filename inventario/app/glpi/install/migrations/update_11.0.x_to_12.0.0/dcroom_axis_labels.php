<?php

/** @var Migration $migration */

$migration->addField('glpi_dcrooms', 'column_labels', 'text', [
    'value' => null,
    'after' => 'vis_cell_height',
]);
$migration->addField('glpi_dcrooms', 'row_labels', 'text', [
    'value' => null,
    'after' => 'column_labels',
]);
