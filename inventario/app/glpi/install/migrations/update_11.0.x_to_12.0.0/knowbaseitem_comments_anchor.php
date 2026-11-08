<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

// Optional text-quote anchor (W3C TextQuoteSelector-style) attaching a comment
// to a selected passage instead of the whole article. Root comments only.
$table = 'glpi_knowbaseitems_comments';

$migration->addField($table, 'anchor_prefix', 'string', [
    'after' => 'parent_comment_id',
    'value' => null,
]);
// Bounded: see KnowbaseItem_Comment::MAX_ANCHOR_LENGTH.
$migration->addField($table, 'anchor_exact', 'varchar(1000) DEFAULT NULL', [
    'after' => 'anchor_prefix',
]);
$migration->addField($table, 'anchor_suffix', 'string', [
    'after' => 'anchor_exact',
    'value' => null,
]);
$migration->addField($table, 'anchor_occurrence', 'integer', [
    'after'     => 'anchor_suffix',
    'nodefault' => true,
    'null'      => true,
]);
