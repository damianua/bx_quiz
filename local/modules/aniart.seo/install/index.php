<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

Loader::includeModule("highloadblock");
Loc::loadMessages(__FILE__);




class aniart_seo extends CModule
{
    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '1.0';
    public $MODULE_VERSION_DATE = '2019-06-03';
    public $MODULE_NAME = 'SEO модуль Aniart';
    public $MODULE_DESCRIPTION = 'Служит для замены ТКДЗ. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniaArt";
    public $PARTNER_URI = "http://aniart.com.ua";


    function InstallTable()
    {
        $tableName = 'seoaniart';
        $data = array(
            'NAME' => str_replace('_','',trim("H".$tableName)),
            'TABLE_NAME' => trim($tableName)
        );
        $result = HL\HighloadBlockTable::add($data);
        if ($result->isSuccess())
        {
            $tableID = $result->getId();
            $oUserTypeEntity    = new CUserTypeEntity();

            $arField = array("UF_PAGE", "UF_PAGE_TITLE", "UF_SORT", "UF_META_TITLE", "UF_KEYWORDS", "UF_DESCRIPTION");
            foreach ($arField as $tField) {
                $aUserFields = array(
                    'ENTITY_ID'         => 'HLBLOCK_'.$tableID,
                    'FIELD_NAME'        => $tField,
                    'USER_TYPE_ID'      => 'string',
                    'MULTIPLE'          => 'N',
                    'MANDATORY'         => 'N',
                    'SHOW_FILTER'       => 'N',
                    'EDIT_FORM_LABEL'   => array(
                        'ru'    => '',
                        'en'    => '',
                    )
                );
                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
            }
        }
        COption::SetOptionString($this->MODULE_ID, "table_id",  $tableID);
    }

    function UnInstallTable()
    {
        $tableID = COption::GetOptionString($this->MODULE_ID, "table_id");
        $result = HL\HighloadBlockTable::delete($tableID);
    }

    function InstallEvents()
    {
       \Bitrix\Main\EventManager::getInstance()->registerEventHandler('main', 'OnEpilog', $this->MODULE_ID, '\Aniart\Seo\Observers\BitrixObserver', 'onEpilog');
    }

    function UnInstallEvents()
    {
       \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler('main', 'OnEpilog', $this->MODULE_ID, '\Aniart\Seo\Observers\BitrixObserver', 'onEpilog');
    }

    function DoInstall()
    {
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);
        $this->InstallTable();
        //COption::SetOptionString($this->MODULE_ID, "table_id", $tableID);

        $this->InstallEvents();

    }
    function DoUninstall()
    {
        global $APPLICATION;
        UnRegisterModule($this->MODULE_ID);
        $this->UnInstallEvents();
        $this->UnInstallTable();
    }
}
?>