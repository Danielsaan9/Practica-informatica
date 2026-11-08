<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

$translation = new DropdownTranslation();
if (isset($_POST['add'])) {
    $translation->check(-1, CREATE, $_POST);
    $translation->add($_POST);
} elseif (isset($_POST['update'])) {
    $translation->check($_POST['id'], UPDATE, $_POST);
    $translation->update($_POST);
} elseif (isset($_POST['purge'])) {
    $translation->check($_POST['id'], PURGE, $_POST);
    $translation->delete($_POST, true);
}
Html::back();
