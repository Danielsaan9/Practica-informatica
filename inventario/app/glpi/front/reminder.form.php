<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
$remind = new Reminder();

if (isset($_POST["add"])) {
    $remind->check(-1, CREATE, $_POST);

    if ($newID = $remind->add($_POST)) {
        Event::log(
            $newID,
            "reminder",
            4,
            "tools",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($remind->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $remind->check($_POST["id"], PURGE);
    $remind->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "reminder",
        4,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    if (!isset($_POST["from_planning_edit_ajax"])) {
        $remind->redirectToList();
    } else {
        Html::back();
    }
} elseif (isset($_POST["update"])) {
    $remind->check($_POST["id"], UPDATE);   // Right to update the reminder

    $remind->update($_POST);
    Event::log(
        $_POST["id"],
        "reminder",
        4,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["addvisibility"])) {
    if (
        isset($_POST["_type"]) && !empty($_POST["_type"])
        && isset($_POST["reminders_id"]) && $_POST["reminders_id"]
    ) {
        if (array_key_exists('entities_id', $_POST) && $_POST['entities_id'] == -1) {
            // "No restriction" value selected
            $_POST['entities_id'] = 'NULL';
            $_POST['no_entity_restriction'] = 1;
        }
        $item = null;
        switch ($_POST["_type"]) {
            case 'User':
                if (isset($_POST['users_id']) && $_POST['users_id']) {
                    $item = new Reminder_User();
                }
                break;

            case 'Group':
                if (isset($_POST['groups_id']) && $_POST['groups_id']) {
                    $item = new Group_Reminder();
                }
                break;

            case 'Profile':
                if (isset($_POST['profiles_id']) && $_POST['profiles_id']) {
                    $item = new Profile_Reminder();
                }
                break;

            case 'Entity':
                $item = new Entity_Reminder();
                break;
        }
        if (!is_null($item)) {
            $item->check(-1, CREATE, $_POST);
            $item->add($_POST);
            Event::log(
                $_POST["reminders_id"],
                "reminder",
                4,
                "tools",
                //TRANS: %s is the user login
                sprintf(__('%s adds a target'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
} else {
    $menus = [
        'central'  => ["tools", "reminder"],
        'helpdesk' => [],
    ];
    Reminder::displayFullPageForItem($_GET["id"], $menus);
}
