<?php

/**
 * @var Migration $migration
 */
$table = ProjectTask::getTable();
$migration->addField($table, "is_deleted", "tinyint NOT NULL DEFAULT '0'", [
    'after' => 'projecttasks_id',
]);
$migration->addKey($table, 'is_deleted');

$migration->addField($table, "auto_projectstates", "bool", [
    'after' => 'projectstates_id',
]);

$migration->addConfig(
    [
        'projecttask_unstarted_states_id' => 0,
        'projecttask_inprogress_states_id' => 0,
        'projecttask_completed_states_id' => 0,
    ]
);

// new right value for projecttask
$migration->replaceRight('projecttask', DELETE | PURGE | ProjectTask::READMY | ProjectTask::UPDATEMY | READNOTE | UPDATENOTE, [
    'projecttask' => ProjectTask::READMY | ProjectTask::UPDATEMY | READNOTE | UPDATENOTE,
]);
