<?php

namespace Glpi\System\Requirement;

use Safe\Exceptions\FilesystemException;

use function Safe\mkdir;
use function Safe\touch;

/**
 * @since 9.5.0
 */
class LogsWriteAccess extends AbstractRequirement
{
    private const LOG_FILES = [
        'php-errors.log',
        'access-errors.log',
        'api.log',
        'cron.log',
        'event.log',
        'mail.log',
        'mail-error.log',
        'mailgate.log',
        'notification.log',
        'webhook.log',
    ];

    private string $log_dir;

    public function __construct(string $log_dir)
    {
        parent::__construct(
            __('Permissions for log files')
        );

        $this->log_dir = $log_dir;
    }

    protected function check()
    {
        if (!is_dir($this->log_dir)) {
            try {
                mkdir($this->log_dir, 0o755, true);
            } catch (FilesystemException) {
                $this->validated = false;
                $this->validation_messages[] = sprintf(__('The log directory %s could not be created.'), $this->log_dir);
                return;
            }
        }

        if (!is_writable($this->log_dir)) {
            $this->validated = false;
            $this->validation_messages[] = sprintf(__('The log directory %s is not writable.'), $this->log_dir);
            return;
        }

        $this->validated = true;

        foreach (self::LOG_FILES as $log_file) {
            $file_path = $this->log_dir . '/' . $log_file;

            if (file_exists($file_path)) {
                if (!is_writable($file_path)) {
                    $this->validated = false;
                    $this->validation_messages[] = sprintf(__('The log file %s is not writable.'), $file_path);
                }
            } else {
                try {
                    touch($file_path);
                } catch (FilesystemException) {
                    $this->validated = false;
                    $this->validation_messages[] = sprintf(__('The log file %s could not be created.'), $file_path);
                }
            }
        }

        if ($this->validated) {
            $this->validation_messages[] = __('Write access to log files has been validated.');
        }
    }
}
