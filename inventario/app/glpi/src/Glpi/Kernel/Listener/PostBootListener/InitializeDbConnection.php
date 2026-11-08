<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use DBConnection;
use Glpi\Debug\Profiler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class InitializeDbConnection implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        global $DB;

        Profiler::getInstance()->start('InitializeDbConnection::execute', Profiler::CATEGORY_BOOT);

        if (file_exists(GLPI_CONFIG_DIR . '/config_db.php')) {
            include_once(GLPI_CONFIG_DIR . '/config_db.php');

            if (\class_exists('DB', false)) {
                DBConnection::establishDBConnection(false, false);

                if ($DB->connected && $DB->tableExists('glpi_configs')) {
                    // Indicates whether the existing DB data must be unsanitized on read operations,
                    // depending on the `must_unsanitize_db_data` configuration.
                    //
                    // Fallback to true, as lack of config in DB means the DB has been initialized prior to this flag
                    // introduction and, in this case, we cannot automatically know whether the DB contains sanitized data.
                    $must_unsanitize_data = $DB->request([
                        'FROM' => 'glpi_configs',
                        'WHERE' => ['name' => 'must_unsanitize_db_data', 'context' => 'core'],
                    ])->current()['value'] ?? true;

                    $DB->setMustUnsanitizeData((bool) $must_unsanitize_data);
                }
            }
        }

        Profiler::getInstance()->stop('InitializeDbConnection::execute');
    }
}
