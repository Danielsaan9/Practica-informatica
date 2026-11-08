<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\NotFoundHttpException;

Session::checkRight(NetworkName::$rightname, READ);

$nn = new NetworkName();

if (isset($_POST["add"])) {
    $nn->check(-1, CREATE, $_POST);

    if ($newID = $nn->add($_POST)) {
        Event::log(
            $newID,
            "networkname",
            5,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s adds an item'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($nn->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $nn->check($_POST["id"], DELETE);
    $nn->delete($_POST);

    Event::log(
        $_POST["id"],
        $_POST['itemtype'],
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    if ($_SESSION['glpibackcreated']) {
        Html::redirect($nn->getLinkURL());
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $nn->check($_POST['id'], PURGE);
    $nn->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "networkname",
        5,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    if ($node = getItemForItemtype($nn->fields["itemtype"])) {
        if ($node->can($nn->fields["items_id"], READ)) {
            Html::redirect($node->getLinkURL());
        }
    }
    $nn->redirectToList();
} elseif (isset($_POST["update"])) {
    $nn->check($_POST['id'], UPDATE);
    $nn->update($_POST);
    Event::log(
        $_POST["id"],
        "networkname",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["unaffect"])) {
    $nn->check($_POST['id'], UPDATE);
    $nn->unaffectAddressByID($_POST['id']);
    Event::log(
        $_POST["id"],
        "networkname",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    if ($node = getItemForItemtype($nn->fields["itemtype"])) {
        if ($node->can($nn->fields["items_id"], READ)) {
            Html::redirect($node->getLinkURL());
        }
    }
    $nn->redirectToList();
} elseif (isset($_POST['assign_address'])) { // From NetworkPort or NetworkEquipement
    $nn->check($_POST['addressID'], UPDATE);

    if ((!empty($_POST['itemtype'])) && (!empty($_POST['items_id']))) {
        if ($node = getItemForItemtype($_POST['itemtype'])) {
            $node->check($_POST['items_id'], UPDATE);
        }
        NetworkName::affectAddress($_POST['addressID'], $_POST['items_id'], $_POST['itemtype']);
        Event::log(
            0,
            "networkport",
            5,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s associates a network name to an item'), $_SESSION["glpiname"])
        );
        Html::back();
    } else {
        throw new NotFoundHttpException();
    }
} else {
    if (!isset($_GET["id"])) {
        $_GET["id"] = "";
    }
    if (empty($_GET["items_id"])) {
        $_GET["items_id"] = "";
    }
    if (empty($_GET["itemtype"])) {
        $_GET["itemtype"] = "";
    }

    $menus = ['config', 'commondropdown', 'NetworkName'];
    NetworkName::displayFullPageForItem($_GET["id"], $menus, $_GET);
}
