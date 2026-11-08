<?php

use Glpi\Exception\Http\BadRequestHttpException;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

// Should be defined by other files that include this file.
// See: change_item.php, item_problem.php, item_ticket.php and item_ticketrecurrent.php
$obj ??= null;
$item_obj ??= null;
$valid_obj = $obj instanceof CommonITILObject || $obj instanceof CommonITILRecurrent;
if (!$valid_obj || !($item_obj instanceof CommonItilObject_Item)) {
    throw new BadRequestHttpException();
}

switch ($_POST['action']) {
    case 'add':
        if (!empty($_POST['my_items'])) {
            $last_underscore = strrpos($_POST['my_items'], '_');
            if ($last_underscore === false) {
                throw new BadRequestHttpException();
            }
            $_POST['itemtype'] = substr($_POST['my_items'], 0, $last_underscore);
            $_POST['items_id'] = substr($_POST['my_items'], $last_underscore + 1);
        }
        if (isset($_POST['itemtype']) && !empty($_POST['items_id'])) {
            $_POST['params']['items_id'][$_POST['itemtype']][$_POST['items_id']] = $_POST['items_id'];
        }
        $item_obj::itemAddForm($obj, $_POST['params'] ?? []);
        break;

    case 'delete':
        if (isset($_POST['itemtype']) && !empty($_POST['items_id'])) {
            $deleted = true;
            if ($_POST['params']['id'] > 0) {
                $criteria = [
                    $obj::getForeignKeyField() => $_POST['params']['id'],
                    'items_id' => $_POST['items_id'],
                    'itemtype' => $_POST['itemtype'],
                ];
                if (
                    $item_obj->getFromDBByCrit($criteria)
                    && $item_obj->can($item_obj->getID(), DELETE)
                ) {
                    $deleted = $item_obj->delete(['id' => $item_obj->getID()]);
                }
            }
            if ($deleted) {
                unset($_POST['params']['items_id'][$_POST['itemtype']][array_search($_POST['items_id'], $_POST['params']['items_id'][$_POST['itemtype']])]);
            }
            $item_obj::itemAddForm($obj, $_POST['params'] ?? []);
        }

        break;
}
