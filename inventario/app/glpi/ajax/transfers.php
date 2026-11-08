<?php

global $CFG_GLPI;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(Transfer::$rightname, READ);

if (isset($_POST["id"]) && ($_POST["id"] > 0)) {
    $transfer = new Transfer();
    $transfer->showForm(
        $_POST["id"],
        ['target' => $CFG_GLPI["root_doc"] . "/front/transfer.action.php"]
    );
}
