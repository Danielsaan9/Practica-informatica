<?php

/**
 * class XHProf
 *
 * @since 0.84
 *
 * Il you need to "profile" some part of code
 *
 * Install the pecl/xhprof extension
 *
 * Add XHPROF_PATH and XHPROF_URL in config/local_define.php (if needed)
 *
 * Before the code
 *    $prof = new XHProf("something useful");
 *
 * If the code contains an exit() or a redirect() you must also call (before)
 *    unset($prof);
 *
 * php-errors.log will give you the URL of the result.
 */
class XHProf
{
    // this can be overloaded in config/local_define.php
    public const XHPROF_PATH = '/usr/share/xhprof/xhprof_lib';
    public const XHPROF_URL  = '/xhprof';


    private static bool $run = false;


    /**
     * @param string $msg (default '')
     **/
    public function __construct($msg = '')
    {
        $this->start($msg);
    }


    public function __destruct()
    {
        $this->stop();
    }


    /**
     * @param string $msg (default '')
     *
     * @return void
     */
    public function start($msg = '')
    {

        if (
            !self::$run
            && function_exists('xhprof_enable')
        ) {
            xhprof_enable(
                XHPROF_FLAGS_NO_BUILTINS
                | XHPROF_FLAGS_CPU
                | XHPROF_FLAGS_MEMORY
            );

            if (class_exists('Toolbox')) {
                Toolbox::logDebug("Start profiling with XHProf", $msg);
            }
            self::$run = true;
        }
    }


    /**
     * @return void
     */
    public function stop()
    {
        if (self::$run) {
            $incl = (defined('XHPROF_PATH') ? XHPROF_PATH : self::XHPROF_PATH);
            require_once $incl . '/utils/xhprof_lib.php';
            require_once $incl . '/utils/xhprof_runs.php';

            if (!class_exists("XHProfRuns_Default")) {
                throw new RuntimeException("pecl/xhprof is not installed");
            }

            $data = xhprof_disable();
            $runs = new XHProfRuns_Default();
            $id   = $runs->save_run($data, 'glpi');

            $url  = (defined('XHPROF_URL') ? XHPROF_URL : self::XHPROF_URL);
            $host = ($_SERVER['HTTP_HOST'] ?? 'localhost');
            $link = "http://" . $host . "$url/index.php?run=$id&source=glpi";
            Toolbox::logDebug("Stop profiling with XHProf, result URL", $link);

            self::$run = false;
        }
    }
}
