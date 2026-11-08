<?php

namespace Glpi\System\Requirement;

/**
 * @since 9.5.0
 */
class ExtensionFunction extends Extension
{
    /**
     * Required function name.
     *
     */
    private string $function_name;

    /**
     * @param string $name           Extension name.
     * @param string $function_name  Required function name.
     * @param bool $optional         Indicated if extension is optional.
     */
    public function __construct(string $name, string $function_name, bool $optional = false)
    {
        parent::__construct($name, $optional);
        $this->function_name = $function_name;
    }

    protected function check()
    {
        $this->validated = function_exists($this->function_name);
        $this->buildValidationMessage();
    }
}
