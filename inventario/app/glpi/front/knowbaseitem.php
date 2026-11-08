<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;

if (!Session::haveRightsOr(KnowbaseItem::$rightname, [READ, KnowbaseItem::READFAQ])) {
    throw new AccessDeniedHttpException();
}

// Entering the knowledge base without any parameter means opening its root
// article.
if ($_GET === [] && KnowbaseItem::hasRoot()) {
    $root_id = KnowbaseItem::getRootId();
    $root    = new KnowbaseItem();
    if ($root->getFromDB($root_id) && $root->can($root_id, READ)) {
        Html::redirect(KnowbaseItem::getFormURLWithID($root_id));
    }
}

if (isset($_GET["id"])) {
    Html::redirect(KnowbaseItem::getFormURLWithID($_GET["id"]));
}

Html::header(KnowbaseItem::getTypeName(1), '', "tools", "knowbaseitem");

Search::show(KnowbaseItem::class);

Html::footer();
