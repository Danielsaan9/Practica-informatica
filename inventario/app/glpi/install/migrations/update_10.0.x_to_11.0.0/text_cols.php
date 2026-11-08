<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$text_cols = [
    ['glpi_notificationtemplatetranslations', 'content_text', 'longtext'],
    ['glpi_notificationtemplatetranslations', 'content_html', 'longtext'],
    ['glpi_items_kanbans', 'state', 'mediumtext'],
];

foreach ($text_cols as $data) {
    [$table, $column, $type] = $data;
    $migration->changeField($table, $column, $column, $type);
}
