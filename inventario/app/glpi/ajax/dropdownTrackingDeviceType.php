<?php

use Glpi\Exception\Http\BadRequestHttpException;

global $CFG_GLPI;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

// Read parameters
$context  = $_POST['context'] ?? '';
$itemtype = $_POST["itemtype"] ?? '';

// Check for required params
if (empty($itemtype)) {
    throw new BadRequestHttpException("Bad request: itemtype cannot be empty");
}

// Check if itemtype is valid in the given context
if ($context == "impact") {
    $isValidItemtype = Impact::isEnabled($itemtype);
} else {
    $isValidItemtype = CommonITILObject::isPossibleToAssignType($itemtype);
}

// Make a select box
if ($isValidItemtype) {
    $table = getTableForItemType($itemtype);

    $rand = (int) ($_POST["rand"] ?? mt_rand());

    // Message for post-only
    if (!isset($_POST["admin"]) || ($_POST["admin"] == 0)) {
        echo "<span class='text-muted'>"
         . __s('Enter the first letters (user, item name, serial or asset number)')
         . "</span>";
    }
    $field_id = Html::cleanId("dropdown_" . $_POST['myname'] . $rand);
    $p = [
        'itemtype'            => $itemtype,
        'entity_restrict'     => Session::getMatchingActiveEntities($_POST['entity_restrict']),
        'table'               => $table,
        'multiple'            => (int) ($_POST["multiple"] ?? 0) !== 0,
        'myname'              => $_POST["myname"],
        'rand'                => $_POST["rand"],
        'width'               => $_POST["width"] ?? 'calc(100% - 25px)',
        '_idor_token'         => Session::getNewIDORToken($itemtype, [
            'entity_restrict' => Session::getMatchingActiveEntities($_POST['entity_restrict']),
        ]),
    ];

    if (!empty($_POST["used"])) {
        if (isset($_POST["used"][$itemtype])) {
            $p["used"] = $_POST["used"][$itemtype];
        }
    }

    // Add context if defined
    if (!empty($context)) {
        $p["context"] = $context;
    }

    echo Html::jsAjaxDropdown(
        $_POST['myname'],
        $field_id,
        $CFG_GLPI['root_doc'] . "/ajax/getDropdownFindNum.php",
        $p
    );

    // Auto update summary of active or just solved tickets
    if (($_POST['source_itemtype'] ?? null) === Ticket::class) {
        $myname = $_POST["myname"];
        echo "<span id='item_ticket_selection_information" . htmlescape("{$myname}_{$rand}") . "' class='ms-1 text-nowrap'></span>";
        Ajax::updateItemOnSelectEvent(
            $field_id,
            "item_ticket_selection_information{$myname}_{$rand}",
            $CFG_GLPI["root_doc"] . "/ajax/ticketiteminformation.php",
            [
                'items_id' => '__VALUE__',
                'itemtype' => $_POST['itemtype'],
            ]
        );
    }
}
