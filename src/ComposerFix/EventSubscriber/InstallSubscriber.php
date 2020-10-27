<?php

namespace ComposerFix\EventSubscriber;

use Eccube2\Util\ParameterUtil;
use Eccube2\Util\PluginUtil;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InstallSubscriber implements EventSubscriberInterface
{
    private $parameters = array(
        'ZIP_DOWNLOAD_URL' => '"https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip"',
        'MODULE_DIR' => '"module/"' ,
        'MODULE_REALDIR' => 'ROOT_REALDIR . MODULE_DIR',
        'MASTER_DATA_REALDIR' => 'ROOT_REALDIR . "var/cache/master/"',
        'PLUGIN_UPLOAD_REALDIR' => 'ROOT_REALDIR . "plugin/"',
        'DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR' => 'ROOT_REALDIR . "var/temp/plugin_update/"',
        'DOWNLOADS_TEMP_PLUGIN_INSTALL_DIR' => 'ROOT_REALDIR . "var/temp/plugin_install/"',
        'LOG_REALFILE' => 'LOG_REALDIR . "site.log"',
        'CUSTOMER_LOG_REALFILE' => 'LOG_REALDIR . "customer.log"',
        'ADMIN_LOG_REALFILE' => 'LOG_REALDIR . "admin.log"',
        'ERROR_LOG_REALFILE' => 'LOG_REALDIR . "error.log"',
        'DB_LOG_REALFILE' => 'LOG_REALDIR . "db.log"',
        'PLUGIN_LOG_REALFILE' => 'LOG_REALDIR . "plugin.log"',
        'OSTORE_LOG_REALFILE' => 'LOG_REALDIR . "ownersstore.log"',
        'CSV_TEMP_REALDIR' => 'ROOT_REALDIR . "temp/csv/"',
        'SMARTY_TEMPLATES_REALDIR' => 'ROOT_REALDIR . "templates/"',
        'COMPILE_REALDIR' => 'ROOT_REALDIR . "var/cache/smarty/" . TEMPLATE_NAME . "/"',
        'COMPILE_ADMIN_REALDIR' => 'ROOT_REALDIR . "var/cache/smarty/admin/"',
        'MOBILE_COMPILE_REALDIR' => 'ROOT_REALDIR . "var/cache/smarty/" . MOBILE_TEMPLATE_NAME . "/"',
        'SMARTPHONE_COMPILE_REALDIR' => 'ROOT_REALDIR . "var/cache/smarty/" . SMARTPHONE_TEMPLATE_NAME . "/"',
        'DOWN_TEMP_REALDIR' => 'ROOT_REALDIR . "var/temp/download/"',
        'DOWN_SAVE_REALDIR'=> 'ROOT_REALDIR . "var/download/"',
    );

    public static function getSubscribedEvents()
    {
        return [
            'install.insert_data' => 'onInsertData',
            'install.after' => 'onAfter',
        ];
    }

    public function onInsertData(Event $event)
    {
        $parameterUtil = new ParameterUtil();
        foreach ($this->parameters as $key => $value) {
            $parameterUtil->set($key, $value, false);
        }
    }

    public function onAfter(Event $event)
    {
        $pluginUtil = new PluginUtil();
        $pluginUtil->install('ComposerFix');
        $pluginUtil->enable('ComposerFix');
    }
}
