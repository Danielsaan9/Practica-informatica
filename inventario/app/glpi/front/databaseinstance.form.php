<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

Session::checkRight(DatabaseInstance::$rightname, READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$instance = new DatabaseInstance();
if (isset($_POST["add"])) {
    if (isset($_POST['databaseinstances_id']) && (int) $_POST['databaseinstances_id'] > 0) {
        $instance->check($_POST['databaseinstances_id'], UPDATE);

        if ($instance->update([
            'id'       => $_POST['databaseinstances_id'],
            'items_id' => ($_POST['items_id'] ?? 0),
            'itemtype' => $_POST['itemtype'],
        ])) {
            Event::log(
                $_POST['databaseinstances_id'],
                "databaseinstance",
                4,
                "management",
                //TRANS: %s is the user login
                sprintf(__('%s links a database instance'), $_SESSION["glpiname"])
            );
        }
        Html::back();
    } else {
        $instance->check(-1, CREATE, $_POST);

        if ($newID = $instance->add($_POST)) {
            Event::log(
                $newID,
                "databaseinstance",
                4,
                "management",
                //TRANS: %s is the user login
                sprintf(__('%s adds a database instance'), $_SESSION["glpiname"])
            );
            if ($_SESSION['glpibackcreated']) {
                Html::redirect($instance->getLinkURL());
            }
        }
        Html::back();
    }
} elseif (isset($_POST["delete"])) {
    $instance->check($_POST['id'], DELETE);
    $ok = $instance->delete($_POST);
    if ($ok) {
        Event::log(
            $_POST["id"],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s deletes a database instance'), $_SESSION["glpiname"])
        );
    }
    $instance->redirectToList();
} elseif (isset($_POST["restore"])) {
    $instance->check($_POST['id'], DELETE);
    if ($instance->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s restores a database instance'), $_SESSION["glpiname"])
        );
    }
    $instance->redirectToList();
} elseif (isset($_POST["purge"])) {
    $instance->check($_POST["id"], PURGE);

    if ($instance->delete($_POST, true)) {
        Event::log(
            $_POST['id'],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s purges a database instance'), $_SESSION["glpiname"])
        );
    }
    $instance->redirectToList();
} elseif (isset($_POST["update"])) {
    $instance->check($_POST["id"], UPDATE);

    if ($instance->update($_POST)) {
        Event::log(
            $_POST['id'],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s updates a database instance'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["management", "database", "DatabaseInstance"];
    DatabaseInstance::displayFullPageForItem($_GET['id'], $menus, [
        'withtemplate' => $_GET['withtemplate'],
    ]);
}
