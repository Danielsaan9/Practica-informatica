<?php

namespace Glpi\Console\Migration;

use Glpi\Form\AccessControl\FormAccessControlManager;
use Glpi\Form\Migration\FormMigration;
use Glpi\Migration\AbstractPluginMigration;
use Override;
use Symfony\Component\Console\Input\InputOption;

class FormCreatorPluginToCoreCommand extends AbstractPluginMigrationCommand
{
    #[Override]
    public function getName(): string
    {
        return 'migration:formcreator_plugin_to_core';
    }

    #[Override]
    public function getDescription(): string
    {
        return sprintf(__('Migrate %s plugin data into GLPI core tables'), 'Formcreator');
    }

    #[Override]
    public function getMigration(): AbstractPluginMigration
    {
        return new FormMigration(
            $this->getDb(),
            FormAccessControlManager::getInstance(),
            $this->input->getOption('form-id')
        );
    }

    #[Override]
    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'form-id',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            __('Import only specific forms with the given IDs'),
            []
        );
    }
}
