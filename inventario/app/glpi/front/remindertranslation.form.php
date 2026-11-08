<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 9.5
 */

$translation = new ReminderTranslation();

if (isset($_POST['add'])) {
    $translation->check(-1, CREATE, $_POST);
    $translation->add($_POST);
    Html::back();
} elseif (isset($_POST['update'])) {
    $translation->check($_POST['id'], UPDATE, $_POST);
    $translation->update($_POST);
    Html::back();
} elseif (isset($_POST["purge"])) {
    $translation->check($_POST['id'], PURGE, $_POST);
    $translation->delete($_POST, true);
    Html::redirect(Reminder::getFormURLWithID($_POST['reminders_id']));
} elseif (isset($_GET["id"])) {
    $menus = ["tools", "remindertranslation"];
    ReminderTranslation::displayFullPageForItem($_GET['id'], $menus);
}
