<?php

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Features\TreeBrowseInterface;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

switch ($_REQUEST['action']) {
    case "getItemslist":
        $params = [
            'start'              => $_REQUEST['start'],
            'is_deleted'         => $_REQUEST['is_deleted'],
            'browse'             => 1,
            'as_map'             => 0,
            'showmassiveactions' => true,
            'criteria'           => $_REQUEST['criteria'],
            'sort'               => $_REQUEST['sort'] ?? [],
            'order'              => $_REQUEST['order'] ?? [],
            'unpublished'        => $_REQUEST['unpublished'],
        ];

        $itemtype = $_REQUEST['itemtype'];
        if (
            !is_string($itemtype)
            || !is_a($itemtype, CommonDBTM::class, true)
            || !is_a($itemtype, TreeBrowseInterface::class, true)
            || (new ReflectionClass($itemtype))->isAbstract()
        ) {
            throw new BadRequestHttpException();
        }
        if ($itemtype::canView() === false) {
            throw new AccessDeniedHttpException();
        }
        $category_item = $itemtype::getCategoryItem($itemtype);
        if ($category_item === null) {
            throw new BadRequestHttpException();
        }
        $category_table = $category_item::getTable();
        $item = new $itemtype();
        $so = $item->rawSearchOptions();

        $field = 0;
        foreach ($so as $value) {
            if (isset($value['field'])) {
                if (($value['field'] == 'name' || $value['field'] == 'completename') && $value['table'] == $category_table) {
                    $field = $value['id'];
                }
            }
        }

        $_SESSION['treebrowse'][$itemtype] = [
            'link'   => "AND",
            'field'  => $field,
            'searchtype'   => "equals",
            'virtual'      => true,
            'value'  => ($_REQUEST['cat_id'] > 0) ? $_REQUEST['cat_id'] : 0,
        ];
        $params['criteria'][] = $_SESSION['treebrowse'][$itemtype];
        Search::showList($itemtype, $params);
        return;
}

throw new BadRequestHttpException();
