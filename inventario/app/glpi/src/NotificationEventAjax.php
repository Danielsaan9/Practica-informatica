<?php

class NotificationEventAjax extends NotificationEventAbstract
{
    public static function getTargetFieldName()
    {
        return 'users_id';
    }


    public static function getTargetField(&$data)
    {
        $field = self::getTargetFieldName();

        if (!isset($data[$field])) {
            //Missing users_id; set to null
            $data[$field] = null;
        }

        return $field;
    }


    public static function canCron()
    {
        //notifications are pulled from web browser, it must not be handled from cron
        return false;
    }


    public static function getAdminData()
    {
        //since admin cannot be logged in; no ajax notifications for global admin
        return [];
    }


    public static function getEntityAdminsData($entity)
    {
        //since entities admin cannot be logged in; no ajax notifications for them
        return [];
    }


    public static function send(array $data)
    {
        trigger_error(
            __METHOD__ . ' should not be called!',
            E_USER_WARNING
        );
        return false;
    }
}
