<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;

/**
 * @since 0.85
 */

/**
 * Following variables have to be defined before inclusion of this file:
 * @var CommonITILValidation $validation
 */

if (!($validation instanceof CommonITILValidation)) {
    throw new BadRequestHttpException();
}
if (!$validation->canView()) {
    throw new AccessDeniedHttpException();
}

$itemtype = $validation::getItilObjectItemType();
$fk       = getForeignKeyFieldForItemType($itemtype);

if (isset($_POST["add"])) {
    if (isset($_POST['users_id_validate'])) {
        Toolbox::deprecated('Usage of "users_id_validate" parameter is deprecated in "front/commonitilvalidation.form.php". Use "items_id_target" instead.');
        $_POST['items_id_target'] = $_POST['users_id_validate'];
        $_POST['itemtype_target'] = User::class;
        unset($_POST['users_id_validate']);
    }

    $validation->check(-1, CREATE, $_POST);

    if (!isset($_POST['items_id_target'])) {
        Html::back();
    }
    if (!is_array($_POST['items_id_target'])) {
        $_POST['items_id_target'] = [$_POST['items_id_target']];
    }

    if (count($_POST['items_id_target']) > 0) {
        $targets = $_POST['items_id_target'];
        foreach ($targets as $target) {
            $_POST['items_id_target'] = $target;
            $validation->add($_POST);
            Event::log(
                $validation->fields[$fk],
                strtolower($itemtype),
                4,
                "tracking",
                //TRANS: %s is the user login
                sprintf(__('%s adds an approval'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
} elseif (isset($_POST["update"])) {
    $validation->check($_POST['id'], UPDATE);
    $validation->update($_POST);
    Event::log(
        $validation->fields[$fk],
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s updates an approval'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST["purge"])) {
    $validation->check($_POST['id'], PURGE);
    $validation->delete($_POST, true);

    Event::log(
        $validation->fields[$fk],
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges an approval'), $_SESSION["glpiname"])
    );
    Html::back();
} elseif (isset($_POST['approval_action'])) {
    if ($validation->getFromDB($_POST['id']) && $validation->canAnswer()) {
        $validation->update($_POST + [
            'status' => ($_POST['approval_action'] === 'approve') ? CommonITILValidation::ACCEPTED : CommonITILValidation::REFUSED,
        ]);
        Html::back();
    }
}

throw new BadRequestHttpException();
