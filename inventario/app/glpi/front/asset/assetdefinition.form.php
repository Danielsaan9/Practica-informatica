<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Asset\AssetDefinition;
use Glpi\Event;

$asset_definition = new AssetDefinition();

if (isset($_POST['add'])) {
    $asset_definition->check(-1, CREATE, $_POST);

    if ($new_id = $asset_definition->add($_POST)) {
        Event::log(
            $new_id,
            AssetDefinition::class,
            4,
            'setup',
            sprintf(__('%1$s adds the item %2$s'), $_SESSION['glpiname'], $_POST['system_name'])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($asset_definition->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST['update'])) {
    $asset_definition->check($_POST['id'], UPDATE);

    if (
        (bool) ($_POST['_update_capacities'] ?? false)
        && !array_key_exists('capacities', $_POST)
    ) {
        // If no capacities are checked, `$_POST['capacities']` will not be sent in request.
        $_POST['capacities'] = [];
    }

    if (array_key_exists('profiles', $_POST)) {
        // Ensure profiles can be updated
        foreach (array_keys($_POST['profiles']) as $profile_id) {
            $profile = new Profile();
            $profile->check((int) $profile_id, UPDATE);
        }

        // Convert profiles input from the `components/checkbox_matrix.html.twig` format
        // to the expected format.
        foreach ($_POST['profiles'] as $profiles_id => $rights_matrix) {
            $combined_rights = 0;
            foreach ($rights_matrix as $right_value => $is_enabled) {
                if ($is_enabled) {
                    $combined_rights |= (int) $right_value;
                }
            }
            $_POST['profiles'][$profiles_id] = $combined_rights;
        }
    }

    if ($asset_definition->update($_POST)) {
        Event::log(
            $_POST['id'],
            AssetDefinition::class,
            4,
            'setup',
            sprintf(__('%s updates an item'), $_SESSION['glpiname'])
        );
    }
    Html::back();
} elseif (isset($_POST['purge'])) {
    $asset_definition->check($_POST['id'], PURGE);
    if ($asset_definition->delete($_POST)) {
        Event::log(
            $_POST['id'],
            AssetDefinition::class,
            4,
            'setup',
            sprintf(__('%s purges an item'), $_SESSION['glpiname'])
        );
    }
    $asset_definition->redirectToList();
} else {
    $menus = ['config', AssetDefinition::class];
    AssetDefinition::displayFullPageForItem($_GET['id'] ?? 0, $menus);
}
