<?php

use Safe\Exceptions\JsonException;

use function Safe\json_encode;

header('Content-Type: application/json; charset=UTF-8');
Html::header_nocache();

$raw_itillayout  = $_POST['itil_layout'];

try {
    $json_itillayout = json_encode($raw_itillayout);
} catch (JsonException $e) {
    return;
}

$user = new User();
$success = $user->update(
    [
        'id' => Session::getLoginUserID(),
        'itil_layout' => $json_itillayout,
    ]
);
echo json_encode(['success' => $success]);
