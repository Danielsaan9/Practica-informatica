<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (!isset($_GET["itemtype"])) {
    $_GET["itemtype"] = "";
}

if (!isset($_GET["items_id"])) {
    $_GET["items_id"] = "";
}

$item_vm = new ItemVirtualMachine();
if (isset($_POST["add"])) {
    $item_vm->check(-1, CREATE, $_POST);

    if ($item_vm->add($_POST)) {
        Event::log(
            $_POST['items_id'],
            $_POST['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s adds a virtual machine'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($item_vm->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $item_vm->check($_POST["id"], DELETE);
    $item_vm->delete($_POST);

    Event::log(
        $_POST["id"],
        $_POST['itemtype'],
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $asset = getItemForItemtype($_POST['itemtype']);
    $asset->getFromDB($item_vm->fields['items_id']);
    Html::redirect($asset->getFormURLWithID($item_vm->fields['items_id'])
                  . ($asset->fields['is_template'] ? "&withtemplate=1" : ""));
} elseif (isset($_POST["purge"])) {
    $item_vm->check($_POST["id"], PURGE);

    if ($item_vm->delete($_POST, true)) {
        Event::log(
            $item_vm->fields['items_id'],
            $item_vm->fields['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges a virtual machine'), $_SESSION["glpiname"])
        );
    }
    $asset = getItemForItemtype($item_vm->fields['itemtype']);
    $asset->getFromDB($item_vm->fields['items_id']);
    Html::redirect($asset->getFormURLWithID($item_vm->fields['items_id'])
                  . ($asset->fields['is_template'] ? "&withtemplate=1" : ""));
} elseif (isset($_POST["update"])) {
    $item_vm->check($_POST["id"], UPDATE);

    if ($item_vm->update($_POST)) {
        Event::log(
            $item_vm->fields['items_id'],
            $item_vm->fields['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates a virtual machine'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["restore"])) {
    $item_vm->check($_POST['id'], DELETE);
    if ($item_vm->restore($_POST)) {
        Event::log(
            $_POST["id"],
            $_POST['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores a virtual machine'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    if ($item_vm->getFromDB($_GET['id'])) {
        $menus = ['assets', $item_vm->fields['itemtype']];
    } else {
        $menus = ['assets', $_GET['itemtype']];
    }

    ItemVirtualMachine::displayFullPageForItem($_GET["id"], $menus, [
        'itemtype' => $_GET['itemtype'],
        'items_id' => $_GET["items_id"],
    ]);
}
