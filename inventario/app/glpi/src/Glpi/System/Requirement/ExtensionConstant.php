<?php

namespace Glpi\System\Requirement;

/**
 * @since 10.0.0
 */
class ExtensionConstant extends AbstractRequirement
{
    /**
     * Required constant name.
     *
     */
    private string $name;

    /**
     * @param string $title Constant title.
     * @param string $name Constant name.
     * @param bool $optional Indicated if extension is optional.
     * @param string $description Constant description.
     */
    public function __construct(string $title, string $name, bool $optional = false, string $description = '')
    {
        parent::__construct(
            $title,
            $description,
            $optional
        );

        $this->name = $name;
    }

    protected function check()
    {
        $this->validated = defined($this->name);
        if ($this->validated) {
            $this->validation_messages = [
                sprintf(__('The constant %s is present.'), $this->name),
            ];
        } elseif ($this->optional) {
            $this->validation_messages = [
                sprintf(__('The constant %s is not present.'), $this->name),
            ];
        } else {
            $this->validation_messages = [
                sprintf(__('The constant %s is missing.'), $this->name),
            ];
        }
    }
}
