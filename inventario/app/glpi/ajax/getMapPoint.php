<?php

use function Safe\json_encode;

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

$result = [];
if (!isset($_POST['itemtype']) || !isset($_POST['items_id']) || (int) $_POST['items_id'] < 1) {
    $result = [
        'success'   => false,
        'message'   => __s('Required argument missing!'),
    ];
} else {
    $itemtype = $_POST['itemtype'];
    $items_id = $_POST['items_id'];

    if ($itemtype != Location::class) {
        $item = getItemForItemtype($itemtype);
        $found = $item->getFromDB($items_id);
        if ($found && isset($item->fields['locations_id']) && (int) $item->fields['locations_id'] > 0) {
            $itemtype = Location::class;
            $items_id = $item->fields['locations_id'];
        } else {
            $result = [
                'success'   => false,
                'message'   => __s('Element seems not geolocalized or cannot be found'),
            ];
        }
    }

    if (!count($result)) {
        /** @var CommonDBTM $item */
        $item = getItemForItemtype($itemtype);
        if (!$item->can($items_id, READ)) {
            $result = [
                'success'   => false,
                'message'   => __s('Not allowed'),
            ];
        } else {
            $item->getFromDB($items_id);
            if (!empty($item->fields['latitude']) && !empty($item->fields['longitude'])) {
                $result = [
                    'lat'    => (float) $item->fields['latitude'],
                    'lng'    => (float) $item->fields['longitude'],
                ];
            } else {
                $result = [
                    'success'   => false,
                    'message'   => "<h3>" . __("Location seems not geolocalized!") . "</h3>"
                               . "<a href='" . htmlescape($item->getLinkURL()) . "'>" . __s("Consider filling latitude and longitude on this location.") . "</a>",
                ];
            }
        }
    }
}

echo json_encode($result);
