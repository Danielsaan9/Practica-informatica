<?php

/**
 * Notifications for ObjectLock
 *
 * @extends NotificationTarget<ObjectLock>
 *
 * @since 9.1
 **/
class NotificationTargetObjectLock extends NotificationTarget
{
    #[Override]
    public function getEvents()
    {
        return ['unlock'               => __('Unlock Item Request')];
    }

    #[Override]
    public function getTags()
    {

        $tags = ['objectlock.action'               => _n('Event', 'Events', 1),
            'objectlock.name'                 => __('Item Name'),
            'objectlock.id'                   => __('Item ID'),
            'objectlock.type'                 => __('Item Type'),
            'objectlock.date'                 => __('Lock date'),
            'objectlock.date_mod'             => __('Lock date'), // old field name
            'objectlock.lockedby.lastname'    => __('Lastname of locking user'),
            'objectlock.lockedby.firstname'   => __('Firstname of locking user'),
            'objectlock.requester.lastname'   => __('Requester Lastname'),
            'objectlock.requester.firstname'  => __('Requester Firstname'),
            'objectlock.url'                  => __('Item URL'),
        ];

        foreach ($tags as $tag => $label) {
            $this->addTagToList(['tag'   => $tag,
                'label' => $label,
                'value' => true,
            ]);
        }
        asort($this->tag_descriptions);
    }

    #[Override]
    public function addNotificationTargets($entity)
    {
        $this->addTarget(Notification::USER, __('Locking User'));
    }

    public function addSpecificTargets($data, $options)
    {

        $user = new User();
        if ($user->getFromDB($this->obj->fields['users_id'])) {
            $this->addToRecipientsList(['language' => $user->fields['language'],
                'users_id' => $user->getID(),
            ]);
        }
    }

    public function addDataForTemplate($event, $options = [])
    {
        global $CFG_GLPI;

        $events = $this->getEvents();

        $object = getItemForItemtype($options['item']->fields['itemtype']);
        $object->getFromDB($options['item']->fields['items_id']);
        $user = new User();
        $user->getFromDB($options['item']->fields['users_id']);

        $this->data['##objectlock.action##']   = $events[$event];
        $this->data['##objectlock.name##']     = $object->fields['name'];
        $this->data['##objectlock.id##']       = $options['item']->fields['items_id'];
        $this->data['##objectlock.type##']     = $options['item']->fields['itemtype'];
        $this->data['##objectlock.date##']     = Html::convDateTime(
            $options['item']->fields['date'],
            $user->fields['date_format']
        );
        $this->data['##objectlock.date_mod##'] = $this->data['##objectlock.date##'];
        $this->data['##objectlock.lockedby.lastname##']
                                              = $user->fields['realname'];
        $this->data['##objectlock.lockedby.firstname##']
                                              = $user->fields['firstname'];
        $this->data['##objectlock.requester.lastname##']
                                              = $_SESSION['glpirealname'];
        $this->data['##objectlock.requester.firstname##']
                                              = $_SESSION['glpifirstname'];
        $this->data['##objectlock.url##']      = $CFG_GLPI['url_base'] . "/?redirect="
                                                   . $options['item']->fields['itemtype'] . "_"
                                                   . $options['item']->fields['items_id'];

        $this->getTags();
        foreach ($this->tag_descriptions[NotificationTarget::TAG_LANGUAGE] as $tag => $values) {
            if (!isset($this->data[$tag])) {
                $this->data[$tag] = $values['label'];
            }
        }
    }

    #[Override]
    public function getSender(): array
    {

        $mails = new UserEmail();
        if (
            isset($_SESSION['glpiID']) && ($_SESSION['glpiID'] > 0)
            && isset($_SESSION['glpilock_directunlock_notification'])
            && ($_SESSION['glpilock_directunlock_notification'] > 0)
            && $mails->getFromDBByCrit([
                'users_id'    => $_SESSION['glpiID'],
                'is_default'  => 1,
            ])
        ) {
            $ret = ['email' => $mails->fields['email'],
                'name'  => formatUserName(
                    0,
                    $_SESSION["glpiname"],
                    $_SESSION["glpirealname"],
                    $_SESSION["glpifirstname"]
                ),
            ];
        } else {
            $ret = parent::getSender();
        }

        return $ret;
    }

    #[Override]
    public function getReplyTo(): array
    {
        return $this->getSender();
    }
}
