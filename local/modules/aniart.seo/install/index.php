<?php

/**
 * Модуль может быть установлен только при наличии установленного базового модуля Aniart MAIN
 */
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
if (class_exists('aniart_seo')) {
    return;
}
class aniart_seo extends CModule
{
    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '0.9';
    public $MODULE_VERSION_DATE = '2019-19-01';
    public $MODULE_NAME = 'Базовый модуль Aniart SEO';
    public $MODULE_DESCRIPTION = 'Служит для подключения SEO-функциональности. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniaArt";
    public $PARTNER_URI = "http://aniart.com.ua";

    public function DoInstall()
    {
        global $APPLICATION;

        if (Bitrix\Main\Loader::includeModule('aniart.main')) {
            RegisterModule($this->MODULE_ID);
        } else {
            $APPLICATION->ThrowException("Модуль Aniart SEO требует наличия установленного модуля Aniart MAIN");
        }

        $APPLICATION->IncludeAdminFile("Установка модуля {$this->MODULE_NAME}", dirname(__FILE__) . "/step.php");
    }
    public function DoUninstall()
    {
        global $APPLICATION;
        UnRegisterModule($this->MODULE_ID);
    }
}
?>