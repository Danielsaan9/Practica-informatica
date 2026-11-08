<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
//move criteria 'os_name' to 'name' for 'RuleDictionnaryOperatingSystem'
//move criteria 'os_version' to 'name' for 'RuleDictionnaryOperatingSystemVersion'
//move criteria 'os_edition' to 'name' for 'RuleDictionnaryOperatingSystemEdition'
//move criteria 'arch_name' to 'name' for 'RuleDictionnaryOperatingSystemArchitecture'
//move criteria 'servicepack_name' to 'name' for 'RuleDictionnaryOperatingSystemServicePack'
$sub_types = [
    'servicepack_name' => 'RuleDictionnaryOperatingSystemServicePack',
    'os_edition' => 'RuleDictionnaryOperatingSystemEdition',
    'arch_name' => 'RuleDictionnaryOperatingSystemArchitecture',
    'os_version' => 'RuleDictionnaryOperatingSystemVersion',
    'os_name' => 'RuleDictionnaryOperatingSystem',
];

//Get all glpi_rulecrtiteria with 'name' criteria for OS Dictionnary
foreach ($sub_types as $criteria => $sub_type) {
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_rulecriterias',
            ['criteria' => 'name'],
            ['criteria' => $criteria],
            [
                'INNER JOIN' => [
                    'glpi_rules' => [
                        'FKEY' => [
                            'glpi_rulecriterias' => 'rules_id',
                            'glpi_rules' => 'id',
                            [
                                'AND' => ['glpi_rules.sub_type' => $sub_type],
                            ],
                        ],
                    ],
                ],
            ]
        )
    );
}
