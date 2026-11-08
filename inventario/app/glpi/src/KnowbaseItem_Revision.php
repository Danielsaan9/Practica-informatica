<?php

/**
 * Class KnowbaseItem_Revision
 * @since 9.2.0
 * @todo Extend CommonDBChild
 */
final class KnowbaseItem_Revision extends CommonDBTM
{
    public static string $rightname   = 'knowbase';

    #[Override]
    public static function getTypeName($nb = 0): string
    {
        return _n('Revision', 'Revisions', $nb);
    }

    #[Override]
    public static function getIcon(): string
    {
        return 'ti ti-history';
    }

    // Revisions are written only through createNew()/addToDB(); no direct write.
    #[Override]
    public static function canCreate(): bool
    {
        return false;
    }

    #[Override]
    public static function canUpdate(): bool
    {
        return false;
    }

    #[Override]
    public static function canPurge(): bool
    {
        return false;
    }

    #[Override]
    public static function canDelete(): bool
    {
        return false;
    }

    // Only editors of the parent article can see its revisions.
    #[Override]
    public function canViewItem(): bool
    {
        $kb = KnowbaseItem::getById((int) ($this->fields['knowbaseitems_id'] ?? 0));
        return $kb !== false && $kb->can($kb->getID(), UPDATE);
    }

    /**
     * Populate and create a new revision from KnowbaseItem or KnowbaseItemTranslation information
     *
     * @param KnowbaseItem|KnowbaseItemTranslation $item Knowledge base item
     *
     * @return int|false ID of the revision created, or false on error
     */
    public function createNew(KnowbaseItem|KnowbaseItemTranslation $item): int|false
    {
        $this->getEmpty();
        unset($this->fields['id']);
        $is_translation = $item::class === KnowbaseItemTranslation::class;
        $this->fields['knowbaseitems_id'] = $item->fields[$is_translation ? 'knowbaseitems_id' : 'id'];
        $this->fields['name'] = $item->fields['name'];
        $this->fields['answer'] = $item->fields['answer'];
        $this->fields['date'] = $item->fields['date_mod'];
        if ($is_translation) {
            $this->fields['language'] = $item->fields['language'];
        }
        $this->fields['revision'] = $this->getNewRevision();
        $this->fields['users_id'] = $item->fields['users_id'];
        return $this->addToDB();
    }

    /**
     * Get new revision number for item
     */
    private function getNewRevision(): int
    {
        global $DB;

        $result = $DB->request([
            'SELECT' => ['MAX' => 'revision AS revision'],
            'FROM'   => 'glpi_knowbaseitems_revisions',
            'WHERE'  => [
                'knowbaseitems_id'   => $this->fields['knowbaseitems_id'],
                'language'           => $this->fields['language'],
            ],
        ])->current();

        $rev = $result['revision'];
        if ($rev === null) {
            // no revisions yet
            $rev = 1;
        } else {
            ++$rev;
        }

        return $rev;
    }
}
