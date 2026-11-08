<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_dcrooms", "vis_cell_width")) {
    $migration->addField(
        "glpi_dcrooms",
        "vis_cell_width",
        "int",
        [
            'after'  => "vis_rows",
            'value'  => 40,
        ]
    );
}
if (!$DB->fieldExists("glpi_dcrooms", "vis_cell_height")) {
    $migration->addField(
        "glpi_dcrooms",
        "vis_cell_height",
        "int",
        [
            'after'  => "vis_cell_width",
            'update' => 39,
            'value'  => 40,
        ]
    );
}
