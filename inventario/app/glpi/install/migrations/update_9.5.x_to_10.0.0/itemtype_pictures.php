<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$dc_models = [ComputerModel::class, EnclosureModel::class, MonitorModel::class, NetworkEquipmentModel::class,
    PassiveDCEquipmentModel::class, PDUModel::class, PeripheralModel::class,
];

// Itemtypes with a 'front_picture' and 'rear picture' field
$front_rear_picture_itemtypes = [PhoneModel::class, PrinterModel::class];
// Itemtypes with a 'pictures' field that can contain one or more pictures
$misc_pictures_itemtypes = array_merge([PhoneModel::class, PrinterModel::class, Software::class, CartridgeItem::class, ConsumableItem::class,
    RackModel::class, SoftwareLicense::class, Datacenter::class, Contact::class, Supplier::class, Appliance::class,
], $dc_models);

foreach ($front_rear_picture_itemtypes as $itemtype) {
    $table = $itemtype::getTable();
    if (!$DB->fieldExists($table, 'picture_front')) {
        $migration->addField($itemtype::getTable(), 'picture_front', 'text');
    }
    if (!$DB->fieldExists($table, 'picture_rear')) {
        $migration->addField($itemtype::getTable(), 'picture_rear', 'text');
    }
}

/** @var CommonDBTM $itemtype */
foreach ($misc_pictures_itemtypes as $itemtype) {
    $table = $itemtype::getTable();
    if (!$DB->fieldExists($table, 'pictures')) {
        $after = ($DB->fieldExists($table, 'picture_rear')) ? 'picture_rear' : '';
        $migration->addField($table, 'pictures', 'text', [
            'after'  => $after,
        ]);
    }
}
