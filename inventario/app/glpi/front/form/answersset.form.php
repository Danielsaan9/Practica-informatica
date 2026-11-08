<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Form\AnswersSet;
use Glpi\Form\Form;

// Read parameters
$id = $_REQUEST['id'] ?? null;

if (isset($_POST["purge"])) {
    $answers_set = new AnswersSet();
    // TODO: Add a specific right to ensure the user can delete an answer
    $answers_set->check($id, DELETE);
    $answers_set->delete($_POST, true);
    $answers_set->redirectToList();
} else {
    Session::checkRight(Form::$rightname, READ);
    AnswersSet::displayFullPageForItem($id, ['admin', Form::class], []);
}
