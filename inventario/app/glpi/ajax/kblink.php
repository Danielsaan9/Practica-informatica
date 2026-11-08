<?php

/**
 * Retrieve the knowledgebase links associated to a category
 * @since   9.2
 */

global $DB;

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (isset($_POST["table"], $_POST["value"])) {
    // Security
    if (!$DB->tableExists($_POST['table'])) {
        return;
    }

    if (isset($_POST['withlink'])) {
        $item = getItemForTable($_POST["table"]);
        if (
            !Session::validateIDOR([
                'itemtype'    => $item::class,
                '_idor_token' => $_POST['_idor_token'] ?? "",
            ])
        ) {
            return;
        }
        $item->getFromDB((int) $_POST["value"]);
        if (method_exists($item, 'getLinks')) {
            echo '&nbsp;' . $item->getLinks();
        }
    }
}
