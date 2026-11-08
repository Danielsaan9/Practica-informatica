<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\ItemLinkException;

/**
 * @since 9.4.0
 */

$item = new Itil_Project();

if (isset($_POST['add'])) {
    try {
        $item->check(-1, CREATE, $_POST);
    } catch (ItemLinkException $e) {
        Html::back();
    }

    if ($item->add($_POST)) {
        Event::log(
            $_POST['projects_id'],
            'project',
            4,
            'maintain',
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION['glpiname'])
        );
    }
    Html::back();
}

throw new BadRequestHttpException();
