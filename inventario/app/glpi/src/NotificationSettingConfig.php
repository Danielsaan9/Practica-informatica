<?php

use Glpi\Application\View\TemplateRenderer;

/**
 * Notifications settings configuration class
 */
class NotificationSettingConfig extends CommonDBTM
{
    public string $table           = 'glpi_configs';
    protected bool $displaylist  = false;
    public static string $rightname       = 'config';

    #[Override]
    public function update(array $input, $history = true, $options = [])
    {
        $success = true;

        $config_id = Config::getConfigIDForContext('core');
        if (isset($input['use_notifications'])) {
            $config = new Config();
            $tmp = [
                'id'                 => $config_id,
                'use_notifications'  => $input['use_notifications'],
            ];
            if (!$config->update($tmp)) {
                $success = false;
            }
            //disable all notifications types if notifications has been disabled
            if ($tmp['use_notifications'] == 0) {
                $modes = Notification_NotificationTemplate::getModes();
                foreach (array_keys($modes) as $mode) {
                    $input['notifications_' . $mode] = 0;
                }
            }
        }

        $config = new Config();
        foreach ($input as $k => $v) {
            if (str_starts_with($k, 'notifications_')) {
                $tmp = [
                    'id' => $config_id,
                    $k    => $v,
                ];
                if (!$config->update($tmp)) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Show configuration form
     *
     * @param array{display?: bool} $options
     * @return string|void
     */
    public function showConfigForm($options = [])
    {
        global $CFG_GLPI;

        if (!isset($options['display'])) {
            $options['display'] = true;
        }

        $modes = Notification_NotificationTemplate::getModes();
        foreach ($modes as $mode_key => &$mode) {
            $settings_class = Notification_NotificationTemplate::getModeClass($mode_key, 'setting');
            $settings = getItemForItemtype($settings_class);
            $mode['label']          = $settings->getEnableLabel();
            $mode['label_settings'] = $settings->getTypeName();
            $mode['is_active']      = (bool) $CFG_GLPI["notifications_$mode_key"];
            $mode['setting_url']    = $settings->getFormURL();
            $mode['icon']           = $settings::getIcon();
        }

        $out = TemplateRenderer::getInstance()->render(
            'pages/setup/setup_notifications.html.twig',
            [
                'use_notifications' => (bool) $CFG_GLPI['use_notifications'],
                'has_active_mode'   => Notification_NotificationTemplate::hasActiveMode(),
                'can_update_config' => Session::haveRight(Config::$rightname, UPDATE) > 0,
                'modes'             => $modes,
            ]
        );

        if ($options['display']) {
            echo $out;
        } else {
            return $out;
        }
    }
}
