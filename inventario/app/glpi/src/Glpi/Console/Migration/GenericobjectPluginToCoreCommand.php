<?php

namespace Glpi\Console\Migration;

use Glpi\Migration\AbstractPluginMigration;
use Glpi\Migration\GenericobjectPluginMigration;
use Override;

class GenericobjectPluginToCoreCommand extends AbstractPluginMigrationCommand
{
    #[Override]
    public function getName(): string
    {
        return 'migration:genericobject_plugin_to_core';
    }

    #[Override]
    public function getDescription(): string
    {
        return sprintf(__('Migrate plugin data into GLPI core tables'), 'GenericObject');
    }

    #[Override]
    public function getMigration(): AbstractPluginMigration
    {
        return new GenericobjectPluginMigration($this->getDb());
    }
}
