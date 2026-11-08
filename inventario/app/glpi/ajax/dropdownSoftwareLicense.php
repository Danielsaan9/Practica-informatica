<?php

global $DB;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(Software::$rightname, UPDATE);

if ($_POST['softwares_id'] > 0) {
    if (!isset($_POST['value'])) {
        $_POST['value'] = 0;
    }

    // Make a select box
    $iterator = $DB->request([
        'DISTINCT'  => true,
        'FROM'      => 'glpi_softwarelicenses',
        'WHERE'     => [
            'glpi_softwarelicenses.softwares_id'   => (int) $_POST['softwares_id'],
        ] + getEntitiesRestrictCriteria('glpi_softwarelicenses', 'entities_id', Session::getMatchingActiveEntities($_POST['entity_restrict']), true),
        'ORDERBY'   => 'name',
    ]);
    $number = count($iterator);

    $values = [];
    if ($number) {
        foreach ($iterator as $data) {
            $ID     = $data['id'];
            $output = $data['name'];

            if (empty($output) || $_SESSION['glpiis_ids_visible']) {
                $output = sprintf(__('%1$s (%2$s)'), $output, $ID);
            }

            $values[$ID] = $output;
        }
    }
    Dropdown::showFromArray($_POST['myname'], $values, ['display_emptychoice' => true]);
}
