<?php

use function Safe\json_encode;

header('Content-Type: application/json; charset=UTF-8');
Html::header_nocache();

$success = false;

$user = new User();
if (
    array_key_exists('itemtype', $_POST)
    && is_string($_POST['itemtype'])
    && $user->getFromDB(Session::getLoginUserID())
    && $user->toggleSavedSearchPin($_POST['itemtype'])
) {
    $success = true;
}

echo json_encode(['success' => $success]);
