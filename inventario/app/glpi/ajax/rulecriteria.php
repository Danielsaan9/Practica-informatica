<?php

global $CFG_GLPI;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

/** @var Rule $rule */
if (isset($_POST["sub_type"]) && ($rule = getItemForItemtype($_POST["sub_type"]))) {
    $criterias = $rule->getAllCriteria();

    if (count($criterias)) {
        // First include -> first of the predefined array
        if (!isset($_POST["criteria"])) {
            $_POST["criteria"] = key($criterias);
        }

        $allow_condition = $criterias[$_POST["criteria"]]['allow_condition'] ?? [];

        $condparam = ['criterion'        => $_POST["criteria"],
            'allow_conditions' => $allow_condition,
        ];
        if (isset($_POST['condition'])) {
            $condparam['value'] = $_POST['condition'];
        }
        $randcrit = (int) RuleCriteria::dropdownConditions($_POST["sub_type"], $condparam);
        echo "<span id='condition_span$randcrit' class='d-inline-block'></span>";

        $paramscriteria = ['condition' => '__VALUE__',
            'criteria'  => $_POST["criteria"],
            'sub_type'  => $_POST["sub_type"],
        ];

        Ajax::updateItemOnSelectEvent(
            "dropdown_condition$randcrit",
            "condition_span$randcrit",
            $CFG_GLPI["root_doc"] . "/ajax/rulecriteriavalue.php",
            $paramscriteria
        );

        if (isset($_POST['pattern'])) {
            $paramscriteria['value'] = $_POST['pattern'];
        }

        Ajax::updateItem(
            "condition_span$randcrit",
            $CFG_GLPI["root_doc"] . "/ajax/rulecriteriavalue.php",
            $paramscriteria,
            "dropdown_condition$randcrit"
        );
    }
}
