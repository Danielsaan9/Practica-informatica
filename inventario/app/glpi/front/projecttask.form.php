<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

use Glpi\Event;

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["projects_id"])) {
    $_GET["projects_id"] = "";
}
if (!isset($_GET["projecttasks_id"])) {
    $_GET["projecttasks_id"] = "";
}
$task = new ProjectTask();

if (isset($_POST["add"])) {
    $task->check(-1, CREATE, $_POST);
    $task->add($_POST);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s adds a task'), $_SESSION["glpiname"])
    );
    if ($_SESSION['glpibackcreated']) {
        Html::redirect($task->getLinkURL());
    } else {
        Html::redirect(ProjectTask::getFormURL() . "?projects_id=" . $task->fields['projects_id']);
    }
} elseif (isset($_POST["restore"])) {
    $task->check($_POST["id"], DELETE);

    $task->restore($_POST);
    Event::log(
        $_POST["id"],
        "project",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s restores a task'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["delete"])) {
    $task->check($_POST['id'], DELETE);
    $task->delete($_POST);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s delete a task'), $_SESSION["glpiname"])
    );
    Html::redirect(Project::getFormURLWithID($task->fields['projects_id']));
} elseif (isset($_POST["purge"])) {
    $task->check($_POST['id'], PURGE);
    $task->delete($_POST, true);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s purges a task'), $_SESSION["glpiname"])
    );
    Html::redirect(Project::getFormURLWithID($task->fields['projects_id']));
} elseif (isset($_POST["update"])) {
    $task->check($_POST["id"], UPDATE);
    $task->update($_POST);

    Event::log(
        $task->fields['projects_id'],
        'project',
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s updates a task'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_GET['_in_modal'])) {
    Html::popHeader(ProjectTask::getTypeName(1), in_modal: true);
    $task->showForm($_GET["id"], ['withtemplate' => $_GET["withtemplate"]]);
    Html::popFooter();
} else {
    $menus = ["tools", "project"];
    ProjectTask::displayFullPageForItem($_GET['id'], $menus, $_GET);
}
