<?php

use function Safe\json_encode;

header('Content-Type: application/json; charset=UTF-8');
Html::header_nocache();

$user = new User();
$success = $user->update(
    [
        'id'        => Session::getLoginUserID(),
        'fold_menu' => (bool) $_SESSION['glpifold_menu'] ? 0 : 1,
    ]
);

echo json_encode(['success' => $success]);
