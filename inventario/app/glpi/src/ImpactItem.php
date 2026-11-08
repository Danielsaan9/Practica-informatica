<?php

/**
 * @since 9.5.0
 * @todo Shouldn't this extend CommonDBChild?
 * @todo The 'parent_id' field should be named 'impactcompounds_id'
 * @todo This should use standard GLPI right management. Currently blocking API access.
 */
class ImpactItem extends CommonDBTM
{
    /**
     * Find ImpactItem for a given CommonDBTM item
     *
     * @param CommonDBTM $item                The given item
     * @param bool       $create_if_missing   Should we create a new ImpactItem
     *                                        if none found ?
     * @return ImpactItem|bool ImpactItem object or false if not found and
     *                         creation is disabled
     */
    public static function findForItem(
        CommonDBTM $item,
        bool $create_if_missing = true
    ) {
        global $DB;

        $it = $DB->request([
            'SELECT' => [
                'glpi_impactitems.id',
            ],
            'FROM' => self::getTable(),
            'WHERE'  => [
                'glpi_impactitems.itemtype' => get_class($item),
                'glpi_impactitems.items_id' => $item->fields['id'],
            ],
        ]);

        $res = $it->current();
        $impact_item = new self();

        if ($res) {
            $id = $res['id'];
        } elseif ($create_if_missing) {
            $id = $impact_item->add([
                'itemtype' => get_class($item),
                'items_id' => $item->fields['id'],
            ]);
        } else {
            return false;
        }

        $impact_item->getFromDB($id);
        return $impact_item;
    }

    public function prepareInputForUpdate($input)
    {
        $max_depth = $input['max_depth'] ?? 0;

        if (intval($max_depth) <= 0) {
            // If value is not valid, reset to default
            $input['max_depth'] = Impact::DEFAULT_DEPTH;
        } elseif ($max_depth >= Impact::MAX_DEPTH && $max_depth != Impact::NO_DEPTH_LIMIT) {
            // Set to no limit if greater than max
            $input['max_depth'] = Impact::NO_DEPTH_LIMIT;
        }

        return $input;
    }
}
