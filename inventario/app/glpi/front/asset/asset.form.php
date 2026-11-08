<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Asset\Asset;
use Glpi\Asset\AssetDefinition;
use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;

if (array_key_exists('id', $_REQUEST) && !Asset::isNewId($_REQUEST['id'])) {
    $asset = Asset::getById($_REQUEST['id']);
    if (!$asset instanceof Asset) {
        $asset = null;
    }
} else {
    $definition = new AssetDefinition();
    $classname  = array_key_exists('class', $_GET) && $definition->getFromDBBySystemName((string) $_GET['class'])
        ? $definition->getAssetClassName()
        : null;
    $asset      = $classname !== null && is_a($classname, Asset::class, true)
        ? new $classname()
        : null;
}

if ($asset === null) {
    throw new BadRequestHttpException('Bad request');
}

Session::checkRightsOr($asset::$rightname, [READ, READ_ASSIGNED, READ_OWNED]);

if (isset($_POST['add'])) {
    $asset->check(-1, CREATE, $_POST);

    if ($new_id = $asset->add($_POST)) {
        Event::log(
            $new_id,
            $asset::class,
            4,
            'inventory',
            sprintf(__('%1$s adds the item %2$s'), $_SESSION['glpiname'], $_POST['name'])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($asset->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST['update'])) {
    $asset->check($_POST['id'], UPDATE);
    if ($asset->update($_POST)) {
        Event::log(
            $_POST['id'],
            $asset::class,
            4,
            'inventory',
            sprintf(__('%s updates an item'), $_SESSION['glpiname'])
        );
    }
    Html::back();
} elseif (isset($_POST['delete'])) {
    $asset->check($_POST['id'], DELETE);
    if ($asset->delete($_POST)) {
        Event::log(
            $_POST['id'],
            $asset::class,
            4,
            'inventory',
            sprintf(__('%s deletes an item'), $_SESSION['glpiname'])
        );
    }
    $asset->redirectToList();
} elseif (isset($_POST['purge'])) {
    $asset->check($_POST['id'], PURGE);
    if ($asset->delete($_POST, true)) {
        Event::log(
            $_POST['id'],
            $asset::class,
            4,
            'inventory',
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $asset->redirectToList();
} elseif (isset($_POST['restore'])) {
    $asset->check($_POST['id'], DELETE);
    if ($asset->restore($_POST)) {
        Event::log(
            $_POST['id'],
            $asset::class,
            4,
            'inventory',
            sprintf(__('%s restores an item'), $_SESSION['glpiname'])
        );
    }
    $asset->redirectToList();
} else {
    $id = (int) ($_GET['id'] ?? null);
    $menus = ['assets', $asset::class];
    $asset::displayFullPageForItem($id, $menus, [
        AssetDefinition::getForeignKeyField() => $asset::getDefinition()->getID(),
        'withtemplate' => $_GET["withtemplate"] ?? '',
        'formoptions'  => "data-track-changes=true",
    ]);
}
