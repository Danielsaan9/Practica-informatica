<?php

Html::header_nocache();

if (!isset($_REQUEST['id'])) {
    throw new RuntimeException('Required argument missing!');
}

$id = $_REQUEST['id'];
$current = $_REQUEST['current'] ?? null;
$rand = $_REQUEST['rand'] ?? mt_rand();

$room = new DCRoom();
if ($room->getFromDB($id)) {
    $used = $room->getFilled($current);
    $positions = $room->getAllPositions();

    Dropdown::showFromArray(
        'position',
        $positions,
        [
            'value'                 => $current,
            'rand'                  => $rand,
            'display_emptychoice'   => true,
            'used'                  => $used,
        ]
    );
} else {
    echo "<div class='col-form-label'>" . __s('No room found or selected') . "</div>";
}
