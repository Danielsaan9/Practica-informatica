<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

Session::checkCentralAccess();
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action = "import";
}

$rulecollection = new RuleCollection();
$rulecollection->checkGlobal(READ);

if ($action !== "export") {
    Html::header(Rule::getTypeName(Session::getPluralNumber()), '', "admin", "rule");
}

switch ($action) {
    case "preview_import":
        $rulecollection->checkGlobal(UPDATE);
        if (RuleCollection::previewImportRules()) {
            break;
        }
        // seems wanted not to break; I do no understand why

        // no break
    case "import":
        $rulecollection->checkGlobal(UPDATE);
        RuleCollection::displayImportRulesForm();
        break;

    case "export":
        $rule = new Rule();
        if (isset($_SESSION['exportitems'])) {
            $rules_key = array_keys($_SESSION['exportitems']);
        } else {
            $rules_key = array_keys($rule->find(getEntitiesRestrictCriteria()));
        }
        $rulecollection::exportRulesToXML($rules_key);
        unset($_SESSION['exportitems']);
        break;

    case "download":
        echo "<div class='center'>";
        $itemtype = $_REQUEST['itemtype'];
        echo "<a href='" . htmlescape($itemtype::getSearchURL()) . "'>" . __s('Back') . "</a>";
        echo "</div>";
        Html::redirect("rule.backup.php?action=export&itemtype=" . urlencode($_REQUEST['itemtype']));
        // no break
    case "process_import":
        $rulecollection->checkGlobal(UPDATE);
        RuleCollection::processImportRules();
        Html::back();
}
if ($action !== "export") {
    Html::footer();
}
