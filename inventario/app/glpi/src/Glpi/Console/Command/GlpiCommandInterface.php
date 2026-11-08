<?php

namespace Glpi\Console\Command;

use Glpi\System\Requirement\RequirementInterface;

interface GlpiCommandInterface
{
    /**
     * Defines whether or not mandatory requirements must be checked before running command.
     *
     * @return bool
     */
    public function mustCheckMandatoryRequirements(): bool;

    /**
     * Defines whether or not mandatory requirements must be checked before running command.
     *
     * @return RequirementInterface[]
     */
    public function getSpecificMandatoryRequirements(): array;

    /**
     * Defines whether or not command requires an up-to-date database to be executed.
     *
     * @return bool
     */
    public function requiresUpToDateDb(): bool;
}
