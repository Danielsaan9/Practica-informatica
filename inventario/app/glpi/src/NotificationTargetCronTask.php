<?php

/**
 * NotificationTargetCronTask Class
 *
 * @extends NotificationTarget<CronTask>
 **/
class NotificationTargetCronTask extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['alert' => __('Monitoring of automatic actions')];
    }

    #[Override]
    public function getEventsToSendImmediately(): array
    {
        return [
            'alert',
        ];
    }

    #[Override]
    public function addDataForTemplate($event, $options = [])
    {

        $events                             = $this->getAllEvents();
        $this->data['##crontask.action##'] = $events[$event];

        $cron                               = new CronTask();
        foreach ($options['items'] as $id => $crontask) {
            $tmp                      = [];
            $tmp['##crontask.name##'] = '';

            if ($isplug = isPluginItemType($crontask["itemtype"])) {
                $tmp['##crontask.name##'] = $isplug["plugin"] . " - ";
            }

            $tmp['##crontask.name##']       .= $crontask['name'];
            $tmp['##crontask.description##'] = $cron->getDescription($id);
            $tmp['##crontask.url##']         = $this->formatURL(
                $options['additionnaloption']['usertype'],
                "CronTask_" . $id
            );
            $this->data['crontasks'][] = $tmp;
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

        $tags = ['crontask.action'      => __('Monitoring of automatic actions'),
            'crontask.url'         => __('URL'),
            'crontask.name'        => __('Name'),
            'crontask.description' => __('Description'),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }

        $this->addTagToList(['tag'     => 'crontasks',
            'label'   => __('Automatic actions list'),
            'value'   => false,
            'foreach' => true,
        ]);

        //Tags with just lang
        $tags = ['crontask.warning'
                     => __('The following automatic actions are in error. They require intervention.'),
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
