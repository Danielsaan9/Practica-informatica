<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

if (isset($_POST["sub_type"])) {
    $sub_type = $_POST["sub_type"];
} elseif (isset($_GET["sub_type"])) {
    $sub_type = $_GET["sub_type"];
} else {
    $sub_type = 0;
}

if (isset($_POST["condition"])) {
    $condition = $_POST["condition"];
} elseif (isset($_GET["condition"])) {
    $condition = $_GET["condition"];
} else {
    $condition = 0;
}

if (isset($_GET['refusedequipments_id'])) {
    $_POST['refusedequipments_id'] = $_GET['refusedequipments_id'];
}

$rulecollection = RuleCollection::getClassByType($sub_type);
if ($rulecollection->isRuleRecursive()) {
    $rulecollection->setEntity($_SESSION['glpiactive_entity']);
}
$rulecollection->checkGlobal(READ);

Html::popHeader(__('Setup'));

$rulecollection->showRulesEnginePreviewCriteriasForm($_POST, $condition);

if (isset($_POST["test_all_rules"])) {
    //Unset values that must not be processed by the rule
    unset($_POST["sub_type"], $_POST["test_all_rules"]);

    echo "<br>";
    $rulecollection->showRulesEnginePreviewResultsForm($_POST, $condition);
}

Html::popFooter();
