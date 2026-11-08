<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Asset\CustomFieldDefinition;

$custom_field = new CustomFieldDefinition();

if (isset($_POST["add"])) {
    $custom_field->check(-1, CREATE, $_POST);
    $custom_field->add($_POST);
} elseif (isset($_POST["update"])) {
    $custom_field->check($_POST['id'], UPDATE);
    $custom_field->update($_POST);
} elseif (isset($_POST["purge"])) {
    $custom_field->check($_POST['id'], PURGE);
    $custom_field->delete(['id' => $_POST['id']]);
}
Html::back();
