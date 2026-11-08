<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Form\AccessControl\FormAccessControl;

use function Safe\json_encode;

/**
 * Ajax endpoint to update an access control item.
 *
 * This endpoint is called once per possible access control stategies when
 * submitting the access control config page for a form.
 */

try {
    $access_control = new FormAccessControl();
    if (isset($_POST["update"])) {
        // Update access control policies
        foreach ($_POST['_access_control'] as $id => $input) {
            $input['id'] = $id;

            $access_control->check($id, UPDATE, $input);
            $access_control->getFromDB($id);
            $input['_config'] = $access_control->createConfigFromUserInput($input);

            if (!$access_control->update($input, true)) {
                throw new RuntimeException(
                    "Failed to update access control item"
                );
            }
        }
    } else {
        // Unknown request
        throw new InvalidArgumentException("Unknown action");
    }
} catch (Throwable $e) {
    // Log error
    global $PHPLOGGER;
    $PHPLOGGER->error(
        $e->getMessage() . ": " . json_encode($_POST),
        ['exception' => $e]
    );

    Session::addMessageAfterRedirect(
        __s('An unexpected error occurred'),
        false,
        ERROR
    );
}

// Redirect to previous page
Html::back();
