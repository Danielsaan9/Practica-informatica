<?php

/**
 * NotificationTargetConsumableItem Class
 *
 * @extends NotificationTarget<ConsumableItem>
 **/
class NotificationTargetConsumableItem extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['alert' => __('Consumables alarm')];
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {

        $events                                    = $this->getAllEvents();

        $this->data['##consumable.entity##']      = Dropdown::getDropdownName(
            'glpi_entities',
            $options['entities_id']
        );
        $this->data['##lang.consumable.entity##'] = Entity::getTypeName(1);
        $this->data['##consumable.action##']      = $events[$event];

        foreach ($options['items'] as $id => $consumable) {
            $remaining_stock = Consumable::getUnusedNumber($id);
            $target_stock = Consumable::getStockTarget($id);
            if ($target_stock <= 0) {
                $alarm_threshold = Consumable::getAlarmThreshold($id);
                $target_stock = $alarm_threshold + 1;
            }
            $to_order = $target_stock - $remaining_stock;
            $tmp                             = [];
            $tmp['##consumable.item##']      = $consumable['name'];
            $tmp['##consumable.reference##'] = $consumable['ref'];
            $tmp['##consumable.remaining##'] = $remaining_stock;
            $tmp['##consumable.stock_target##'] = $target_stock;
            $tmp['##consumable.to_order##'] = $to_order;
            $tmp['##consumable.url##']       = $this->formatURL(
                $options['additionnaloption']['usertype'],
                "ConsumableItem_" . $id
            );
            $this->data['consumables'][] = $tmp;
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

        $tags = [
            'consumable.action'        => _n('Event', 'Events', 1),
            'consumable.reference'     => __('Reference'),
            'consumable.item'          => ConsumableItem::getTypeName(1),
            'consumable.remaining'     => __('Remaining'),
            'consumable.stock_target'  => __('Stock target'),
            'consumable.to_order'      => __('To order'),
            'consumable.entity'        => Entity::getTypeName(1),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $this->addTagToList(['tag'     => 'consumables',
            'label'   => __('Device list'),
            'value'   => false,
            'foreach' => true,
        ]);

        asort($this->tag_descriptions);
    }
}
