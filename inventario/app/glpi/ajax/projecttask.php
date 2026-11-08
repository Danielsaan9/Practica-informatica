<?php

/**
 * @since 9.2
 */

use function Safe\json_encode;

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

if (isset($_POST['projecttasktemplates_id']) && ($_POST['projecttasktemplates_id'] > 0)) {
    $template = new ProjectTaskTemplate();
    $template->getFromDB($_POST['projecttasktemplates_id']);

    $template->fields['description'] = DropdownTranslation::getTranslatedValue(
        $template->getID(),
        $template::class,
        'description',
        $_SESSION['glpilanguage'],
        $template->fields['description']
    );

    echo json_encode($template->fields);
}
