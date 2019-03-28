<?php
$modulePath = dirname(__FILE__);
include $modulePath.'/hl.php';
use HLCreate;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
if (class_exists('aniart_seo')) {
    return;
}
class aniart_seo extends CModule
{
    //use HLCreate;

    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '0.1';
    public $MODULE_VERSION_DATE = '2019-03-27';
    public $MODULE_NAME = 'SEO модуль';
    public $MODULE_DESCRIPTION = 'Служит для установки seo. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "test";
    public $PARTNER_URI = "http://#";

    public function DoInstall()
    {
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);
        $HL = new HLCreate();
        $hl_id=$HL->createHB();
        Bitrix\Main\Config\Option::set("aniart.seo", "HL_SEO_PAGES_ID", $hl_id);

    }
    public function DoUninstall()
    {
        global $APPLICATION;
        UnRegisterModule($this->MODULE_ID);
    }
}
?>