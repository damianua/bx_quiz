<?php
$modulePath = dirname(__FILE__);
include dirname(__FILE__) . '/../vars.php';
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Localization\Loc;
use Aniart\Main\Exceptions;

Loc::loadMessages(__FILE__);
if (class_exists('aniart_seo')) {
    return;
}

class aniart_seo extends CModule
{
    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '1.0';
    public $MODULE_VERSION_DATE = '2019-11-9';
    public $MODULE_NAME = 'Seo модуль Aniart';
    public $MODULE_DESCRIPTION = 'Тестовое задание';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniArt";
    public $PARTNER_URI = "http://aniart.com.ua";

    public function DoInstall()
    {
        global $APPLICATION;

        $this->InstallSeoTable();

        RegisterModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->DeleteSeoTable($this->CheckSeoTable());

        UnRegisterModule($this->MODULE_ID);
    }

    public function CheckSeoTable()
    {
        $rsData = HighloadBlockTable::getList(array('filter' => array('NAME' => SEO_HL_NAME)));
        if (!($arData = $rsData->fetch())) {
            return false;
        } else {
            return $arData['ID'];
        }
    }

    private function AddSeoTable()
    {
        $result = HighloadBlockTable::add(array(
            'NAME' => SEO_HL_NAME,
            'TABLE_NAME' => strtolower(SEO_HL_NAME),
        ));
        if (!$result->isSuccess())
            throw new Exception(implode($result->getErrorMessages()));
        else
            return $result->getId();
    }

    public function DeleteSeoTable($id)
    {
        HighloadBlockTable::delete($id);
    }

    public function InstallSeoTable()
    {
        if (!Bitrix\Main\Loader::includeModule('aniart.main'))
            throw new Exception('Не установлен модуль Aniart.Main');

        if (!$this->CheckSeoTable()) {
            $id = $this->AddSeoTable();
            $seoFileds = array(
                ['FIELD_NAME' => 'UF_PAGE'],
                ['FIELD_NAME' => 'UF_PAGE_TITLE'],
                ['FIELD_NAME' => 'UF_SORT'],
                ['FIELD_NAME' => 'UF_META_TITLE'],
                ['FIELD_NAME' => 'UF_KEYWORDS'],
                ['FIELD_NAME' => 'UF_DESCRIPTION']
            );
            $this->AddSeoFields($id, $seoFileds);
        } else
            throw new Exception('HL-блок уже существует');
    }

    private function AddSeoFields($id, $fields)
    {
        $userTypeEntity = new \CUserTypeEntity();
        foreach ($fields as $field) {
            $field['ENTITY_ID'] = 'HLBLOCK_' . $id;
            $field['USER_TYPE_ID'] = 'string';
            $field['MULTIPLE'] = "N";
            $userTypeEntity->Add($field);
        }

    }
}

?>