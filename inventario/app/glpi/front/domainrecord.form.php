<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

if (empty($_GET["id"])) {
    $_GET["id"] = '';
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = '';
}

$record = new DomainRecord();

if (isset($_POST["add"])) {
    $record->check(-1, CREATE, $_POST);
    $newID = $record->add($_POST);
    if ($_SESSION['glpibackcreated'] && !isset($_POST['_in_modal'])) {
        Html::redirect($record->getFormURLWithID($newID));
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $record->check($_POST['id'], DELETE);
    $record->delete($_POST);
    $record->redirectToList();
} elseif (isset($_POST["restore"])) {
    $record->check($_POST['id'], PURGE);
    $record->restore($_POST);
    $record->redirectToList();
} elseif (isset($_POST["purge"])) {
    $record->check($_POST['id'], PURGE);
    $record->delete($_POST, true);
    $record->redirectToList();
} elseif (isset($_POST["update"])) {
    $record->check($_POST['id'], UPDATE);
    $record->update($_POST);
    Html::back();
} elseif (isset($_GET['_in_modal'])) {
    Html::popHeader(DomainRecord::getTypeName(Session::getPluralNumber()), in_modal: true);
    $record->showForm($_GET["id"], ['domains_id' => $_GET['domains_id'] ?? null]);
    Html::popFooter();
} else {
    $menus = ["management", "domain", "DomainRecord"];
    $options = [
        'withtemplate' => $_GET["withtemplate"],
    ];
    if (isset($_GET['domains_id'])) {
        $options['domains_id'] = $_GET['domains_id'];
    }
    DomainRecord::displayFullPageForItem($_GET["id"], $menus, $options);
}
