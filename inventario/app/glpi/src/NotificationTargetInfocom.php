<?php

/**
 * NotificationTargetInfocom Class
 *
 * @extends NotificationTarget<Infocom>
 */
class NotificationTargetInfocom extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['alert' => __('Alarms on financial and administrative information')];
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {

        $events                                 = $this->getAllEvents();

        $this->data['##infocom.entity##']      = Dropdown::getDropdownName(
            'glpi_entities',
            $options['entities_id']
        );
        $this->data['##infocom.action##']      = $events[$event];

        foreach ($options['items'] as $id => $item) {
            $tmp = [];

            if ($obj = getItemForItemtype($item['itemtype'])) {
                $tmp['##infocom.itemtype##']
                                     = $obj->getTypeName(1);
                $tmp['##infocom.item##'] = $item['item_name'];
                $tmp['##infocom.expirationdate##']
                                     = $item['warrantyexpiration'];
                $tmp['##infocom.url##']  = $this->formatURL(
                    $options['additionnaloption']['usertype'],
                    $item['itemtype'] . "_"
                    . $item['items_id'] . "_Infocom"
                );
            }
            $this->data['infocoms'][] = $tmp;
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

        $tags = ['infocom.action'         => _n('Event', 'Events', 1),
            'infocom.itemtype'       => __('Item type'),
            'infocom.item'           => _n('Associated item', 'Associated items', 1),
            'infocom.expirationdate' => __('Expiration date'),
            'infocom.entity'         => Entity::getTypeName(1),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $this->addTagToList(['tag'     => 'items',
            'label'   => __('Device list'),
            'value'   => false,
            'foreach' => true,
        ]);

        asort($this->tag_descriptions);
    }
}
