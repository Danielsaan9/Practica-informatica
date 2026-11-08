<?php

namespace Glpi\Toolbox;

trait SingletonTrait
{
    /**
     * @var array<class-string, static>
     */
    private static array $_instances = [];

    /**
     * Private method : no `new` instantiation is possible
     */
    private function __construct() {}

    public static function getInstance(): static
    {
        $class = static::class;
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }

        return self::$_instances[$class];
    }
}
