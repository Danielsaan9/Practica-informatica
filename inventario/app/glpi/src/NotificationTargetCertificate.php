<?php

/**
 * NotificationTargetSoftwareLicense Class
 *
 * @extends NotificationTarget<Certificate>
 *
 * @since 9.2
 **/
class NotificationTargetCertificate extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['alert' => __('Alarm on expired certificate')];
    }

    public function addAdditionalTargets($event = '')
    {
        $this->addTarget(
            Notification::ITEM_TECH_IN_CHARGE,
            __('Technician in charge of the certificate')
        );
        $this->addTarget(
            Notification::ITEM_TECH_GROUP_IN_CHARGE,
            __('Group in charge of the certificate')
        );
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {

        $events = $this->getAllEvents();
        $certificate = $this->obj;

        $options['certificates'] = [];
        if (!$certificate->isNewItem()) {
            $options['certificates'][] = $certificate->fields;// Compatibility with old behaviour
        }
        $options['entities_id'] = $certificate->fields['entities_id'];

        $this->data['##certificate.action##'] = $events[$event];
        $this->data['##certificate.entity##'] = Dropdown::getDropdownName(
            'glpi_entities',
            $options['entities_id']
        );

        $this->data['##certificate.name##']           = $certificate->fields['name'];
        $this->data['##certificate.serial##']         = $certificate->fields['serial'];
        $this->data['##certificate.type##'] = Dropdown::getDropdownName(
            'glpi_certificatetypes',
            $certificate->fields['certificatetypes_id']
        );
        $this->data['##certificate.expirationdate##'] = Html::convDate($certificate->fields["date_expiration"]);
        $this->data['##certificate.url##']            = $this->formatURL(
            $options['additionnaloption']['usertype'],
            "Certificate_" . $certificate->getID()
        );

        foreach ($options['certificates'] as $id => $certificate_data) {
            // Old behaviour preserved as notifications rewriting in migrations is kind of complicated
            $this->data['certificates'][] = [
                '##certificate.name##'           => $certificate_data['name'],
                '##certificate.serial##'         => $certificate_data['serial'],
                '##certificate.expirationdate##' => Html::convDate($certificate_data["date_expiration"]),
                '##certificate.url##'            => $this->formatURL(
                    $options['additionnaloption']['usertype'],
                    "Certificate_" . $id
                ),
            ];
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

        $tags = ['certificate.expirationdate' => __('Expiration date'),
            'certificate.name'           => __('Name'),
            'certificate.type'         => _n('Type', 'Types', 1),
            'certificate.serial'         => __('Serial number'),
            'certificate.url'            => __('URL'),
            'certificate.entity'         => Entity::getTypeName(1),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $this->addTagToList(['tag'     => 'certificates',
            'label'   => __('Certificates list (deprecated; contains only one element)'),
            'value'   => false,
            'foreach' => true,
        ]);

        asort($this->tag_descriptions);
    }
}
