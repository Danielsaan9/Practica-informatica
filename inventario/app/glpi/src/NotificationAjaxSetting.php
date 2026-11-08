<?php

use Glpi\Application\View\TemplateRenderer;

/**
 *  This class manages the ajax notifications settings
 */
class NotificationAjaxSetting extends NotificationSetting
{
    #[Override]
    public static function getTypeName($nb = 0)
    {
        return __('Browser notifications configuration');
    }

    public function getEnableLabel()
    {
        return __('Enable browser notifications');
    }

    public static function getMode()
    {
        return Notification_NotificationTemplate::MODE_AJAX;
    }

    public function showFormConfig()
    {
        global $CFG_GLPI;

        if ($CFG_GLPI['notifications_ajax']) {
            $crontask = new CronTask();
            $crontask->getFromDBbyName('QueuedNotification', 'queuednotificationcleanstaleajax');

            TemplateRenderer::getInstance()->display('pages/setup/notification/ajax_setting.html.twig', [
                'stale_crontask_name' => $crontask->getName(),
                'item' => $this,
                'params' => [
                    'candel' => false,
                    'addbuttons' => ['test_ajax_send' => __('Send a test browser notification to you')],
                ],
            ]);
        } else {
            $twig_params = ['message' => __('Notifications are disabled.')];
            // language=Twig
            echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
                <div class="alert alert-warning">
                    <a href="{{ path('front/setup.notification.php') }}">{{ message }}</a>
                </div>
TWIG, $twig_params);
        }
    }

    #[Override]
    public static function getIcon()
    {
        return "ti ti-message";
    }
}
