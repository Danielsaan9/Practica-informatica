<?php

/**
 * @since 0.85
 */

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(DropdownTranslation::$rightname, UPDATE);
if (isset($_POST['itemtype']) && isset($_POST['language'])) {
    $item = getItemForItemtype($_POST['itemtype']);
    $item->getFromDB($_POST['items_id']);
    DropdownTranslation::dropdownFields($item, $_POST['language']);
}
