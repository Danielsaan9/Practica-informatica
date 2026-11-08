<?php

use Glpi\Event;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$nn = new NetworkName();
if (isset($_POST["unaffect"])) {
    $nn->check($_POST['id'], UPDATE);
    $nn::unaffectAddressByID($_POST['id']);
    Event::log(
        $_POST["id"],
        "networkname",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
}
