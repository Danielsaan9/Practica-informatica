<?php

/**
 * Represents a dependency relation between project tasks
 * Possible link types are "finish_to_start":"0", "start_to_start":"1", "finish_to_finish":"2", "start_to_finish":"3"
 *
 * @since 9.5.4
 *
 */
class ProjectTaskLink extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = ProjectTask::class;
    public static ?string $items_id_1 = 'projecttasks_id_source';

    public static ?string $itemtype_2 = ProjectTask::class;
    public static ?string $items_id_2 = 'projecttasks_id_target';

    /**
     * @param int[] $projecttaskIds List of project task IDs
     * @return DBmysqlIterator
     * @used-by gantt plugin
     */
    public function getFromDBForItemIDs(array $projecttaskIds)
    {
        global $DB;

        $iterator = $DB->request([
            'FROM' => self::getTable(),
            'WHERE' => [
                'AND' => [
                    'projecttasks_id_source' => $projecttaskIds,
                    'projecttasks_id_target' => $projecttaskIds,
                ],
            ],
        ]);

        return $iterator;
    }

    /**
     * @param array{projecttasks_id_source: int, projecttasks_id_target: int, type: int} $taskLink
     * @return bool
     */
    public function checkIfExist($taskLink)
    {
        global $DB;
        $iterator = $DB->request([
            'SELECT' => 'id',
            'FROM' => self::getTable(),
            'WHERE' => [
                'AND' => ['projecttasks_id_source' => $taskLink['projecttasks_id_source'],
                ],
                ['projecttasks_id_target' => $taskLink['projecttasks_id_target']],
                ['type' => $taskLink['type']],
            ],
        ]);
        return count($iterator) > 0;
    }
}
