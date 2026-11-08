<?php

use function Safe\preg_match;

global $DB;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

// Make a select box
if (isset($_POST["rubdoc"])) {
    $used = [];

    // Clean used array
    if (isset($_POST['used']) && is_array($_POST['used']) && (count($_POST['used']) > 0)) {
        $iterator = $DB->request([
            'SELECT' => ['id'],
            'FROM'   => 'glpi_documents',
            'WHERE'  => [
                'id'                    => $_POST['used'],
                'documentcategories_id' => (int) $_POST['rubdoc'],
            ],
        ]);

        foreach ($iterator as $data) {
            $used[$data['id']] = $data['id'];
        }
    }

    if (preg_match('/[^a-z_\-0-9]/i', $_POST['myname'])) {
        throw new RuntimeException('Invalid name provided!');
    }

    if (!isset($_POST['entity']) || $_POST['entity'] === '' || $_POST['entity'] === []) {
        $_POST['entity'] = $_SESSION['glpiactive_entity'];
    }

    // `entity` may be a single entity id (scalar) or a list of entity ids
    // (e.g. a recursive item passes the whole entities subtree).
    if (is_array($_POST['entity'])) {
        $entity = array_values(array_map('intval', $_POST['entity']));
    } else {
        $entity = (int) $_POST['entity'];
    }

    Dropdown::show(
        'Document',
        [
            'name'      => $_POST['myname'],
            'used'      => $used,
            'width'     => '50%',
            'entity'    => $entity,
            'rand'      => intval($_POST['rand']),
            'condition' => ['glpi_documents.documentcategories_id' => (int) $_POST["rubdoc"]],
            'value'     => (int) ($_POST['value'] ?? -1),
        ]
    );
}
