<?php

/**
 * Class NotificationTarget
 *
 * @extends NotificationTarget<FieldUnicity>
 */
class NotificationTargetFieldUnicity extends NotificationTarget
{
    #[Override]
    public function getEvents(): array
    {
        return ['refuse' => __('Alert on duplicate record')];
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {
        //User who tries to add or update an item in DB
        $action = ($options['action_user'] ? __('Add the item') : __('Update the item'));
        $this->data['##unicity.action_type##'] = $action;
        $this->data['##unicity.action_user##'] = $options['action_user'];
        $this->data['##unicity.date##']        = Html::convDateTime($options['date']);

        if ($item = getItemForItemtype($options['itemtype'])) {
            $this->data['##unicity.itemtype##'] = $item->getTypeName(1);
            $this->data['##unicity.message##']
                  = $item->getUnicityErrorMessage($options['label'], $options['field'], $options['double']);
        }
        $this->data['##unicity.entity##']      = Dropdown::getDropdownName(
            'glpi_entities',
            $options['entities_id']
        );
        if ($options['refuse']) {
            $this->data['##unicity.action##'] = __('Record into the database denied');
        } else {
            $this->data['##unicity.action##'] = __('Item successfully added but duplicate record on');
        }
        $this->getTags();
        foreach ($this->tag_descriptions[NotificationTarget::TAG_LANGUAGE] as $tag => $values) {
            if (!isset($this->data[$tag])) {
                $this->data[$tag] = $values['label'];
            }
        }
    }

    #[Override]
    public function getTags()
    {

        $tags = ['unicity.message'     => __('Message'),
            'unicity.action_user' => __('Doer'),
            'unicity.action_type' => __('Intended action'),
            'unicity.date'        => _n('Date', 'Dates', 1),
            'unicity.itemtype'    => _n('Type', 'Types', 1),
            'unicity.entity'      => Entity::getTypeName(1),
            'unicity.action'      => __('Alert on duplicate record'),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        asort($this->tag_descriptions);
        return $this->tag_descriptions;
    }
}
