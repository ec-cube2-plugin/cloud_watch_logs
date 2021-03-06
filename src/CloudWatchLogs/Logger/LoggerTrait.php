<?php

namespace CloudWatchLogs\Logger;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use CloudWatchLogs\Monolog\Processor\UserProcessor;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

trait LoggerTrait
{
    private static $logger = [];

    private static $client;

    /**
     * @param string $msg
     * @param string $path
     * @param bool $verbose
     */
    public static function gfPrintLog($msg, $path = '', $verbose = USE_VERBOSE_LOG)
    {
        if (!defined('CLOUDWATCH_LOGS_GROUP_NAME') || empty(CLOUDWATCH_LOGS_GROUP_NAME)
            || !defined('CLOUDWATCH_LOGS_STREAM_NAME') || empty(CLOUDWATCH_LOGS_STREAM_NAME)) {
            parent::gfPrintLog($msg, $path, $verbose);

            return;
        }

        if (empty($path)) {
            $path = \GC_Utils_Ex::isAdminFunction() ? ADMIN_LOG_REALFILE : LOG_REALFILE;
        }

        // logger
        $name = basename($path, '.log');
        $logger = static::getLogger($name);

        // stack trace
        if ($verbose && $name !== 'error') {
            foreach ($logger->getHandlers() as $handler) {
                $formatter = $handler->getFormatter();
                if (method_exists($formatter, 'includeStacktraces')) {
                    $formatter->includeStacktraces(true);
                }
            }
        }

        if ($name === 'error') {
            $logger->error($msg);
        } else {
            $logger->info($msg);
        }

        if ($verbose && $name !== 'error') {
            foreach ($logger->getHandlers() as $handler) {
                $formatter = $handler->getFormatter();
                if (method_exists($formatter, 'includeStacktraces')) {
                    $formatter->includeStacktraces(false);
                }
            }
        }
    }

    public static function getCloudWatchLogsClient()
    {
        if (!isset(self::$client)) {
            $sdkParams = [
                'region' => 'ap-northeast-1',
                'version' => 'latest',
            ];
            if (defined('AWS_REGION') && !empty(AWS_REGION)) {
                $sdkParams['region'] = AWS_REGION;
            }
            if (defined('AWS_ACCESS_KEY_ID') && !empty(AWS_ACCESS_KEY_ID)) {
                $sdkParams['credentials']['key'] = AWS_ACCESS_KEY_ID;
            }
            if (defined('AWS_SECRET_ACCESS_KEY') && !empty(AWS_SECRET_ACCESS_KEY)) {
                $sdkParams['credentials']['secret'] = AWS_SECRET_ACCESS_KEY;
            }
            $client = new CloudWatchLogsClient($sdkParams);
            self::$client = $client;
        }

        return self::$client;
    }

    /**
     * @param $name
     * @return Logger
     * @throws \Exception
     */
    public static function getLogger($name)
    {
        if (!defined('CLOUDWATCH_LOGS_GROUP_NAME') || empty(CLOUDWATCH_LOGS_GROUP_NAME)) {
            throw new \Exception('CLOUDWATCH_LOGS_GROUP_NAME を設定してください');
        }
        if (!defined('CLOUDWATCH_LOGS_STREAM_NAME') || empty(CLOUDWATCH_LOGS_STREAM_NAME)) {
            throw new \Exception('CLOUDWATCH_LOGS_STREAM_NAME を設定してください');
        }

        if (!isset(self::$logger[$name])) {
            // handler
            $client = static::getCloudWatchLogsClient();
            $group = str_replace(['%name%'], [$name], CLOUDWATCH_LOGS_GROUP_NAME);
            $stream = str_replace(['%name%'], [$name], CLOUDWATCH_LOGS_STREAM_NAME);
            if (defined('CLOUDWATCH_LOGS_RETENTION') && CLOUDWATCH_LOGS_RETENTION !== '') {
                $retention = CLOUDWATCH_LOGS_RETENTION;
            } else {
                $retention = null;
            }
            $handler = new CloudWatch($client, $group, $stream, $retention, 10000);
            $handler->setFormatter(new JsonFormatter());

            // error のみ includeStacktraces をデフォルトで有効に
            if ($name === 'error') {
                $formatter = $handler->getFormatter();
                if (method_exists($formatter, 'includeStacktraces')) {
                    $formatter->includeStacktraces(true);
                }
            }

            // logger
            $logger = new Logger($name);
            $logger
                ->pushHandler($handler)
                ->pushProcessor(new WebProcessor())
                ->pushProcessor(new UserProcessor());

            self::$logger[$name] = $logger;
        }

        return self::$logger[$name];
    }
}
