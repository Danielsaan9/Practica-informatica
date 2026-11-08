<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;

Html::header(__('Transfer'), '', 'admin', 'rule', 'Transfer');

$transfer = new Transfer();

$transfer->checkGlobal(READ);

if (isset($_POST['transfer'])) {
    if (isset($_SESSION['glpitransfer_list'])) {
        if (!Session::haveAccessToEntity($_POST['to_entity'])) {
            throw new AccessDeniedHttpException();
        }
        $transfer->moveItems($_SESSION['glpitransfer_list'], $_POST['to_entity'], $_POST);
        unset($_SESSION['glpitransfer_list']);
        echo "<div class='fw-bold text-center'>" . __s('Operation successful') . "<br>";
        echo "<a href='central.php' role='button' class='btn btn-primary'>" . __s('Home') . "</a></div>";
        Html::footer();
        return;
    }
} elseif (isset($_POST['clear'])) {
    unset($_SESSION['glpitransfer_list']);
    echo "<div class='fw-bold text-center'>" . __s('Operation successful') . "<br>";
    echo "<a href='central.php' role='button' class='btn btn-primary'>" . __s('Home') . "</a></div>";
    echo "</div>";
    Html::footer();
    return;
}

unset($_SESSION['glpimassiveactionselected']);

$transfer->showTransferList();

Html::footer();
