<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Item_Project;
use Override;
use Project;

class IsProjectAssetCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Project::getTypeName();
    }

    public function getIcon(): string
    {
        return Project::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Can be associated to a project");
    }

    public function getCloneRelations(): array
    {
        return [
            Item_Project::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Item_Project::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s assets used in %2$s projects'),
            $this->countAssetsLinkedToPeerItem($classname, Item_Project::class),
            $this->countPeerItemsUsage($classname, Item_Project::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        // Allow our item to be linked to projects
        $this->registerToTypeConfig('project_asset_types', $classname);

        CommonGLPI::registerStandardTab($classname, Item_Project::class, 95);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from project assets types
        $this->unregisterFromTypeConfig('project_asset_types', $classname);

        // Delete related project data
        $project_item = new Item_Project();
        $project_item->deleteByCriteria(
            crit   : ['itemtype' => $classname],
            force  : true,
            history: false
        );

        // Clean history related to projects (both sides of the relation)
        $this->deleteRelationLogs($classname, Project::class);

        // Clean display preferences
        $this->deleteDisplayPreferences(
            $classname,
            Project::rawSearchOptionsToAdd($classname)
        );
    }
}
