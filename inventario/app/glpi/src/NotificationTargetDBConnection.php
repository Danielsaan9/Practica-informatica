<?php

/**
 * Class NotificationTarget
 *
 * @extends NotificationTarget<DBConnection>
 */
class NotificationTargetDBConnection extends NotificationTarget
{
    #[Override]
    public function addNotificationTargets($entity)
    {
        $this->addProfilesToTargets();
        $this->addGroupsToTargets($entity);
        $this->addTarget(Notification::GLOBAL_ADMINISTRATOR, __('Administrator'));
    }

    #[Override]
    public function getEvents()
    {
        return ['desynchronization' => __('Desynchronization SQL replica')];
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {
        if ($options['diff'] > 1000000000) {
            $tmp = __("Can't connect to the database.");
        } else {
            $tmp = Html::timestampToString($options['diff'], true);
        }
        $this->data['##dbconnection.delay##'] = $tmp . " (" . $options['name'] . ")";

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
        $tags = ['dbconnection.delay' => __('Difference between main and replica')];
        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
                'lang'  => true,
            ]);
        }

        //Tags with just lang
        $tags = ['dbconnection.title'
                                 => __('Replica database out of sync!'),
            'dbconnection.delay'
                                 => __('The replica database is desynchronized. The difference is of:'),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => false,
                'lang'  => true,
            ]);
        }

        asort($this->tag_descriptions);
    }
}
