<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRightsOr(SoftwareLicense::$rightname, [READ, READ_ASSIGNED, READ_OWNED]);
if (!isset($_REQUEST["id"])) {
    $_REQUEST["id"] = "";
}

if (!isset($_REQUEST["softwares_id"])) {
    $_REQUEST["softwares_id"] = "";
}
if (!isset($_REQUEST["withtemplate"])) {
    $_REQUEST["withtemplate"] = "";
}
$license = new SoftwareLicense();

if (isset($_POST["add"])) {
    $license->check(-1, CREATE, $_POST);
    if ($newID = $license->add($_POST)) {
        Event::log(
            $_POST['softwares_id'],
            "software",
            4,
            "inventory",
            //TRANS: %s is the user login, %2$s is the license id
            sprintf(__('%1$s adds the license %2$s'), $_SESSION["glpiname"], $newID)
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($license->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["restore"])) {
    $license->check($_POST['id'], DELETE);
    if ($license->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "software",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $license->redirectToList();
} elseif (isset($_POST["delete"])) {
    $license->check($_POST['id'], DELETE);
    $license->delete($_POST);
    Event::log(
        $license->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the license id
        sprintf(__('%1$s deletes the license %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    $license->redirectToList();
} elseif (isset($_POST["purge"])) {
    $license->check($_POST['id'], PURGE);
    $license->delete($_POST, true);
    Event::log(
        $license->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the license id
        sprintf(__('%1$s purges the license %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    $license->redirectToList();
} elseif (isset($_POST["update"])) {
    $license->check($_POST['id'], UPDATE);

    $license->update($_POST);
    Event::log(
        $license->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the license id
        sprintf(__('%1$s updates the license %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    Html::back();
} else {
    $menus = ["management", "softwarelicense"];
    SoftwareLicense::displayFullPageForItem($_REQUEST['id'], $menus, $_REQUEST);
}
