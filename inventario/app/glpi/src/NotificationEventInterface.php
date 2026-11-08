<?php

interface NotificationEventInterface
{
    /**
     * Raise a notification event
     *
     * @param string                                    $event              Event
     * @param CommonGLPI                                $item               Notification data
     * @param array                                     $options            Options
     * @param string                                    $label              Label
     * @param array                                     $data               Notification data
     * @param NotificationTarget<covariant CommonGLPI>  $notificationtarget Target
     * @param NotificationTemplate                      $template           Template
     * @param bool                                   $notify_me          Whether to notify current user
     * @param mixed                                     $emitter            If this action is executed by the cron, we can
     *                                                                      supply the id of the user (or the email if this
     *                                                                      is an anonymous user with no account) who
     *                                                                      triggered the event so it can be used instead of
     *                                                                      getLoginUserID
     * @param CommonDBTM|null                           $trigger            Item that raises the notification (in case notification was raised by a child item).
     *
     * @return void
     *
     * @since 11.0.0 Param `$trigger` has been added.
     */
    public static function raise(
        $event,
        CommonGLPI $item,
        array $options,
        $label,
        array $data,
        NotificationTarget $notificationtarget,
        NotificationTemplate $template,
        $notify_me,
        $emitter = null,
        ?CommonDBTM $trigger = null
    );


    /**
     * Get target field name
     *
     * @return string
     */
    public static function getTargetFieldName();

    /**
     * Get (and populate if needed) target field for notification
     *
     * @param array $data Input event data
     *
     * @return string
     */
    public static function getTargetField(&$data);

    /**
     * Whether notifications can be handled by a crontab
     *
     * @return bool
     */
    public static function canCron();

    /**
     * Get admin data
     *
     * @return array
     */
    public static function getAdminData();

    /**
     * Get entity admin data
     *
     * @param int $entity Entity ID
     *
     * @return array
     */
    public static function getEntityAdminsData($entity);

    /**
     * Send notification
     *
     * @param array $data Data to send
     *
     * @return false|int False if something went wrong, number of send notifications otherwise
     */
    public static function send(array $data);
}
