<?php

/**
 * @var Migration $migration
 */

$tables = [
    'glpi_autoupdatesystems', 'glpi_savedsearches', 'glpi_changevalidations', 'glpi_dashboards_dashboards',
    'glpi_dashboards_filters', 'glpi_dashboards_items', 'glpi_devicecasemodels', 'glpi_devicecontrolmodels',
    'glpi_devicedrivemodels', 'glpi_devicegenericmodels', 'glpi_devicegenerictypes', 'glpi_devicegraphiccardmodels',
    'glpi_deviceharddrivemodels', 'glpi_deviceharddrivetypes', 'glpi_devicecameramodels', 'glpi_devicememorymodels',
    'glpi_devicemotherboardmodels', 'glpi_devicenetworkcardmodels', 'glpi_devicepcimodels', 'glpi_devicepowersupplymodels',
    'glpi_deviceprocessormodels', 'glpi_devicesensormodels', 'glpi_devicesensortypes', 'glpi_devicesoundcardmodels',
    'glpi_networkaliases', 'glpi_networkinterfaces', 'glpi_reservationitems', 'glpi_reservations',
    'glpi_softwarecategories', 'glpi_ticketrecurrents', 'glpi_recurrentchanges', 'glpi_tickettemplates',
    'glpi_changetemplates', 'glpi_problemtemplates', 'glpi_devicebatterymodels', 'glpi_devicefirmwaremodels',
    'glpi_domaintypes', 'glpi_domainrelations', 'glpi_domainrecordtypes', 'glpi_appliancetypes', 'glpi_applianceenvironments',
    'glpi_agenttypes', 'glpi_pendingreasons', 'glpi_pendingreasons_items', 'glpi_snmpcredentials', 'glpi_forms_categories',
    'glpi_oauthclients', 'glpi_defaultfilters',
];

foreach ($tables as $table) {
    $migration->addField($table, 'date_creation', 'datetime');
    $migration->addKey($table, 'date_creation');
    $migration->addField($table, 'date_mod', 'datetime');
    $migration->addKey($table, 'date_mod');
}
