<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\Asset;
use Glpi\Asset\CapacityConfig;
use Notepad;
use Override;
use ReflectionClass;
use Session;

class HasNotepadCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Notepad::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Notepad::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Enable a simple notepad");
    }

    public function getSearchOptions(string $classname): array
    {
        return Notepad::rawSearchOptionsToAdd();
    }

    public function getSpecificRights(): array
    {
        return [READNOTE, UPDATENOTE];
    }

    public function getCloneRelations(): array
    {
        return [
            Notepad::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Notepad::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s notes attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Notepad::class),
            $this->countAssetsLinkedToPeerItem($classname, Notepad::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        CommonGLPI::registerStandardTab($classname, Notepad::class, 80);
    }

    public function onObjectInstanciation(Asset $object, CapacityConfig $config): void
    {
        $reflected_class = new ReflectionClass($object);
        $reflected_property = $reflected_class->getProperty('usenotepad');
        $reflected_property->setValue($object, true);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Delete related infocom data
        $notepad = new Notepad();
        $notepad->deleteByCriteria(['itemtype' => $classname], force: true, history: false);

        // Clean history related to notepad
        $this->deleteRelationLogs($classname, Notepad::class);

        // Clean display preferences
        $notepad_search_options = Notepad::rawSearchOptionsToAdd();
        $this->deleteDisplayPreferences($classname, $notepad_search_options);
    }
}
