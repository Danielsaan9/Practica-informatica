<?php

use Glpi\Exception\Http\NotFoundHttpException;

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(MailCollector::$rightname, UPDATE);

$mailcollector = new MailCollector();

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'getFoldersList') {
    // The collector must already exist in database.
    if (
        !array_key_exists('id', $_REQUEST)
        || !$mailcollector->getFromDB($_REQUEST['id'])
    ) {
        throw new NotFoundHttpException();
    }

    $mailcollector->displayFoldersList($_REQUEST['input_id'] ?? '');
}
