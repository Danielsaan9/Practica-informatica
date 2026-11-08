<?php

use Glpi\Features\Clonable;

/**
 * Template for followups
 * @since 9.5
 **/
class ITILFollowupTemplate extends AbstractITILChildTemplate
{
    /** @use Clonable<static> */
    use Clonable;

    // From CommonDBTM
    public bool $dohistory          = true;
    public bool $can_be_translated  = true;

    public static string $rightname          = 'itilfollowuptemplate';

    public static function getTypeName($nb = 0)
    {
        return _n('Followup template', 'Followup templates', $nb);
    }


    public function getAdditionalFields()
    {
        return [
            [
                'name'  => 'requesttypes_id',
                'label' => __('Source of followup'),
                'type'  => 'dropdownValue',
                'list'  => true,
            ], [
                'name'  => 'pendingreasons_id',
                'label' => PendingReason::getTypeName(1),
                'type'  => 'dropdownValue',
                'list'  => true,
            ], [
                'name'  => 'is_private',
                'label' => __('Private'),
                'type'  => 'bool',
            ], [
                'name'  => 'content',
                'label' => __('Content'),
                'type'  => 'tinymce',
                // Images should remains in base64 in templates.
                // When an element will be created from a template, tinymce will catch the base64 image and trigger the
                // document upload process.
                'convert_images_to_documents' => false,
            ],
        ];
    }


    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '4',
            'name'               => __('Content'),
            'field'              => 'content',
            'table'              => self::getTable(),
            'datatype'           => 'text',
            'htmltext'           => true,
        ];

        $tab[] = [
            'id'                 => '5',
            'name'               => __('Source of followup'),
            'field'              => 'name',
            'table'              => getTableForItemType('RequestType'),
            'datatype'           => 'dropdown',
        ];

        $tab[] = [
            'id'                 => '6',
            'name'               => __('Private'),
            'field'              => 'is_private',
            'table'              => self::getTable(),
            'datatype'           => 'bool',
        ];

        $tab[] = [
            'id'                 => '7',
            'name'               => PendingReason::getTypeName(1),
            'field'              => 'name',
            'table'              => getTableForItemType('PendingReason'),
            'datatype'           => 'dropdown',
        ];

        return $tab;
    }

    public static function getIcon()
    {
        return "ti ti-stack-2-filled";
    }

    public function getCloneRelations(): array
    {
        return [];
    }
}
