<?php

namespace CloudWatchLogs;

use Monolog\ErrorHandler;

trait ErrorHandlerTrait
{
    public static function load()
    {
        // config.php 読み込み
        $objInit = new \SC_Initial_Ex();
        $objInit->requireInitialConfig();

        if (!defined('CLOUDWATCH_LOGS_GROUP_NAME') || empty(CLOUDWATCH_LOGS_GROUP_NAME)
            || !defined('CLOUDWATCH_LOGS_STREAM_NAME') || empty(CLOUDWATCH_LOGS_STREAM_NAME)) {
            parent::load();

            return;
        }

        error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE & ~E_DEPRECATED & ~E_STRICT);
        ini_set('display_errors', 0);

        $logger = \GC_Utils_Ex::getLogger('error');
        ErrorHandler::register($logger);

        if (
            php_sapi_name() !== 'cli'
            && !(defined('SAFE') && SAFE === true)
            && !(defined('INSTALL_FUNCTION') && INSTALL_FUNCTION === true)
        ) {
            register_shutdown_function(array(__CLASS__, 'handle_error'));
        }
    }

    /**
     * エラー捕捉時のエラーハンドラ関数
     */
    public static function handle_error()
    {
        $arrError = error_get_last();
        if ($arrError && in_array($arrError['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
            $error_type_name = \GC_Utils_Ex::getErrorTypeName($arrError['type']);
            $errstr = "Fatal error($error_type_name): {$arrError['message']} on [{$arrError['file']}({$arrError['line']})]";

            \SC_Helper_HandleError_Ex::displaySystemError($errstr);
        }
    }
}
