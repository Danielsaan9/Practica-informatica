<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;

Session::checkCentralAccess();

if (isset($_POST["sub_type"])) {
    $sub_type = $_POST["sub_type"];
} elseif (isset($_GET["sub_type"])) {
    $sub_type = $_GET["sub_type"];
} else {
    $sub_type = 0;
}

if (isset($_POST["rules_id"])) {
    $rules_id = $_POST["rules_id"];
} elseif (isset($_GET["rules_id"])) {
    $rules_id = $_GET["rules_id"];
} else {
    $rules_id = 0;
}

/** @var Rule $rule */
if (!$rule = getItemForItemtype($sub_type)) {
    throw new BadRequestHttpException();
}
$rule->checkGlobal(READ);

$in_modal = isset($_REQUEST['_in_modal']) ? (bool) $_REQUEST['_in_modal'] : false;
Html::popHeader(__('Setup'), '', $in_modal);

$rule->showRulePreviewCriteriasForm($rules_id);

if (isset($_POST["test_rule"])) {
    $params = [];
    //Unset values that must not be processed by the rule
    unset($_POST["test_rule"], $_POST["rules_id"], $_POST["sub_type"]);
    $rule->getRuleWithCriteriasAndActions($rules_id, true, true);

    //Add rules specific POST fields to the param array
    $params = $rule->addSpecificParamsForPreview($params);

    $input = $rule->prepareAllInputDataForProcess($_POST, $params);
    //$rule->regex_results = array();
    echo "<br>";
    $rule->showRulePreviewResultsForm($input, $params);
}

Html::popFooter();
