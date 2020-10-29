<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * プラグインのメインクラス
 */
class CloudWatchLogs extends SC_Plugin_Base
{
    public function install($arrPlugin, SC_Plugin_Installer $objPluginInstaller = null)
    {
    }

    public function uninstall($arrPlugin, SC_Plugin_Installer $objPluginInstaller = null)
    {
    }

    public function enable($arrPlugin, SC_Plugin_Installer $objPluginInstaller = null)
    {
    }

    public function disable($arrPlugin, SC_Plugin_Installer $objPluginInstaller = null)
    {
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     *
     * @param SC_Helper_Plugin $objHelperPlugin
     * @param int $priority
     */
    public function register(SC_Helper_Plugin $objHelperPlugin, $priority)
    {
        parent::register($objHelperPlugin, $priority);

        $objHelperPlugin->addAction('LC_Page_Admin_System_Log_Ex_action_before', array($this, 'LC_Page_Admin_System_Log_action_before'), $priority);
        $objHelperPlugin->addAction('prefilterTransform', array($this, 'prefilterTransform'), $priority);
    }

    public function LC_Page_Admin_System_Log_action_before(LC_Page_Admin_System_Log $objPage)
    {
        $objPage = new plg_CloudWatchLogs_LC_Page_Admin_System_Log();
        $objPage->init();
        $objPage->process();
        exit();
    }

    /**
     * プレフィルタコールバック関数
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    public function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename)
    {
        if (GC_Utils_Ex::isAdminFunction()) {
            $deviceType = DEVICE_TYPE_ADMIN;
        } else {
            $deviceType = SC_Display_Ex::detectDevice();
        }

        if ($deviceType === DEVICE_TYPE_ADMIN) {
            if (strpos($filename, 'system/log.tpl') !== false) {
                $source = str_replace('<table class="list log">', '<table class="list log">' . "\n" . '<col width="15%"><col width="25%"><col width="60%">', $source);
            }
        }
    }
}
