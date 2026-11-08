<?php

/**
 * @var Migration $migration
 */
$dc_model_dropdowns = [
    'EnclosureModel', 'PeripheralModel', 'PDUModel', 'NetworkEquipmentModel', 'MonitorModel', 'ComputerModel',
    'PassiveDCEquipmentModel',
];

foreach ($dc_model_dropdowns as $model_dropdown) {
    $migration->changeSearchOption($model_dropdown, 130, 3);
}
