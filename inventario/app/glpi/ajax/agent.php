<?php

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\NotFoundHttpException;

use function Safe\json_encode;

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    $agent = new Agent();
    if (!$agent->getFromDB($id)) {
        throw new NotFoundHttpException('Unable to load agent #' . $id);
    }
    if (!$agent->can($id, READ)) {
        throw new AccessDeniedHttpException();
    }
    $answer = [];

    Session::writeClose();
    switch ($_POST['action']) {
        case Agent::ACTION_INVENTORY:
            $answer = $agent->requestInventory();
            break;

        case Agent::ACTION_STATUS:
            $answer = $agent->requestStatus();
            break;
    }

    echo json_encode($answer);
}
