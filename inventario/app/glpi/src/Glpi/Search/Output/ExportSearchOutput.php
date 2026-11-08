<?php

namespace Glpi\Search\Output;

use Glpi\Plugin\Hooks;
use Glpi\Search\SearchOption;
use Plugin;

/**
 *
 * @internal Not for use outside {@link Search} class and the "Glpi\Search" namespace.
 */
abstract class ExportSearchOutput extends AbstractSearchOutput
{
    /**
     * Generic Function to display Items
     *
     * @since 9.4: $num param has been dropped
     *
     * @param string  $itemtype item type
     * @param int $ID       ID of the SEARCH_OPTION item
     * @param array   $data     array retrieved data array
     *
     * @return string String to print
     **/
    public static function displayConfigItem($itemtype, $ID, $data = [])
    {

        SearchOption::getOptionsForItemtype($itemtype);

        // Plugin can override core definition for its type
        if ($plug = isPluginItemType($itemtype)) {
            $out = Plugin::doOneHook(
                $plug['plugin'],
                Hooks::AUTO_DISPLAY_CONFIG_ITEM,
                $itemtype,
                $ID,
                $data,
                "{$itemtype}_{$ID}"
            );
            if (!empty($out)) {
                return $out;
            }
        }

        return '';
    }
}
