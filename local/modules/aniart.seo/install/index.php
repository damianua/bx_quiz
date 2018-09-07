<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock\HighloadBlockTable;

Loc::loadMessages(__FILE__);
if (class_exists('aniart_main')) {
    return;
}
use Bitrix\Main\ModuleManager;

class aniart_seo extends CModule
{
    const NAME = 'SeoPages';
    const TABLE_NAME = 'seo_pages';

    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '0.1';
    public $MODULE_VERSION_DATE = '2018-09-04';
    public $MODULE_NAME = 'Seo-модуль Aniart';
    public $MODULE_DESCRIPTION = 'Seo-модуль тестовый. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniaArt";
    public $PARTNER_URI = "http://aniart.com.ua";

    protected $params;

    public function __construct()
    {
        $this->params = array();
        include $this->getPath() . 'vars.php';
    }

    protected function getPath()
    {
        return realpath(dirname(__FILE__) . '/../') . '/';
    }

    protected function writeVars()
    {
        $text = "<?\n";
        if (isset($this->params['seo_pages_id'])) {
            $text .= "define('HL_SEO_PAGES_ID', " . (int) $this->params['seo_pages_id'] . ");\n ";
        }
        file_put_contents($this->getPath() . 'vars.php', $text);
    }

    protected function removeHighloadTable()
    {
        HighloadBlockTable::delete(HL_SEO_PAGES_ID);
        $this->params['seo_pages_id'] = 0;
        $this->writeVars();
    }

    protected function createHighloadTable()
    {
        $oldHlBlock = HighloadBlockTable::getList([
            'filter' => ['TABLE_NAME' => self::TABLE_NAME],
            'select' => ['ID'],
            'limit' => 1,
        ])->fetch();
        if ($oldHlBlock !== false) {
            return false;
        }
        $newHlBlock = HighloadBlockTable::add([
            'TABLE_NAME' => self::TABLE_NAME,
            'NAME' =>self::NAME
        ]);
        if (!$newHlBlock->isSuccess()) {
            throw new \Exception($newHlBlock->getErrorMessages());
        }
        $this->params['seo_pages_id'] = $newHlBlock->getId();
        $arPropsList = ['UF_PAGE', 'UF_PAGE_TITLE', 'UF_SORT', 'UF_META_TITLE', 'UF_KEYWORDS', 'UF_DESCRIPTION'];
        $arFields = array(
            "ENTITY_ID" => 'HLBLOCK_' . $newHlBlock->getId(),
            "FIELD_NAME" => '',
            "USER_TYPE_ID" => 'string',
            "MULTIPLE" => 'N',
        );
        $obUserField = new \CUserTypeEntity;
        foreach ($arPropsList as $code) {
            $arFields['FIELD_NAME'] = $code;
            $ID = $obUserField->Add($arFields);
            if ($ID <= 0) {
                throw new \Exception('CUserTypeEntity::Add error, highloadblock id = ' .$newHlBlock->getId());
            }
        }

        $this->writeVars();

    }

    public function DoInstall()
    {
        global $APPLICATION;
        $this->createHighloadTable();
        ModuleManager::RegisterModule($this->MODULE_ID);
    }
    public function DoUninstall()
    {
        global $APPLICATION;
        $this->removeHighloadTable();
        ModuleManager::unRegisterModule($this->MODULE_ID);

    }
}
?>
