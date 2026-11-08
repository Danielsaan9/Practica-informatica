<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Form\Form;

// Read parameters
$id = $_REQUEST['id'] ?? null;

if (($_REQUEST['id'] ?? 0) == 0) {
    Session::checkRight(Form::$rightname, CREATE);

    // Add as draft and redirect to the creation page
    // This allow to seamlessly skip the creation step and get straight to the
    // edit page which will contains more fields
    $form = new Form();
    $id = $form->add([
        'name'         => __("Untitled form"),
        'entities_id'  => $_SESSION['glpiactive_entity'],
        'is_recursive' => true,
        'is_draft'     => true,
    ]);
    Session::setActiveTab(Form::class, Form::class . '$main');
    Html::redirect($form->getLinkURL());
} elseif (isset($_POST['update'])) {
    $id = $_POST['id'] ?? 0;

    $form = new Form();
    $form->getFromDB($id);
    $form->check($id, UPDATE);
    $form->update($_POST);

    Html::redirect($form->getLinkURL());
} else {
    // Show requested form
    Session::checkRight(Form::$rightname, READ);
    Form::displayFullPageForItem($id, ['admin', Form::class], []);
}
