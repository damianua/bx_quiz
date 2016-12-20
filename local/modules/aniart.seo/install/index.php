<?php
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
if (class_exists('aniart_seo')) {
    return;
}
class aniart_seo extends CModule
{
    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '0.1';
    public $MODULE_VERSION_DATE = '2016-12-20';
    public $MODULE_NAME = 'Модуль soe Aniart';
    public $MODULE_DESCRIPTION = 'Служит для управления seo параметрами. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniaArt";
    public $PARTNER_URI = "http://aniart.com.ua";

    public function DoInstall()
    {
        global $APPLICATION;
        $this->registerEvents();
        RegisterModule($this->MODULE_ID);
    }
    public function DoUninstall()
    {
        global $APPLICATION;
        $this->unregisterEvents();
        UnRegisterModule($this->MODULE_ID);
    }

    private function registerEvents() {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler('main', 'OnEpilog', 'aniart.seo', '\Aniart\Seo\Observers\BitrixObserver', 'onEpilog');

    }

    private function unregisterEvents() {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'OnEpilog', 'aniart.seo', '\Aniart\Seo\Observers\BitrixObserver', 'onEpilog');

    }
}
?>