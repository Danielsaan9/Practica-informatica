<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

global $CFG_GLPI;

/**
 * Following variables have to be defined before inclusion of this file:
 * @var Item_Devices $item_device
 */

/** @var Item_Devices|null $item_device */
if (!($item_device instanceof Item_Devices)) {
    throw new BadRequestHttpException();
}
if (!$item_device->canView()) {
    throw new AccessDeniedHttpException();
}


if (isset($_POST["id"])) {
    $_GET["id"] = $_POST["id"];
} elseif (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (isset($_POST["add"])) {
    $item_device->check(-1, CREATE, $_POST);
    if ($newID = $item_device->add($_POST)) {
        Event::log(
            $newID,
            get_class($item_device),
            4,
            "setup",
            sprintf(__('%1$s adds an item'), $_SESSION["glpiname"])
        );

        if ($_SESSION['glpibackcreated']) {
            Html::redirect($item_device->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $item_device->check($_POST["id"], PURGE);
    $item_device->delete($_POST, true);

    Event::log(
        $_POST["id"],
        get_class($item_device),
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );

    $device = $item_device->getOnePeer(1);
    Html::redirect($device->getLinkURL());
} elseif (isset($_POST["update"])) {
    $item_device->check($_POST["id"], UPDATE);
    $item_device->update($_POST);

    Event::log(
        $_POST["id"],
        get_class($item_device),
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    if (in_array($item_device::class, $CFG_GLPI['devices_in_menu'])) {
        $menus = ["assets", strtolower($item_device::class)];
    } else {
        $menus = ["config", "commondevice", $item_device::class];
    }

    $item_device::displayFullPageForItem($_GET["id"], $menus, $options ?? []);
}
