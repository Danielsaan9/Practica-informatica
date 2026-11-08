<?php

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;

use function Safe\json_encode;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!Session::haveRight(Rack::$rightname, UPDATE)) {
    throw new AccessDeniedHttpException();
}
if (!isset($_REQUEST['action'])) {
    throw new BadRequestHttpException();
}

$answer = [];
if (($_GET['action'] ?? null) === 'show_pdu_form') {
    $rack_id = (int) $_GET['racks_id'];

    $rack = new Rack();
    if (!$rack->can($rack_id, READ)) {
        throw new AccessDeniedHttpException();
    }

    PDU_Rack::showFirstForm($rack_id);
} elseif (($_GET['action'] ?? null) === 'show_rack_form' && isset($_GET['racks_id'])) {
    $rack = new Rack();
    if (isset($_GET['room']) && Rack::isNewID((int) $_GET['racks_id']) && $rack->canGlobal(CREATE)) {
        $room = new DCRoom();
        if ($room->can((int) $_GET['room'], READ)) {
            $rack->showForm(-1, [
                'dcrooms_id' => (int) $_GET['room'],
                'locations_id' => $room->fields['locations_id'],
                'position' => $_GET['position'],
            ]);
        }
    } elseif ($rack->can((int) $_GET['racks_id'], READ)) {
        $rack->showForm((int) $_GET['racks_id']);
    } else {
        throw new AccessDeniedHttpException();
    }
} elseif (isset($_POST['action'])) {
    header("Content-Type: application/json; charset=UTF-8", true);
    switch ($_POST['action']) {
        case 'move_item':
            $id = (int) $_POST['id'];

            $item_rack = new Item_Rack();
            if (!$item_rack->getFromDB($id) || !$item_rack->can($id, UPDATE)) {
                throw new AccessDeniedHttpException();
            }

            $answer['status'] = $item_rack->update([
                'id'       => $id,
                'position' => (int) $_POST['position'],
                'hpos'     => (int) $_POST['hpos'],
            ]);
            break;

        case 'move_pdu':
            $id = (int) $_POST['id'];

            $pdu_rack = new PDU_Rack();
            if (!$pdu_rack->getFromDB($id) || !$pdu_rack->can($id, UPDATE)) {
                throw new AccessDeniedHttpException();
            }

            $answer['status'] = $pdu_rack->update([
                'id'       => $id,
                'position' => (int) $_POST['position'],
            ]);
            break;

        case 'move_rack':
            $id = (int) $_POST['id'];

            $rack = new Rack();
            if (!$rack->getFromDB($id) || !$rack->can($id, UPDATE)) {
                throw new AccessDeniedHttpException();
            }

            $answer['status'] = $rack->update([
                'id'         => $id,
                'dcrooms_id' => (int) $_POST['dcrooms_id'],
                'position'   => (int) $_POST['x'] . "," . (int) $_POST['y'],
            ]);
            break;
    }

    echo json_encode($answer);
}
