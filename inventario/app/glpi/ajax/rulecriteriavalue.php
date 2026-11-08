<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

// Non define case
/** @var Rule $rule */
if (isset($_POST["sub_type"]) && ($rule = getItemForItemtype($_POST["sub_type"]))) {
    $value = '';
    if (isset($_POST['value'])) {
        $value = $_POST['value'];
    }
    $rule->displayCriteriaSelectPattern("pattern", $_POST["criteria"], $_POST['condition'], $value);
}
