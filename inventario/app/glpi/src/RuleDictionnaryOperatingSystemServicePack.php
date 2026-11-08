<?php

class RuleDictionnaryOperatingSystemServicePack extends RuleDictionnaryDropdown
{
    public function getCriterias()
    {
        static $criterias = [];

        if (count($criterias)) {
            return $criterias;
        }

        $criterias['name']['field'] = 'name';
        $criterias['name']['name']  = OperatingSystemServicePack::getTypeName(1);
        $criterias['name']['table'] = 'glpi_operatingsystemservicepacks';

        $criterias['os_name']['field'] = 'name';
        $criterias['os_name']['name']  = OperatingSystem::getTypeName(1);
        $criterias['os_name']['table'] = 'glpi_operatingsystems';

        $criterias['os_version_name']['field'] = 'name';
        $criterias['os_version_name']['name']  = OperatingSystemVersion::getTypeName(1);
        $criterias['os_version_name']['table'] = 'glpi_operatingsystemversions';

        $criterias['arch_name']['field'] = 'name';
        $criterias['arch_name']['name']  = OperatingSystemArchitecture::getTypeName(1);
        $criterias['arch_name']['table'] = 'glpi_operatingsystemarchitectures';

        $criterias['os_edition']['field'] = 'name';
        $criterias['os_edition']['name']  = OperatingSystemEdition::getTypeName(1);
        $criterias['os_edition']['table'] = 'glpi_operatingsystemeditions';


        return $criterias;
    }

    public function getActions()
    {
        $actions                          = [];
        $actions['name']['name']          = OperatingSystemServicePack::getTypeName(1);
        $actions['name']['force_actions'] = ['append_regex_result', 'assign', 'regex_result'];

        return $actions;
    }
}
