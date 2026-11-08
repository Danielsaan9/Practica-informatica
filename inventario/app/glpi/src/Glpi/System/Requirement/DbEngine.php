<?php

namespace Glpi\System\Requirement;

use DBmysql;

use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * @since 9.5.0
 */
class DbEngine extends AbstractRequirement
{
    /**
     * DB instance.
     *
     */
    private DBmysql $db;

    public function __construct(DBmysql $db)
    {
        parent::__construct(
            __('DB engine version')
        );

        $this->db = $db;
    }

    protected function check()
    {
        $version_string = $this->db->getVersion();

        $server  = preg_match('/-MariaDB/', $version_string) ? 'MariaDB' : 'MySQL';
        $version = preg_replace('/^((\d+\.?)+).*$/', '$1', $version_string);

        switch ($server) {
            case 'MariaDB':
                $min_version = '10.11';
                break;
            case 'MySQL':
            default:
                $min_version = '8.0';
                break;
        }
        $is_supported = version_compare($version, $min_version, '>=');

        if ($is_supported) {
            $this->validated = true;
            $this->validation_messages[] = sprintf(
                __('Database engine version (%s) is supported.'),
                $version
            );
        } else {
            $msg = sprintf(__('Database engine version (%s) is not supported.'), $version);
            $msg .= ' ' . sprintf('Minimum required version is %s %s.', $server, $min_version);
            $this->validated = false;
            $this->validation_messages[] = $msg;
        }
    }
}
