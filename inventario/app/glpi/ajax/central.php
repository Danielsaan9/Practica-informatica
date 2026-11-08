<?php

use Glpi\Exception\Http\BadRequestHttpException;

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (
    (!isset($_REQUEST['params']['_idor_token']) || empty($_REQUEST['params']['_idor_token'])) || !isset($_REQUEST['itemtype'])
    || !isset($_REQUEST['widget'])
) {
    throw new BadRequestHttpException();
}

$idor = $_REQUEST['params']['_idor_token'];
unset($_REQUEST['params']['_idor_token']);

if (
    !Session::validateIDOR([
        'itemtype'     => $_REQUEST['itemtype'],
        '_idor_token'  => $idor,
    ] + $_REQUEST['params'])
) {
    throw new BadRequestHttpException();
}

/** @var class-string<CommonGLPI> $itemtype */
$itemtype = $_REQUEST['itemtype'];
$params = $_REQUEST['params'];

switch ($_REQUEST['widget']) {
    case 'central_count':
        if (method_exists($itemtype, 'showCentralCount')) {
            $itemtype::showCentralCount($params['foruser'] ?? false);
        }
        break;
    case 'central_list':
        if (method_exists($itemtype, 'showCentralList')) {
            if (is_subclass_of($itemtype, CommonITILObject::class) || is_subclass_of($itemtype, CommonITILTask::class)) {
                $showgrouptickets = isset($params['showgrouptickets']) ? ($params['showgrouptickets'] !== 'false') : false;
                $itemtype::showCentralList($params['start'], $params['status'] ?? 'process', $showgrouptickets);
            }
        } elseif ($itemtype === RSSFeed::class) {
            $personal = $params['personal'] !== 'false';
            $itemtype::showListForCentral($personal);
        } elseif ($itemtype === Planning::class) {
            $itemtype::showCentral($params['who']);
        } elseif ($itemtype === Reminder::class) {
            $personal = ($params['personal'] ?? true) !== 'false';
            $itemtype::showListForCentral($personal);
        } elseif ($itemtype === Project::class) {
            $itemtype::showListForCentral($params['itemtype']);
        } elseif ($itemtype === ProjectTask::class) {
            $itemtype::showListForCentral($params['itemtype']);
        }
        break;
    default:
        echo __s('Invalid widget');
}
