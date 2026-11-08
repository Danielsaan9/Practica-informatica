<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(Rack::$rightname, READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$rack = new Rack();

if (isset($_POST["add"])) {
    $rack->check(-1, CREATE, $_POST);

    if ($newID = $rack->add($_POST)) {
        Event::log(
            $newID,
            "racks",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($rack->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $rack->check($_POST["id"], DELETE);
    $rack->delete($_POST);

    Event::log(
        $_POST["id"],
        "racks",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $rack->redirectToList();
} elseif (isset($_POST["restore"])) {
    $rack->check($_POST["id"], DELETE);

    $rack->restore($_POST);
    Event::log(
        $_POST["id"],
        "racks",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $rack->redirectToList();
} elseif (isset($_POST["purge"])) {
    $rack->check($_POST["id"], PURGE);

    $rack->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "racks",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $rack->redirectToList();
} elseif (isset($_POST["update"])) {
    $rack->check($_POST["id"], UPDATE);

    $rack->update($_POST);
    Event::log(
        $_POST["id"],
        "racks",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $options = [
        'id'           => $_GET['id'],
        'withtemplate' => $_GET['withtemplate'],
        'formoptions'  => "data-track-changes=true",
    ];
    if (isset($_GET['position'])) {
        $options['position'] = $_GET['position'];
    }
    if (isset($_GET['room'])) {
        $room = new DCRoom();
        if ($room->getFromDB((int) $_GET['room'])) {
            $options['dcrooms_id']   = $room->getID();
            $options['locations_id'] = $room->fields['locations_id'];
        }
    }

    if ($_REQUEST['ajax'] ?? false) {
        $rack->display($options);
    } else {
        $menus = ["assets", "rack"];
        Rack::displayFullPageForItem($_GET["id"], $menus, $options);
    }
}
