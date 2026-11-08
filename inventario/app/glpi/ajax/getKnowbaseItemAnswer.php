<?php

/**
 * @since 9.2
 */

header('Content-type: application/json');
Html::header_nocache();

if (isset($_POST['knowbaseitems_id'])) {
    $kbitem = new KnowbaseItem();
    $kbitem->getFromDB(intval($_POST['knowbaseitems_id']));
    $kbitem->showFull();
}
