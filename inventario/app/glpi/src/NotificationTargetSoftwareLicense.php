<?php

/**
 * NotificationTargetSoftwareLicense Class
 *
 * @extends NotificationTarget<SoftwareLicense>
 */
class NotificationTargetSoftwareLicense extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['alert' => __('Alarms on expired licenses')];
    }


    public function addDataForTemplate($event, $options = [])
    {

        $events                            = $this->getAllEvents();

        $this->data['##license.action##'] = $events[$event];

        $this->data['##license.entity##'] = Dropdown::getDropdownName(
            'glpi_entities',
            $options['entities_id']
        );

        foreach ($options['licenses'] as $id => $license) {
            $tmp                       = [];
            $tmp['##license.item##']   = $license['softname'];
            $tmp['##license.name##']   = $license['name'];
            $tmp['##license.serial##'] = $license['serial'];
            $tmp['##license.expirationdate##']
                                    = Html::convDate($license["expire"]);
            $tmp['##license.url##']    = $this->formatURL(
                $options['additionnaloption']['usertype'],
                "SoftwareLicense_" . $id
            );
            $tmp['##license.entity##'] = Dropdown::getDropdownName(
                'glpi_entities',
                $license['entities_id']
            );
            $this->data['licenses'][] = $tmp;
        }

        $this->getTags();
        foreach ($this->tag_descriptions[NotificationTarget::TAG_LANGUAGE] as $tag => $values) {
            if (!isset($this->data[$tag])) {
                $this->data[$tag] = $values['label'];
            }
        }
    }

    #[Override]
    public function getGroupedItemsOptionName(): ?string
    {
        return 'licenses';
    }

    #[Override]
    public function getTags()
    {

        $tags = ['license.expirationdate' => __('Expiration date'),
            'license.item'           => _n('Software', 'Software', 1),
            'license.name'           => __('Name'),
            'license.serial'         => __('Serial number'),
            'license.entity'         => Entity::getTypeName(1),
            'license.url'            => __('URL'),
            'license.action'         => _n('Event', 'Events', 1),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $this->addTagToList(['tag'     => 'licenses',
            'label'   => __('Device list'),
            'value'   => false,
            'foreach' => true,
        ]);

        asort($this->tag_descriptions);
    }
}
