<?php

use Aws\CloudWatchLogs\Exception\CloudWatchLogsException;

/**
 * LC_Page_Admin_System_Log
 */
class plg_CloudWatchLogs_LC_Page_Admin_System_Log extends LC_Page_Admin_System_Log
{
    /**
     * EC-CUBE ログを取得する.
     *
     * @return array $arrLogs 取得したログ
     */
    public function getEccubeLog($log_path_base)
    {
        if (!defined('CLOUDWATCH_LOGS_GROUP_NAME') || empty(CLOUDWATCH_LOGS_GROUP_NAME)
            || !defined('CLOUDWATCH_LOGS_STREAM_NAME') || empty(CLOUDWATCH_LOGS_STREAM_NAME)) {
            return parent::getEccubeLog($log_path_base);
        }

        $name = basename($log_path_base, '.log');
        $group = str_replace(['%name%'], [$name], CLOUDWATCH_LOGS_GROUP_NAME);
        $stream = str_replace(['%name%'], [$name], CLOUDWATCH_LOGS_STREAM_NAME);

        $client = GC_Utils_Ex::getCloudWatchLogsClient();
        try {
            $result = $client->getLogEvents([
                'limit' => (int) $this->line_max,
                'logGroupName' => $group,
                'logStreamName' => $stream,
                'startFromHead' => false,
            ]);
        } catch (CloudWatchLogsException $e) {
            if ($e->getAwsErrorCode() === 'ResourceNotFoundException') {
                return [];
            }

            throw $e;
        }

        $arrLogs = array();
        foreach ($result['events'] as $event) {
            $log = json_decode($event['message'], true);

            $body = $log['message'];
            $body = preg_replace('|(\s+sid=)[0-9a-zA-Z]+|', '$1[Filtered]', $body);

            $date = isset($log['datetime']['date']) ? $log['datetime']['date'] : '';
            $date = preg_replace('|^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\.\d{6}$|', '$1', $date);

            $arrLogs[] = [
                'date' => $date,
                'path' => isset($log['extra']['url']) ? $log['extra']['url'] : '',
                'body' => $body,
            ];
        }

        return array_reverse($arrLogs);
    }
}
