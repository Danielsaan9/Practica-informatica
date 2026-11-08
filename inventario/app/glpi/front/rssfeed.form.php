<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.84
 */

use Glpi\Event;

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
$rssfeed = new RSSFeed();

if (isset($_POST["add"])) {
    $rssfeed->check(-1, CREATE, $_POST);

    $newID = $rssfeed->add($_POST);
    Event::log(
        $newID,
        "rssfeed",
        4,
        "tools",
        sprintf(
            __('%1$s adds the item %2$s'),
            $_SESSION["glpiname"],
            $rssfeed->fields["name"]
        )
    );
    Html::redirect($rssfeed->getFormURLWithID($newID));
} elseif (isset($_POST["purge"])) {
    $rssfeed->check($_POST["id"], PURGE);
    $rssfeed->delete($_POST, true);
    Event::log(
        $_POST["id"],
        "rssfeed",
        4,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $rssfeed->redirectToList();
} elseif (isset($_POST["update"])) {
    $rssfeed->check($_POST["id"], UPDATE);   // Right to update the rssfeed

    $rssfeed->update($_POST);
    Event::log(
        $_POST["id"],
        "rssfeed",
        4,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["addvisibility"])) {
    if (
        isset($_POST["_type"]) && !empty($_POST["_type"])
        && isset($_POST["rssfeeds_id"]) && $_POST["rssfeeds_id"]
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
                    $item = new RSSFeed_User();
                }
                break;

            case 'Group':
                if (isset($_POST['groups_id']) && $_POST['groups_id']) {
                    $item = new Group_RSSFeed();
                }
                break;

            case 'Profile':
                if (isset($_POST['profiles_id']) && $_POST['profiles_id']) {
                    $item = new Profile_RSSFeed();
                }
                break;

            case 'Entity':
                $item = new Entity_RSSFeed();
                break;
        }
        if (!is_null($item)) {
            $item->check(-1, CREATE, $_POST);
            $item->add($_POST);
            Event::log(
                $_POST["rssfeeds_id"],
                "rssfeed",
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
        'central'  => ["tools", "rssfeed"],
        'helpdesk' => [],
    ];
    RSSFeed::displayFullPageForItem($_GET["id"], $menus);
}
