<?php

use function Safe\json_encode;

Session::checkRight(Config::$rightname, UPDATE);

if (isset($_POST["test_smtp_send"])) {
    $result = NotificationMailing::testNotification();
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($result);
}
