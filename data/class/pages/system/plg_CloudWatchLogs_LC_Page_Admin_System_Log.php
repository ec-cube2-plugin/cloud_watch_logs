<?php

use Aws\CloudWatchLogs\Exception\CloudWatchLogsException;

/**
 * LC_Page_Admin_System_Log
 */
class plg_CloudWatchLogs_LC_Page_Admin_System_Log extends LC_Page_Admin_System_Log
{
    public $arrAUTHORITY;

    public function init()
    {
        parent::init();

        $masterData = new \SC_DB_MasterData_Ex();
        $this->arrAUTHORITY = $masterData->getMasterData('mtb_authority');
    }

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
                'log' => $log,
            ];
        }

        return array_reverse($arrLogs);
    }

    /**
     * ログファイルのパスを取得する
     *
     * セキュリティ面をカバーする役割もある。
     */
    public function getLogPath($log_name)
    {
        if (strlen($log_name) === 0) {
            return LOG_REALFILE;
        }
        if (defined($const_name = $log_name . '_LOG_REALFILE')) {
            return constant($const_name);
        }
        if ($log_name === 'PAYGENT' && defined('PAYGENT_LOG_PATH')) {
            return PAYGENT_LOG_PATH;
        }
        if ($log_name === 'PAYPAL_EXPRESS' && defined('PAYPAL_EXPRESS_LOG_PATH')) {
            return PAYPAL_EXPRESS_LOG_PATH;
        }
        if ($log_name === 'DROPPED_ITEMS_NOTICER' && defined('DROPPED_ITEMS_NOTICER_LOG')) {
            return DROPPED_ITEMS_NOTICER_LOG;
        }
        trigger_error('不正なログが指定されました。', E_USER_ERROR);
    }

    public function loadLogList()
    {
        parent::loadLogList();

        if (defined('PAYGENT_LOG_PATH') && PAYGENT_LOG_PATH) {
            $this->arrLogList['PAYGENT'] = 'ペイジェントログファイル';
        }

        if (defined('PAYPAL_EXPRESS_LOG_PATH') && PAYPAL_EXPRESS_LOG_PATH) {
            $this->arrLogList['PAYPAL_EXPRESS'] = 'ペイパルエクスプレスログファイル';
        }

        if (defined('DROPPED_ITEMS_NOTICER_LOG') && DROPPED_ITEMS_NOTICER_LOG) {
            $this->arrLogList['DROPPED_ITEMS_NOTICER'] = 'ペイパルカゴ落ち通知メルマガ配信ログファイル';
        }

    }
}
