<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * プラグインのメインクラス
 */
class ComposerFix extends SC_Plugin_Base
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

        $objHelperPlugin->addAction('SC_FormParam_construct', array($this, 'SC_FormParam_construct'), $priority);
        $objHelperPlugin->addAction('LC_Page_Admin_System_System_action_before', array($this, 'LC_Page_Admin_System_System_action_before'), $priority);
        $objHelperPlugin->addAction('prefilterTransform', array($this, 'prefilterTransform'), $priority);
    }

    public function SC_FormParam_construct($class_name, SC_FormParam $objFormParam)
    {
        $arrBacktrace = debug_backtrace();
        foreach ($arrBacktrace as $backtrace) {
            if (is_object($backtrace['object']) && $backtrace['object'] instanceof LC_Page) {
                $objPage = $backtrace['object'];
                if ($objPage instanceof LC_Page_Admin_Basis_ZipInstall && defined('ZIP_TEMP_REALDIR')) {
                    $objPage->zip_csv_temp_realfile = ZIP_TEMP_REALDIR . 'ken_all.zip';
                } elseif ($objPage instanceof LC_Page_Admin_System_Bkup && defined('BACKUP_REALDIR')) {
                    $objPage->bkup_dir = BACKUP_REALDIR;
                }
                break;
            }
        }
    }

    public function LC_Page_Admin_System_System_action_before(LC_Page_Admin_System_System $objPage)
    {
        if ($objPage->getMode() === 'info') {
            exit;
        }
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
        if (defined('ADMIN_FUNCTION') && ADMIN_FUNCTION) {
            $deviceType = DEVICE_TYPE_ADMIN;
        } else {
            $deviceType = SC_Display_Ex::detectDevice();
        }

        if ($deviceType === DEVICE_TYPE_ADMIN) {
            if (strpos($filename, 'system/system.tpl') !== false) {
                $source = preg_replace('|<h2>PHP情報</h2>\n<iframe src="\?mode=info.*?</iframe>|', '', $source);
            }
        }
    }
}
