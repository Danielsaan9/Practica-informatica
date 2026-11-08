<?php

use function Safe\json_encode;

global $CFG_GLPI;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

if (isset($_POST['entity_restrict'])) {
    $_POST['entity_restrict'] = Session::getMatchingActiveEntities($_POST['entity_restrict']);
}

// Make a select box
if ($_POST["idtable"] && class_exists($_POST["idtable"])) {
    // Link to user for search only > normal users
    $link = "getDropdownValue.php";

    if ($_POST["idtable"] == 'User') {
        $link = "getDropdownUsers.php";
    }

    $rand = (int) ($_POST['rand'] ?? mt_rand());

    $field_id = Html::cleanId("dropdown_" . $_POST["name"] . $rand);

    $displaywith = Dropdown::getDisplayWith($_POST["idtable"]);
    $p = [
        'value'               => 0,
        'valuename'           => Dropdown::EMPTY_VALUE,
        'itemtype'            => $_POST["idtable"],
        'display_emptychoice' => filter_var($_POST['display_emptychoice'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true,
        'displaywith'         => $displaywith,
    ];
    $idor_params = [
        'displaywith' => $displaywith,
    ];
    if (isset($_POST['value'])) {
        $p['value'] = $_POST['value'];
    }
    if (isset($_POST['valuename'])) {
        $p['valuename'] = $_POST['valuename'];
    }
    if (isset($_POST['entity_restrict'])) {
        $entity_restrict = is_array($_POST['entity_restrict'])
            ? json_encode(array_values($_POST['entity_restrict']))
            : $_POST['entity_restrict'];
        $p['entity_restrict']           = $entity_restrict;
        $idor_params['entity_restrict'] = $entity_restrict;
    }
    if (isset($_POST['condition'])) {
        $p['condition']           = $_POST['condition'];
        $idor_params['condition'] = $_POST['condition'];
    }
    if (isset($_POST['used']) && !is_array($_POST['used'])) {
        $_POST['used'] = Toolbox::jsonDecode($_POST['used'], true);
    }
    if (isset($_POST['used'][$_POST['idtable']])) {
        $p['used'] = $_POST['used'][$_POST['idtable']];
    }
    if (isset($_POST['width'])) {
        $p['width'] = $_POST['width'];
    }
    if (isset($_POST['multiple'])) {
        $p['multiple'] = (bool) $_POST['multiple'];
    }
    if (isset($_POST['container_css_class'])) {
        $p['container_css_class'] = $_POST['container_css_class'];
    }
    if (isset($_POST['specific_tags_items_id_dropdown'])) {
        $p['specific_tags'] = $_POST['specific_tags_items_id_dropdown'];
    }
    if (isset($_POST['aria_label'])) {
        $p['aria_label'] = $_POST['aria_label'];
    }
    if (isset($_POST['toadd']) && is_array($_POST['toadd'])) {
        $p['toadd'] = $_POST['toadd'];
    }
    $p['_idor_token'] = Session::getNewIDORToken($_POST["idtable"], $idor_params);

    echo  Html::jsAjaxDropdown(
        $_POST["name"],
        $field_id,
        $CFG_GLPI['root_doc'] . "/ajax/" . $link,
        $p
    );

    if (!empty($_POST['showItemSpecificity'])) {
        $params = ['items_id' => '__VALUE__',
            'itemtype' => $_POST["idtable"],
        ];
        if (isset($entity_restrict)) {
            $params['entity_restrict'] = $entity_restrict;
        }

        $id = 'showItemSpecificity_' . $_POST['name'] . $rand;

        Ajax::updateItemOnSelectEvent(
            $field_id,
            $id,
            $_POST['showItemSpecificity'],
            $params
        );

        echo "<br><div id='" . htmlescape($id) . "'>&nbsp;</div>";
    }
}
