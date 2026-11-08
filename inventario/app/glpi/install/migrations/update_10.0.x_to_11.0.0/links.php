<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
use function Safe\preg_replace;

$links = $DB->request([
    'SELECT' => ['id', 'link', 'data'],
    'FROM'   => 'glpi_links',
]);

// Replace custom tags format with twig variable format
$simple_tag_pattern = '/\[([A-Z_]+)\]/';
$field_tag_pattern = '/\[FIELD:([a-z_]+)\]/';

foreach ($links as $link) {
    $new_link = preg_replace($simple_tag_pattern, '{{ $1 }}', $link['link']);
    $new_link = preg_replace($field_tag_pattern, '{{ item.$1 }}', $new_link);
    $new_data = preg_replace($simple_tag_pattern, '{{ $1 }}', $link['data']);
    $new_data = preg_replace($field_tag_pattern, '{{ item.$1 }}', $new_data);
    $DB->update('glpi_links', [
        'id'   => $link['id'],
        'link' => $new_link,
        'data' => $new_data,
    ], [
        'id' => $link['id'],
    ]);
}
