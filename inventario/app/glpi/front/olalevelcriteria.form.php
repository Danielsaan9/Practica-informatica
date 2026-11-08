<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 9.2
 */
$criteria = new OlaLevelCriteria();

if (isset($_POST["add"])) {
    $criteria->check(-1, CREATE, $_POST);
    $criteria->add($_POST);

    Html::back();
} elseif (isset($_POST["update"])) {
    $criteria->check($_POST['id'], UPDATE);
    $criteria->update($_POST);

    Html::back();
} elseif (isset($_POST["purge"])) {
    $criteria->check($_POST['id'], PURGE);
    $criteria->delete($_POST, true);

    Html::back();
}
