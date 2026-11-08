<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Document;
use Document_Item;
use Glpi\Asset\CapacityConfig;
use Override;
use Session;

class HasDocumentsCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Document::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Document::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Upload and attach files");
    }

    public function getCloneRelations(): array
    {
        return [
            Document_Item::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        // `timeline_position=0` is the value when a document is attached manually
        // filtering on this value prevents counting documents attached from rich text fields
        $specific_criteria = ['timeline_position' => 0];

        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Document_Item::class, $specific_criteria) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        // `timeline_position=0` is the value when a document is attached manually
        // filtering on this value prevents removal of documents attached from rich text fields
        $specific_criteria = ['timeline_position' => 0];

        return sprintf(
            __('%1$s documents attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Document_Item::class, $specific_criteria),
            $this->countAssetsLinkedToPeerItem($classname, Document_Item::class, $specific_criteria)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('document_types', $classname);

        CommonGLPI::registerStandardTab($classname, Document_Item::class, 55);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from document types
        $this->unregisterFromTypeConfig('document_types', $classname);

        // Delete relations to documents
        $document_item = new Document_Item();
        $document_item->deleteByCriteria(
            [
                'itemtype' => $classname,
                // 0 is the value when a document is attached manually
                // filtering on this value prevents removal of documents attached from rich text fields
                'timeline_position' => 0,
            ],
            force: true,
            history: false
        );

        // Clean history related to documents
        $this->deleteRelationLogs($classname, Document::class);

        // Clean display preferences
        $documents_search_options = Document::rawSearchOptionsToAdd($classname);
        $this->deleteDisplayPreferences($classname, $documents_search_options);
    }
}
