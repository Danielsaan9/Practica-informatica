<?php

namespace Glpi\Dropdown;

use CommonTreeDropdown;
use Glpi\Application\View\TemplateRenderer;
use Glpi\CustomObject\AbstractDefinition;
use Glpi\CustomObject\CustomObjectTrait;
use RuntimeException;

abstract class Dropdown extends CommonTreeDropdown
{
    use CustomObjectTrait;

    public bool $can_be_translated = true;

    /**
     * Dropdown definition system name.
     *
     * Must be defined here to make PHPStan happy (see https://github.com/phpstan/phpstan/issues/8808).
     * Must be defined by child class too to ensure that assigning a value to this property will affect
     * each child classe independently.
     */
    protected static string $definition_system_name;

    public static function canView(): bool
    {
        if (!parent::canView()) {
            return false;
        }
        return (bool) static::getDefinition()->fields['is_active'];
    }

    /**
     * Get the dropdown definition related to concrete class.
     *
     * @return DropdownDefinition
     */
    public static function getDefinition(): DropdownDefinition
    {
        $definition = DropdownDefinitionManager::getInstance()->getDefinition(static::$definition_system_name);
        if (!($definition instanceof DropdownDefinition)) {
            throw new RuntimeException('Dropdown definition is expected to be defined in concrete class.');
        }

        return $definition;
    }

    /**
     * Get the definition class instance.
     * @return AbstractDefinition<Dropdown>
     */
    public static function getDefinitionClassInstance(): AbstractDefinition
    {
        return new DropdownDefinition();
    }

    public function prepareInputForAdd($input)
    {
        $input = parent::prepareInputForAdd($input);
        if ($input === false) {
            return false;
        }
        return $this->prepareDefinitionInput($input);
    }

    public function prepareInputForUpdate($input)
    {
        $input = parent::prepareInputForUpdate($input);
        if ($input === false) {
            return false;
        }

        return $this->prepareDefinitionInput($input);
    }

    public function showForm($ID, array $options = [])
    {
        $this->initForm($ID, $options);
        TemplateRenderer::getInstance()->display(
            'pages/setup/custom_dropdown.html.twig',
            [
                'item'   => $this,
                'params' => $options,
                'additional_fields' => $this->getAdditionalFields(),
            ]
        );
        return true;
    }

    public function rawSearchOptions()
    {
        $search_options = parent::rawSearchOptions();

        return $this->amendSearchOptions($search_options);
    }
}
