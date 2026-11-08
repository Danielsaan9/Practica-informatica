<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * Following variables have to be defined before inclusion of this file:
 * @var RuleCollection $rulecollection
 */

use Glpi\Event;

$rule = $rulecollection->getRuleClass();
$rulecollection->checkGlobal(READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (isset($_POST["update"])) {
    $rulecollection->checkGlobal(UPDATE);
    $rule->check($_POST['id'], UPDATE);
    $rule->update($_POST);

    Event::log(
        $_POST['id'],
        "rules",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["add"])) {
    $rulecollection->checkGlobal(CREATE);

    if (isset($_POST['profiles_id']) && isset($_POST['entities_id']) && isset($_POST['is_recursive'])) {
        $entity = new Entity();
        $entity->executeAddRule($_POST);
    } else {
        $newID = $rule->add($_POST);
        Event::log(
            $newID,
            "rules",
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $newID)
        );
        Html::redirect($rule->getFormURLWithID($newID));
    }
} elseif (isset($_POST["purge"])) {
    $rulecollection->checkGlobal(PURGE);
    $rule->check($_POST['id'], PURGE);
    $rulecollection->deleteRuleOrder($_POST["ranking"]);
    $rule->delete($_POST, true);

    Event::log(
        $_POST["id"],
        "rules",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $rule->redirectToList();
}

$menus = ['admin', $rulecollection->menu_type, $rulecollection->menu_option];
$rule::displayFullPageForItem($_GET["id"], $menus, [
    'formoptions'  => " data-track-changes='true'",
]);
