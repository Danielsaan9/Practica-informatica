<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */
$rule = new Rule();
$rule->getFromDB(intval($_POST['rules_id']));

$action = new RuleAction($rule->fields['sub_type']);

if (isset($_POST["add"])) {
    $action->check(-1, CREATE, $_POST);
    $action->add($_POST);

    Html::back();
} elseif (isset($_POST["update"])) {
    $action->check($_POST['id'], UPDATE);
    $action->update($_POST);

    Html::back();
} elseif (isset($_POST["purge"])) {
    $action->check($_POST['id'], PURGE);
    $action->delete($_POST, true);

    Html::back();
}
