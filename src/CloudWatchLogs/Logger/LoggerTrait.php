<?php

namespace CloudWatchLogs\Logger;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Logger;

trait LoggerTrait
{
    /**
     * @param string $msg
     * @param string $path
     * @param bool $verbose
     */
    public static function gfPrintLog($msg, $path = '', $verbose = USE_VERBOSE_LOG)
    {
        \GC_Utils_Base::gfPrintLog($msg, $path, $verbose);
    }
}
