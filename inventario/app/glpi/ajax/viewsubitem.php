<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!isset($_POST['type'])) {
    return;
}
if (!isset($_POST['parenttype'])) {
    return;
}

if (
    ($item = getItemForItemtype($_POST['type']))
    && ($parent = getItemForItemtype($_POST['parenttype']))
) {
    if (
        isset($_POST[$parent::getForeignKeyField()], $_POST["id"])
        && $parent->getFromDB($_POST[$parent::getForeignKeyField()])
    ) {
        $item->showForm($_POST["id"], ['parent' => $parent]);
    } else {
        echo __s('Access denied');
    }
}
