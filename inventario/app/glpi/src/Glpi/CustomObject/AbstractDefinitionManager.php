<?php

namespace Glpi\CustomObject;

use function Safe\spl_autoload_register;

/**
 * Abstract class for custom object definition managers
 * @template ConcreteDefinition of AbstractDefinition
 */
abstract class AbstractDefinitionManager
{
    /**
     * Definitions cache.
     * @var array<int, ConcreteDefinition>
     */
    private array $definitions = [];

    /**
     * @phpstan-return static<ConcreteDefinition>
     */
    abstract public static function getInstance(): self;

    /**
     * @phpstan-return ConcreteDefinition
     */
    abstract public static function getDefinitionClassInstance(): AbstractDefinition;

    /**
     * Returns the regex pattern of reserved system names
     * @return string
     */
    abstract public function getReservedSystemNamesPattern(): string;

    /**
     * Register the class autoload function.
     * @return void
     */
    final public function registerAutoload(): void
    {
        spl_autoload_register(
            function ($classname) {
                // Use `static::getInstance()` to be sure that the autoloader will use the current instance in testing context
                // instead of the instance that was the current one during the GLPI boot.
                static::getInstance()->autoloadClass($classname);
            }
        );
    }

    /**
     * Autoload custom object class, if requested class is managed by this definition manager.
     *
     * @param string $classname
     * @return void
     */
    abstract public function autoloadClass(string $classname): void;

    /**
     * Boot the definitions.
     */
    final public function bootDefinitions(): void
    {
        $definition_object = static::getDefinitionClassInstance();

        $this->definitions = [];

        $definitions_data = getAllDataFromTable($definition_object::getTable());
        foreach ($definitions_data as $definition_data) {
            $definition = new $definition_object();
            $definition->getFromResultSet($definition_data);
            $this->registerDefinition($definition);
        }

        // Bootstrap definitions
        foreach ($this->getDefinitions(true) as $definition) {
            $this->bootstrapDefinition($definition);
        }
    }

    /**
     * Register a definition.
     * @phpstan-param ConcreteDefinition $definition
     */
    public function registerDefinition(AbstractDefinition $definition): void
    {
        $this->definitions[$definition->fields['system_name']] = $definition;
    }

    /**
     * Bootstrap the definition.
     * @param AbstractDefinition $definition
     * @phpstan-param ConcreteDefinition $definition
     * @return void
     */
    public function bootstrapDefinition(AbstractDefinition $definition) {}

    final public function getCustomObjectClassNames(bool $with_namespace = true): array
    {
        $classes = [];

        foreach ($this->getDefinitions(true) as $definition) {
            $classes[] = $definition->getCustomObjectClassName($with_namespace);
        }

        return $classes;
    }

    /**
     * Get the definition corresponding to given system name.
     *
     * @param string $system_name
     * @phpstan-return ConcreteDefinition|null
     */
    final public function getDefinition(string $system_name): ?AbstractDefinition
    {
        return $this->getDefinitions()[$system_name] ?? null;
    }

    /**
     * Clear the definitions cache.
     */
    final public function clearDefinitionsCache(): void
    {
        $this->definitions = [];
    }

    /**
     * Get all the definitions.
     *
     * @param bool $only_active
     * @return AbstractDefinition[]
     * @phpstan-return ConcreteDefinition[]
     */
    final public function getDefinitions(bool $only_active = false): array
    {
        if (!$only_active) {
            return $this->definitions;
        }

        return \array_filter(
            $this->definitions,
            fn(AbstractDefinition $definition) => $definition->isActive()
        );
    }
}
