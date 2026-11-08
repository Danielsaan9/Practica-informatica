<?php

use Glpi\Exception\Http\AccessDeniedHttpException;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (isset($_POST["my_items"]) && !empty($_POST["my_items"])) {
    $last_underscore = strrpos($_POST["my_items"], "_");
    if ($last_underscore === false) {
        throw new AccessDeniedHttpException();
    }
    $_POST["itemtype"] = substr($_POST["my_items"], 0, $last_underscore);
    $_POST["items_id"] = substr($_POST["my_items"], $last_underscore + 1);
}

if (
    isset($_POST['itemtype'])
    && isset($_POST['items_id']) && ($_POST['items_id'] > 0)
) {
    // Security
    if (!($item = getItemForItemtype($_POST['itemtype'])) || !$item->can($_POST['items_id'], READ)) {
        throw new AccessDeniedHttpException();
    }

    $days   = 3;

    $ticket = new Ticket();
    $data   = $ticket->getActiveOrSolvedLastDaysForItem(
        $_POST['itemtype'],
        $_POST['items_id'],
        $days
    );

    $nb = count($data);
    $badge_helper = sprintf(
        _sn(
            '%s ticket in progress or recently solved on this item.',
            '%s tickets in progress or recently solved on this item.',
            $nb
        ),
        $nb
    );
    echo "<span class='badge badge-secondary' title='$badge_helper'>$nb</span>";

    if ($nb) {
        $content = '';
        foreach ($data as $title) {
            $content .= htmlescape($title) . '<br>';
        }
        echo '&nbsp;';
        Html::showToolTip($content);
    }
}
