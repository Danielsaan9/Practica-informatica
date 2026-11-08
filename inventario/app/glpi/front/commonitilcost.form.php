<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

/**
 * Following variables have to be defined before inclusion of this file:
 * @var CommonITILCost $cost
 */

Session::checkCentralAccess();
if (!($cost instanceof CommonITILCost)) {
    throw new BadRequestHttpException();
}
if (!$cost->canView()) {
    throw new AccessDeniedHttpException();
}
$itemtype = $cost->getItilObjectItemType();
$fk       = getForeignKeyFieldForItemType($itemtype);


if (isset($_POST["add"])) {
    $cost->check(-1, CREATE, $_POST);

    if ($cost->add($_POST)) {
        Event::log(
            $_POST[$fk],
            strtolower($itemtype),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s adds a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $cost->check($_POST["id"], PURGE);
    if ($cost->delete($_POST, true)) {
        Event::log(
            $cost->fields[$fk],
            strtolower($itemtype),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s purges a cost'), $_SESSION["glpiname"])
        );
    }
    Html::redirect($itemtype::getFormURLWithID($cost->fields[$fk]));
} elseif (isset($_POST["update"])) {
    $cost->check($_POST["id"], UPDATE);

    if ($cost->update($_POST)) {
        Event::log(
            $cost->fields[$fk],
            strtolower($itemtype),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s updates a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
