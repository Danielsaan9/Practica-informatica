<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

global $CFG_GLPI;

$alias = new NetworkAlias();

if (!isset($_GET["id"])) {
    $_GET["id"] = -1;
}
if (empty($_GET["networknames_id"])) {
    $_GET["networknames_id"] = "";
}

if (isset($_POST["add"])) {
    $alias->check(-1, CREATE, $_POST);

    if ($newID = $alias->add($_POST)) {
        Event::log(
            $newID,
            $alias::class,
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($alias->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $alias->check($_POST['id'], PURGE);
    $item = $alias->getItem();
    $alias->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "networkname",
        5,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    if ($item) {
        Html::redirect($item->getLinkURL());
    } else {
        Html::redirect($CFG_GLPI["root_doc"] . "/front/central.php");
    }
} elseif (isset($_POST["update"])) {
    $alias->check($_POST["id"], UPDATE);
    $alias->update($_POST);

    Event::log(
        $_POST["id"],
        $alias::class,
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
}

if (isset($_GET['_in_modal'])) {
    Html::popHeader(NetworkAlias::getTypeName(1));
    $alias->showForm($_GET["id"], $_GET);
    Html::popFooter();
} else {
    Session::checkRight(NetworkName::$rightname, UPDATE);

    $menus = ['assets'];
    NetworkAlias::displayFullPageForItem($_GET["id"], $menus, $_GET);
}
