<?php
use Bitrix\Highloadblock\HighloadBlockTable;
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
        try {
            $this->installDB();
            $this->registerEvents();
            RegisterModule($this->MODULE_ID);
        } catch (Exception $e) {
            $APPLICATION->ThrowException();
            return false;
        }

        return true;
    }

    public function DoUninstall()
    {
        Bitrix\Main\Loader::includeModule('highloadblock');
        global $APPLICATION;
        $this->unregisterEvents();
        $this->uninstallDB();
        UnRegisterModule($this->MODULE_ID);
    }

    private function registerEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler('main', 'OnEpilog', 'aniart.seo', '\Aniart\Seo\Observers\BitrixObserver', 'onEpilog');

    }

    private function unregisterEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'OnEpilog', 'aniart.seo', '\Aniart\Seo\Observers\BitrixObserver', 'onEpilog');

    }

    public function installDB()
    {
        global $APPLICATION;
        $hlID = $this->createHLBlock();
        COption::SetOptionInt($this->MODULE_ID, 'seo_hlblock_id', $hlID);
    }

    public function uninstallDB()
    {

        $hlID = COption::GetOptionInt($this->MODULE_ID, 'seo_hlblock_id');
        HighloadBlockTable::delete($hlID);
        COption::RemoveOption($this->MODULE_ID);
    }

    private function createHLBlock()
    {
        $params = ['NAME' => 'SeoParams', 'TABLE_NAME' => 'seo_params'];
        $result = HighloadBlockTable::add($params);

        if (!$result->isSuccess()) {
            throw new Exception();
        }

        $userTypeEntityInstance = new CUserTypeEntity();
        foreach ($this->getHLFieldsParams($result->getId()) as $fieldParams) {
            if (!$userTypeEntityInstance->Add($fieldParams)) {
                throw new Exception();
            }
        }
        return $result->getId();
    }

    private function getHLFieldsParams($hlID)
    {
        $entityID = 'HLBLOCK_' . $hlID;
        return [
            [
                'ENTITY_ID' => $entityID,
                'FIELD_NAME' => 'UF_PAGE',
                'USER_TYPE_ID' => 'string'
            ],
            [
                'ENTITY_ID' => $entityID,
                'FIELD_NAME' => 'UF_SORT',
                'USER_TYPE_ID' => 'string'
            ],
            [
                'ENTITY_ID' => $entityID,
                'FIELD_NAME' => 'UF_PAGE_TITLE',
                'USER_TYPE_ID' => 'string'
            ],
            [
                'ENTITY_ID' => $entityID,
                'FIELD_NAME' => 'UF_META_TITLE',
                'USER_TYPE_ID' => 'string'
            ],
            [
                'ENTITY_ID' => $entityID,
                'FIELD_NAME' => 'UF_KEYWORDS',
                'USER_TYPE_ID' => 'string'
            ],
            [
                'ENTITY_ID' => $entityID,
                'FIELD_NAME' => 'UF_DESCRIPTION',
                'USER_TYPE_ID' => 'string'
            ],

        ];
    }
}

?>