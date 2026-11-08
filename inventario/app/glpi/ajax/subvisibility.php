<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!empty($_POST['type']) && isset($_POST['items_id']) && ($_POST['items_id'] > 0)) {
    $prefix = '';
    $suffix = '';
    if (!empty($_POST['prefix'])) {
        $prefix = $_POST['prefix'] . '[';
        $suffix = ']';
    }

    switch ($_POST['type']) {
        case 'Group':
        case 'Profile':
            $params = ['value' => $_SESSION['glpiactive_entity'],
                'name'  => $prefix . 'entities_id' . $suffix,
            ];
            if (Session::canViewAllEntities()) {
                $params['toadd'] = [-1 => __('No restriction')];
            }
            echo "<table class='tab_format'><tr><td>";
            echo htmlescape(Entity::getTypeName(1));
            echo "</td><td>";
            Entity::dropdown($params);
            echo "</td><td>";
            echo __s('Child entities');
            echo "</td><td>";
            Dropdown::showYesNo($prefix . 'is_recursive' . $suffix);
            echo "</td></tr></table>";
            break;
    }
}
