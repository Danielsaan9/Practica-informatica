<?php

/**
 * NotificationTargetMailCollector Class
 *
 * @extends NotificationTarget<MailCollector>
 */
class NotificationTargetMailCollector extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['error' => __('Receiver errors')];
    }


    public function addDataForTemplate($event, $options = [])
    {

        $events                                  = $this->getEvents();
        $this->data['##mailcollector.action##'] = $events[$event];

        foreach ($options['items'] as $id => $mailcollector) {
            $tmp                             = [];
            $tmp['##mailcollector.name##']   = $mailcollector['name'];
            $tmp['##mailcollector.errors##'] = $mailcollector['errors'];
            $tmp['##mailcollector.url##']    = $this->formatURL(
                $options['additionnaloption']['usertype'],
                "MailCollector_" . $id
            );
            $this->data['mailcollectors'][] = $tmp;
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

        $tags = ['mailcollector.action' => _n('Event', 'Events', 1),
            'mailcollector.name'   => __('Name'),
            'mailcollector.errors' => __('Connection errors'),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $tags = ['mailcollector.url' => sprintf(
            __('%1$s: %2$s'),
            _n('Receiver', 'Receivers', 1),
            __('URL')
        ),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
                'lang'  => false,
            ]);
        }

        //Foreach global tags
        $tags = ['mailcollectors' => _n('Receiver', 'Receivers', Session::getPluralNumber())];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'     => $tag,
                'label'   => $label,
                'value'   => false,
                'foreach' => true,
            ]);
        }

        asort($this->tag_descriptions);
        return $this->tag_descriptions;
    }
}
