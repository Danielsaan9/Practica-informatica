<?php

namespace Glpi\System\Requirement;

/**
 * @since 9.5.0
 */
class Extension extends AbstractRequirement
{
    /**
     * Required extension name.
     *
     */
    protected string $name;

    /**
     * @param string      $name         Required extension name.
     * @param bool        $optional     Indicate if extension is optional.
     * @param string|null $description  Describe usage of the extension.
     */
    public function __construct(string $name, bool $optional = false, ?string $description = null)
    {
        parent::__construct(
            sprintf(__('%s extension'), $name),
            $description,
            $optional
        );

        $this->name = $name;
    }

    protected function check()
    {
        $this->validated = extension_loaded($this->name);
        $this->buildValidationMessage();
    }

    /**
     * Defines the validation message based on self properties.
     *
     * @return void
     */
    protected function buildValidationMessage()
    {
        if ($this->validated) {
            $this->validation_messages[] = sprintf(__('%s extension is installed'), $this->name);
        } elseif ($this->optional) {
            $this->validation_messages[] = sprintf(__('%s extension is not present'), $this->name);
        } else {
            $this->validation_messages[] = sprintf(__('%s extension is missing'), $this->name);
        }
    }
}
