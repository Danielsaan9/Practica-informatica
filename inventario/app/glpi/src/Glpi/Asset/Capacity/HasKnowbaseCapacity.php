<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use KnowbaseItem;
use KnowbaseItem_Item;
use Override;
use Session;

class HasKnowbaseCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return KnowbaseItem::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return KnowbaseItem::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Knowledge base articles can be associated to these assets");
    }

    public function getCloneRelations(): array
    {
        return [
            KnowbaseItem_Item::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, KnowbaseItem_Item::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s knowbase items attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, KnowbaseItem_Item::class),
            $this->countAssetsLinkedToPeerItem($classname, KnowbaseItem_Item::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('kb_types', $classname);

        CommonGLPI::registerStandardTab($classname, KnowbaseItem_Item::class, 70);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        $this->unregisterFromTypeConfig('kb_types', $classname);

        $kb_item = new KnowbaseItem_Item();
        $kb_item->deleteByCriteria([
            'itemtype' => $classname,
        ], true, false);

        $this->deleteRelationLogs($classname, KnowbaseItem::class);
        $this->deleteRelationLogs($classname, KnowbaseItem_Item::class);
    }
}
