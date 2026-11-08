<?php

/**
 * @since 9.5.0
 * @todo This should use standard GLPI right management. Currently blocking API access.
 */
class ImpactContext extends CommonDBTM
{
    /**
     * Get ImpactContext for the given ImpactItem
     *
     * @param ImpactItem $item
     * @return ImpactContext|false
     */
    public static function findForImpactItem(ImpactItem $item)
    {
        $impactContext = new self();
        $exist = $impactContext->getFromDB($item->fields['impactcontexts_id']);

        return $exist ? $impactContext : false;
    }
}
