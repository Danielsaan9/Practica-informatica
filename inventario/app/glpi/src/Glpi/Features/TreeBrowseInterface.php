<?php

namespace Glpi\Features;

use CommonDBTM;

interface TreeBrowseInterface
{
    /**
     * @param string $itemtype
     * @param array $params
     * @param bool $update
     *
     * @return void
     */
    public static function showBrowseView(string $itemtype, array $params, $update = false);

    /**
     * Get list of document categories in Wunderbaum (fancytree) format.
     *
     * @param class-string<CommonDBTM> $itemtype
     * @param array $params
     *
     * @return array
     */
    public static function getTreeCategoryList(string $itemtype, array $params): array;

    /**
     * Return category item for given itemtype.
     *
     * @param class-string<CommonDBTM> $itemtype
     */
    public static function getCategoryItem(string $itemtype): ?CommonDBTM;
}
