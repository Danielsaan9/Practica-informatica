<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRightsOr(Software::$rightname, [READ, READ_ASSIGNED, READ_OWNED]);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["softwares_id"])) {
    $_GET["softwares_id"] = "";
}

$version = new SoftwareVersion();

if (isset($_POST["add"])) {
    $version->check(-1, CREATE, $_POST);

    if ($newID = $version->add($_POST)) {
        Event::log(
            $_POST['softwares_id'],
            "software",
            4,
            "inventory",
            //TRANS: %s is the user login, %2$s is the version id
            sprintf(__('%1$s adds the version %2$s'), $_SESSION["glpiname"], $newID)
        );
        Html::redirect(Software::getFormURLWithID($version->fields['softwares_id']));
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $version->check($_POST['id'], PURGE);
    $version->delete($_POST, true);
    Event::log(
        $version->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the version id
        sprintf(__('%1$s purges the version %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    Html::redirect(Software::getFormURLWithID($version->fields['softwares_id']));
} elseif (isset($_POST["update"])) {
    $version->check($_POST['id'], UPDATE);

    $version->update($_POST);
    Event::log(
        $version->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the version id
        sprintf(__('%1$s updates the version %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    Html::back();
} else {
    $menus = ["assets", "software"];
    SoftwareVersion::displayFullPageForItem($_GET["id"], $menus, [
        'softwares_id' => $_GET["softwares_id"],
    ]);
}
