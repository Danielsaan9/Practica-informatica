<?php

namespace Glpi\System\Requirement;

final class IntegerSize extends AbstractRequirement
{
    public function __construct()
    {
        parent::__construct(
            __('PHP maximal integer size'),
            __('Support of 64 bits integers is required for IP addresses related operations (network inventory, API clients IP filtering, ...).')
        );
    }

    protected function check()
    {
        if (PHP_INT_SIZE < 8) {
            $this->validated = false;
            $this->validation_messages[] = __('OS or PHP is not relying on 64 bits integers.');
        } else {
            $this->validated = true;
            $this->validation_messages[] = __('OS and PHP are relying on 64 bits integers.');
        }
    }
}
