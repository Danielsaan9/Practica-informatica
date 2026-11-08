<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 9.2
 */

use Glpi\Event;

$item = new OlaLevel();

if (isset($_POST["update"])) {
    $item->check($_POST["id"], UPDATE);

    $item->update($_POST);

    Event::log(
        $_POST["id"],
        "olas",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an ola level'), $_SESSION["glpiname"])
    );

    Html::back();
} elseif (isset($_POST["add"])) {
    $item->check(-1, CREATE, $_POST);

    if ($item->add($_POST)) {
        Event::log(
            $_POST["olas_id"],
            "olas",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($item->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    if (isset($_POST['id'])) {
        $item->check($_POST['id'], PURGE);
        if ($item->delete($_POST, true)) {
            Event::log(
                $_POST["id"],
                "olas",
                4,
                "setup",
                //TRANS: %s is the user login
                sprintf(__('%s purges an ola level'), $_SESSION["glpiname"])
            );
        }
        $item->redirectToList();
    }

    Html::back();
} elseif (isset($_GET["id"]) && ($_GET["id"] > 0)) {
    $menus = ["config", "slm", "OlaLevel"];
    OlaLevel::displayFullPageForItem($_GET["id"], $menus);
}
