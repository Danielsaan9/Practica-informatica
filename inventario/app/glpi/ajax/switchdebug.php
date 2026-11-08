<?php

(new Config())->checkGlobal(UPDATE);

if (isset($_POST['mode']) && in_array($_POST['mode'], [
    Session::NORMAL_MODE,
    Session::DEBUG_MODE,
])) {
    // Mode was manually specified
    $mode = $_POST['mode'];
} else {
    // Toggle
    $mode = ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE ? Session::NORMAL_MODE : Session::DEBUG_MODE);
}

$user = new User();
$user->update(
    [
        'id'        => Session::getLoginUserID(),
        'use_mode'  => $mode,
    ]
);
Session::addMessageAfterRedirect(
    $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE
     ? __s('Debug mode has been enabled!')
     : __s('Debug mode has been disabled!')
);

Html::back();
