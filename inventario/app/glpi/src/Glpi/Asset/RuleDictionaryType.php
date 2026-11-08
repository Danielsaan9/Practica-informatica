<?php

namespace Glpi\Asset;

use RuleDictionnaryDropdown;
use RuntimeException;
use Toolbox;

abstract class RuleDictionaryType extends RuleDictionnaryDropdown
{
    /**
     * Asset definition system name.
     *
     * Must be defined here to make PHPStan happy (see https://github.com/phpstan/phpstan/issues/8808).
     * Must be defined by child class too to ensure that assigning a value to this property will affect
     * each child classe independently.
     */
    protected static string $definition_system_name;

    /**
     * Get the asset definition related to concrete class.
     *
     * @return AssetDefinition
     */
    public static function getDefinition(): AssetDefinition
    {
        $definition = AssetDefinitionManager::getInstance()->getDefinition(static::$definition_system_name);
        if (!($definition instanceof AssetDefinition)) {
            throw new RuntimeException('Asset definition is expected to be defined in concrete class.');
        }

        return $definition;
    }

    public function getCriterias()
    {
        return [
            'name' => [
                'field' => 'name',
                'name'  => _n('Type', 'Types', 1),
                'table' => static::getDefinition()->getAssetTypeClassName()::getTable(),
            ],
        ];
    }

    public function getActions()
    {
        return [
            'name' => [
                'name'          => _n('Type', 'Types', 1),
                'force_actions' => [
                    'append_regex_result',
                    'assign',
                    'regex_result',
                ],
            ],
        ];
    }

    public static function getSearchURL($full = true)
    {
        return Toolbox::getItemTypeSearchURL(self::class, $full)
            . '?class=' . static::getDefinition()->fields['system_name'];
    }

    public static function getFormURL($full = true)
    {
        return Toolbox::getItemTypeFormURL(self::class, $full)
            . '?class=' . static::getDefinition()->fields['system_name'];
    }
}
