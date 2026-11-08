<?php

/**
 * ProjectState Class
 *
 * @since 0.85
 **/
class ProjectState extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Project state', 'Project states', $nb);
    }

    public function post_getEmpty()
    {
        $this->fields['color'] = '#dddddd';
    }

    public function getAdditionalFields()
    {
        return [
            [
                'name'     => 'color',
                'label'    => __('Color'),
                'type'     => 'color',
                'list'     => true,
            ],
            [
                'name'     => 'is_finished',
                'label'    => __('Finished state'),
                'type'     => 'bool',
                'list'     => true,
            ],
        ];
    }

    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '11',
            'table'              => static::getTable(),
            'field'              => 'color',
            'name'               => __('Color'),
            'datatype'           => 'color',
        ];

        $tab[] = [
            'id'                 => '12',
            'table'              => static::getTable(),
            'field'              => 'is_finished',
            'name'               => __('Finished state'),
            'datatype'           => 'bool',
        ];

        return $tab;
    }

    public static function getIcon()
    {
        return "ti ti-label";
    }
}
