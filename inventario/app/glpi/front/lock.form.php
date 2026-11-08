<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Plugin\Hooks;

global $CFG_GLPI;

/**
 * @since 0.84
 */

if (isset($_POST['itemtype'])) {
    $source_item = getItemForItemtype($_POST['itemtype']);
    if ($source_item->can($_POST['id'], UPDATE)) {
        $devices = Item_Devices::getDeviceTypes();
        $actions = array_merge($CFG_GLPI['inventory_lockable_objects'], array_values($devices));

        if (isset($_POST["unlock"])) {
            foreach ($actions as $type) {
                if (isset($_POST[$type]) && count($_POST[$type])) {
                    $item = getItemForItemtype($type);
                    foreach (array_keys($_POST[$type]) as $key) {
                        if (!$item->can($key, UPDATE)) {
                            Session::addMessageAfterRedirect(
                                htmlescape(sprintf(
                                    __('You do not have rights to restore %s item.'),
                                    $type
                                )),
                                true,
                                ERROR
                            );
                            continue;
                        }

                        //Force unlock
                        $item->restore(['id' => $key]);
                    }
                }
            }

            //Execute hook to unlock fields managed by a plugin, if needed
            Plugin::doHookFunction(Hooks::UNLOCK_FIELDS, $_POST);
        } elseif (isset($_POST["purge"])) {
            foreach ($actions as $type) {
                if (isset($_POST[$type]) && count($_POST[$type])) {
                    $item = getItemForItemtype($type);
                    foreach (array_keys($_POST[$type]) as $key) {
                        if (!$item->can($key, PURGE)) {
                            Session::addMessageAfterRedirect(
                                htmlescape(sprintf(
                                    __('You do not have rights to delete %s item.'),
                                    $type
                                )),
                                true,
                                ERROR
                            );
                            continue;
                        }

                        //Force unlock
                        $item->delete(['id' => $key], true);
                    }
                }
            }
        }
    }
}

Html::back();
