<?php

/** @file
 * @brief
 */

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

global $DB;

if (isset($_POST["projects_id"])) {
    if ($_POST["projects_id"] > 0) {

        $condition = [
            'glpi_projecttasks.projects_id' => $_POST['projects_id'],
        ];

        $finished_states_it = $DB->request(
            [
                'SELECT' => ['id'],
                'FROM'   => ProjectState::getTable(),
                'WHERE'  => [
                    'is_finished' => 1,
                ],
            ]
        );

        $finished_states_ids = [];
        foreach ($finished_states_it as $state) {
            $finished_states_ids[] = $state['id'];
        }

        if ($finished_states_ids !== []) {
            $condition['glpi_projecttasks.projectstates_id'] = ['NOT IN', $finished_states_ids];
        }

        if (!empty($_POST['used'])) {
            $condition['glpi_projecttasks.id'] = ['NOT IN', $_POST['used']];
        }

        $dropdown_params = [
            'itemtype'        => ProjectTask::class,
            'entity_restrict' => Session::getMatchingActiveEntities($_POST['entity_restrict']),
            'myname'          => $_POST["myname"],
            'condition'       => $condition,
            'rand'            => $_POST["rand"],
        ];

        if (isset($_POST["displaywith"])) {
            $dropdown_params["displaywith"] = $_POST["displaywith"];
        }

        $label = ProjectTask::getTypeName(1);

        echo '<label class="form-label mb-0">' . htmlspecialchars($label) . '</label>';
        ProjectTask::dropdown($dropdown_params);
    }
}
