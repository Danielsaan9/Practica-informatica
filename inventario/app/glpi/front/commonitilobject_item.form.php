<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @var CommonDBTM $obj
 * @var CommonItilObject_Item $item_obj
 */

if (!($obj instanceof CommonDBTM) || !($item_obj instanceof CommonItilObject_Item)) {
    throw new BadRequestHttpException('Bad request');
}

$obj_fkey = $obj->getForeignKeyField();

if (isset($_POST["add"])) {
    if (isset($_POST['my_items']) && !empty($_POST['my_items'])) {
        // Split on last underscore to handle multi-underscore itemtype names
        $last_underscore = strrpos($_POST['my_items'], '_');
        if ($last_underscore === false) {
            throw new BadRequestHttpException('Bad request');
        }
        $_POST['itemtype'] = substr($_POST['my_items'], 0, $last_underscore);
        $_POST['items_id'] = substr($_POST['my_items'], $last_underscore + 1);
    }

    if (isset($_POST['add_items_id'])) {
        $_POST['items_id'] = $_POST['add_items_id'];
    }

    if (!isset($_POST['items_id']) || empty($_POST['items_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            _n('Associated element', 'Associated elements', 1)
        );
        Session::addMessageAfterRedirect(htmlescape($message), false, ERROR);
        Html::back();
    }

    $item_obj->check(-1, CREATE, $_POST);

    if ($item_obj->add($_POST)) {
        Event::log(
            $_POST[$obj_fkey],
            strtolower($obj::class),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    if (
        !$item_obj->getFromDBByCrit([
            $obj_fkey  => $_POST[$obj_fkey],
            'items_id' => $_POST['items_id'],
            'itemtype' => $_POST['itemtype'],
        ])
    ) {
        Html::back();
    }
    $item_obj->check($item_obj->getID(), PURGE);
    $item_obj->delete(['id' => $item_obj->getID()], true);
    Html::back();
}

throw new BadRequestHttpException();
