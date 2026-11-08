<?php

/**
 *  Interface to implement for each notification mode
 */
interface NotificationInterface
{
    /**
     * Send notifications
     * @param array<mixed> $options
     * @return bool
     **/
    public function sendNotification($options = []);

    /**
     * Check data
     *
     * @param mixed $value   The data to check (may differ for every notification mode)
     * @param array $options Optionnal special options (may be needed)
     *
     * @return bool
     **/
    public static function check($value, $options = []);


    /**
     * Method to test notification
     *
     * @return mixed
     **/
    public static function testNotification();
}
