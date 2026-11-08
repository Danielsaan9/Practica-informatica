<?php

/**
 * @since 0.84
 */

use Glpi\DBAL\QuerySubQuery;

global $CFG_GLPI;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(NetworkPort::$rightname, UPDATE);

// Make a select box
if (class_exists($_POST["itemtype"])) {
    $rand     = mt_rand();

    $toupdate = [
        'value_fieldname' => 'item',
        'to_update'       => "results_item_$rand",
        'url'             => $CFG_GLPI["root_doc"] . "/ajax/dropdownConnectNetworkPort.php",
        'moreparams'      => [
            'networkports_id'    => $_POST['networkports_id'],
            'itemtype'           => $_POST['itemtype'],
            'myname'             => $_POST['myname'],
            'instantiation_type' => $_POST['instantiation_type'],
        ],
    ];
    $params   = [
        'rand'      => $rand,
        'name'      => "items",
        'entity'    => $_POST["entity_restrict"],
        'condition' => [
            'id' => new QuerySubQuery([
                'SELECT' => 'items_id',
                'FROM'   => 'glpi_networkports',
                'WHERE'  => [
                    'itemtype'           => $_POST['itemtype'],
                    'instantiation_type' => $_POST['instantiation_type'],
                ],
            ]),
        ],
        'toupdate'  => $toupdate,
    ];

    Dropdown::show($_POST['itemtype'], $params);

    echo "<span id='results_item_$rand'>";
    echo "</span>";
}
