<?php

/**
 * @var Migration $migration
 */
//set default configuration for import_unmanaged
$migration->addConfig(['import_unmanaged' => 1], 'inventory');

//add last_inventory_update field
$migration->addField('glpi_unmanageds', 'last_inventory_update', 'timestamp');
$migration->addField("glpi_unmanageds", "groups_id_tech", "fkey", ["after" => "states_id"]);
$migration->addKey('glpi_unmanageds', 'groups_id_tech');

// add default rules for unmanaged device if RuleImportAsset already added
if (countElementsInTable(Rule::getTable(), ['sub_type' => 'RuleImportAsset']) > 0) {
    $migration->createRule(
        [
            'name'      => 'Unmanaged update (by name)',
            'uuid'      => 'glpi_rule_import_asset_unmanaged_update_name',
            'match'     => 'AND',
            'sub_type'  => RuleImportAsset::class,
            'is_active' => 1,
        ],
        [
            [
                'criteria'  => 'itemtype',
                'condition' => Rule::PATTERN_IS,
                'pattern'   => 'Unmanaged',
            ],
            [
                'criteria'  => 'name',
                'condition' => Rule::PATTERN_EXISTS,
                'pattern'   => 1,
            ],
            [
                'criteria'  => 'name',
                'condition' => Rule::PATTERN_FIND,
                'pattern'   => 1,
            ],
        ],
        [
            [
                'field'         => '_inventory',
                'action_type'  => "assign",
                'value'         => RuleImportAsset::RULE_ACTION_LINK_OR_IMPORT,
            ],
        ]
    );

    $migration->createRule(
        [
            'name'      => 'Unmanaged import (by name)',
            'uuid'      => 'glpi_rule_import_asset_unmanaged_import_name',
            'match'     => 'AND',
            'sub_type'  => RuleImportAsset::class,
            'is_active' => 1,
        ],
        [
            [
                'criteria'  => 'itemtype',
                'condition' => Rule::PATTERN_IS,
                'pattern'   => 'Unmanaged',
            ],
            [
                'criteria'  => 'name',
                'condition' => Rule::PATTERN_EXISTS,
                'pattern'   => 1,
            ],
        ],
        [
            [
                'field'         => '_inventory',
                'action_type'  => "assign",
                'value'         => RuleImportAsset::RULE_ACTION_LINK_OR_IMPORT,
            ],
        ]
    );

    $migration->createRule(
        [
            'name'      => 'Unmanaged import denied',
            'uuid'      => 'glpi_rule_import_asset_unmanaged_import_denied',
            'match'     => 'AND',
            'sub_type'  => RuleImportAsset::class,
            'is_active' => 1,
        ],
        [
            [
                'criteria'  => 'itemtype',
                'condition' => Rule::PATTERN_IS,
                'pattern'   => 'Unmanaged',
            ],
        ],
        [
            [
                'field'         => '_inventory',
                'action_type'  => "assign",
                'value'         => RuleImportAsset::RULE_ACTION_DENIED,
            ],
        ]
    );
}
