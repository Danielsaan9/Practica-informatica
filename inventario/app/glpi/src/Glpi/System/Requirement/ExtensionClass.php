<?php

namespace Glpi\System\Requirement;

/**
 * @since 9.5.0
 */
class ExtensionClass extends Extension
{
    /**
     * Required class or interface name.
     *
     */
    private string $class_name;

    /**
     * @param string $name        Extension name.
     * @param string $class_name  Required class or interface name.
     * @param bool $optional      Indicated if extension is optional.
     */
    public function __construct(string $name, string $class_name, bool $optional = false)
    {
        parent::__construct($name, $optional);
        $this->class_name = $class_name;
    }

    protected function check()
    {
        $this->validated = class_exists($this->class_name) || interface_exists($this->class_name);
        $this->buildValidationMessage();
    }
}
