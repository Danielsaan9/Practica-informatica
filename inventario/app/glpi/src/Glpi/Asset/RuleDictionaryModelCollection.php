<?php

namespace Glpi\Asset;

use RuleDictionnaryDropdownCollection;
use RuntimeException;

abstract class RuleDictionaryModelCollection extends RuleDictionnaryDropdownCollection
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

    public function __construct()
    {
        $this->item_table  = static::getDefinition()->getAssetModelClassName()::getTable();
        $this->menu_option = sprintf('model.%s', static::getDefinition()->fields['system_name']);
    }

    public function getTitle()
    {
        return sprintf(__('Dictionary of %s'), static::getDefinition()->getAssetModelClassName()::getTypeName());
    }
}
