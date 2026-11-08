<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;

if (!isset($_GET["id"])) {
    $_GET["id"] = -1;
}

$doc          = new Document();

if (isset($_POST["add"])) {
    $doc->check(-1, CREATE, $_POST);
    if (isset($_POST['_filename']) && is_array($_POST['_filename'])) {
        $fic = $_POST['_filename'];
        $tag = $_POST['_tag_filename'];
        $prefix = $_POST['_prefix_filename'];
        foreach (array_keys($fic) as $key) {
            $_POST['_filename']        = [$fic[$key]];
            $_POST['_tag_filename']    = [$tag[$key]];
            $_POST['_prefix_filename'] = [$prefix[$key]];
            if ($newID = $doc->add($_POST)) {
                Event::log(
                    $newID,
                    "documents",
                    4,
                    "login",
                    sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $doc->fields["name"])
                );
            }
        }
        if ($_SESSION['glpibackcreated'] && (!isset($_POST['itemtype']) || !isset($_POST['items_id']))) {
            Html::redirect($doc->getLinkURL());
        }
    } elseif ($newID = $doc->add($_POST)) {
        Event::log(
            $newID,
            "documents",
            4,
            "login",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $doc->fields["name"])
        );
        // Not from item tab
        if ($_SESSION['glpibackcreated'] && (!isset($_POST['itemtype']) || !isset($_POST['items_id']))) {
            Html::redirect($doc->getLinkURL());
        }
    }

    Html::back();
} elseif (isset($_POST["delete"])) {
    $doc->check($_POST["id"], DELETE);

    if ($doc->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "documents",
            4,
            "document",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $doc->redirectToList();
} elseif (isset($_POST["restore"])) {
    $doc->check($_POST["id"], DELETE);

    if ($doc->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "documents",
            4,
            "document",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $doc->redirectToList();
} elseif (isset($_POST["purge"])) {
    $doc->check($_POST["id"], PURGE);

    if ($doc->delete($_POST, true)) {
        Event::log(
            $_POST["id"],
            "documents",
            4,
            "document",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $doc->redirectToList();
} elseif (isset($_POST["update"])) {
    $doc->check($_POST["id"], UPDATE);

    if ($doc->update($_POST)) {
        Event::log(
            $_POST["id"],
            "documents",
            4,
            "document",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["management", "document"];
    Document::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true",
    ]);
}
