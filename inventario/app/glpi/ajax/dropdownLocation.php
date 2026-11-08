<?php

Html::header_nocache();

if (
    !isset($_REQUEST['itemtype'])
    && !is_subclass_of($_REQUEST['itemtype'], 'CommonDBTM')
) {
    throw new RuntimeException('Required argument missing or incorrect!');
}

$item = getItemForItemtype($_REQUEST['itemtype']);
$item->getFromDB((int) $_REQUEST['items_id']);

$locations_id = $item->fields['locations_id'] ?? 0;

$entities_id = $item->fields['entities_id'] ?? $_SESSION['glpiactive_entity'];

$is_recursive = $_SESSION['glpiactive_entity_recursive'];
if (isset($_REQUEST['is_recursive'])) {
    $is_recursive = (bool) $_REQUEST['is_recursive'];
}

Location::dropdown([
    'value' => $locations_id,
    'entity' => $entities_id,
    'entity_sons' => $is_recursive,
]);
