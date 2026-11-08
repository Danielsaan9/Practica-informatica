<?php

/**
 * NotificationTargetCartridgeItem Class
 *
 * @extends NotificationTarget<CartridgeItem>
 **/
class NotificationTargetCartridgeItem extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['alert' => __('Cartridges alarm')];
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {
        $events = $this->getAllEvents();

        $this->data['##cartridge.entity##'] = Dropdown::getDropdownName(
            'glpi_entities',
            $options['entities_id']
        );
        $this->data['##cartridge.action##'] = $events[$event];

        foreach ($options['items'] as $id => $cartridge) {
            $remaining_stock = Cartridge::getUnusedNumber($id);
            $target_stock = Cartridge::getStockTarget($id);
            if ($target_stock <= 0) {
                $alarm_threshold = Cartridge::getAlarmThreshold($id);
                $target_stock = $alarm_threshold + 1;
            }
            $to_order = $target_stock - $remaining_stock;
            $tmp                            = [];
            $tmp['##cartridge.item##']      = $cartridge['name'];
            $tmp['##cartridge.reference##'] = $cartridge['ref'];
            $tmp['##cartridge.remaining##'] = $remaining_stock;
            $tmp['##cartridge.stock_target##'] = $target_stock;
            $tmp['##cartridge.to_order##'] = $to_order;
            $tmp['##cartridge.url##']       = $this->formatURL(
                $options['additionnaloption']['usertype'],
                "CartridgeItem_" . $id
            );

            $this->data['cartridges'][] = $tmp;
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
            'cartridge.action'         => _n('Event', 'Events', 1),
            'cartridge.reference'      => __('Reference'),
            'cartridge.item'           => CartridgeItem::getTypeName(1),
            'cartridge.remaining'      => __('Remaining'),
            'cartridge.stock_target'   => __('Stock target'),
            'cartridge.to_order'       => __('To order'),
            'cartridge.url'            => __('URL'),
            'cartridge.entity'         => Entity::getTypeName(1),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $this->addTagToList(['tag'     => 'cartridges',
            'label'   => __('Device list'),
            'value'   => false,
            'foreach' => true,
        ]);

        asort($this->tag_descriptions);
    }
}
