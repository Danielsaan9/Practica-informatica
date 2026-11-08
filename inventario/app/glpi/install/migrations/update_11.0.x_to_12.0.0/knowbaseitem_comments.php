<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$table = 'glpi_knowbaseitems_comments';

$migration->displayMessage("Flatten knowbase item comments to max 2 levels");

$all_comments = [];
foreach ($DB->request([
    'SELECT' => ['id', 'parent_comment_id'],
    'FROM'   => $table,
]) as $row) {
    $all_comments[$row['id']] = $row['parent_comment_id'];
}

$root_comments = [];
$level_2_plus_comments = [];

$updates = [];
foreach ($all_comments as $id => $parent_id) {
    // Root comment (level 0): keep track of it
    if ($parent_id === null) {
        $root_comments[] = $id;
        continue;
    }

    // Level 1 comment (parent has no parent itself): nothing to do
    $grand_parent_id = $all_comments[$parent_id] ?? null;
    if ($grand_parent_id === null) {
        continue;
    }

    // Level 2+: keep track so we can find its first root ancestor
    $level_2_plus_comments[] = $id;
}

foreach ($level_2_plus_comments as $comment_id) {
    // Find the first root ancestor
    $previous = $comment_id;
    do {
        $parent = $all_comments[$previous] ?? null;
        $previous = $parent;
    } while ($parent !== null && !in_array($parent, $root_comments));

    if ($parent === null) {
        // This comment is an orphan, ignore it.
        continue;
    }

    $updates[$comment_id] = $parent;
}

foreach ($updates as $id => $new_parent_id) {
    $DB->update($table, ['parent_comment_id' => $new_parent_id], ['id' => $id]);
}
