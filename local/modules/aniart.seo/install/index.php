<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
if (class_exists('aniart_seo')) {
    return;
}

class aniart_seo extends CModule
{
    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '0.9';
    public $MODULE_VERSION_DATE = '2017-01-09';
    public $MODULE_NAME = 'Модуль SEO Aniart';
    public $MODULE_DESCRIPTION = 'Модуль SEO Aniar. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniaArt";
    public $PARTNER_URI = "http://aniart.com.ua";


    function InstallDB()
    {
        $PATH = dirname(__DIR__)."/lib/createhtable.php";
        echo $PATH;
        if (file_exists($PATH) )
        {
            require_once $PATH;
            AddMessage2Log("InstallDb");
            $objHBTable= new \Aniart\Seo\CreateHBTable();
            $objHBTable->createTable();
        }
        return true;
    }

    function UnInstallDB() {
        $PATH = dirname(__DIR__)."/lib/createhtable.php";
        if (file_exists($PATH) )
        {
            require_once $PATH;
            $objHBTable = new \Aniart\Seo\CreateHBTable();

            $objHBTable->deleteTable();
            \Bitrix\Main\Config\Option::delete("aniart.seo", array("name" => "hl_block_id"));
        }
        return true;
    }

    public function DoInstall()
    {
        global $APPLICATION;
        $this->InstallDB();
        $this->InstallEvents();
        RegisterModule($this->MODULE_ID);
    }
    public function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallDB();
        $this->UnInstallEvents();
        UnRegisterModule($this->MODULE_ID);
    }
    function InstallEvents() {
        $eventManager = \Bitrix\Main\EventManager::getInstance();

        $eventManager->registerEventHandler('main', 'OnEpilog', $this->MODULE_ID, '\Aniart\Main\Observers\BitrixObserver', 'onEpilog');
    }
    function UnInstallEvents() {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'OnEpilog', $this->MODULE_ID, '\Aniart\Main\Observers\BitrixObserver', 'onEpilog');
    }
}