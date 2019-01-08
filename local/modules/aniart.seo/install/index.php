<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);
if (class_exists('aniart_seo')) {
    return;
}
class aniart_seo extends CModule
{
    public $MODULE_ID = 'aniart.seo';
    public $MODULE_VERSION = '0.1';
    public $MODULE_VERSION_DATE = '2018-10-09';
    public $MODULE_NAME = 'Вывод SEO мета данных Aniart';
    public $MODULE_DESCRIPTION = 'Служит для установки и вывода SEO мета данных. Использует движок D7.';
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME = "AniaArt";
    public $PARTNER_URI = "http://aniart.com.ua";

    public $HL_NAME = "SeoPagesId";

    public function DoInstall()
    {
        global $APPLICATION;

        if (!IsModuleInstalled($this->MODULE_ID))
        {
            $this->InstallDB();
            //$this->InstallEvents();

            echo CAdminMessage::ShowNote("Модуль ".$this->MODULE_ID." установлен");

        }

    }
    public function DoUninstall()
    {
        global $APPLICATION;
        if (IsModuleInstalled($this->MODULE_ID))
        {
            $this->UnInstallDB();
            //$this->UnInstallEvents();

            echo CAdminMessage::ShowNote("Модуль успешно удален из системы");
        }

    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallDB($arParams = Array())
    {
        global $errors;
        $arTableProp = array(
            'UF_PAGE' => array('Y', 'string', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Урл',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Урл',
                ),
            )),
            'UF_PAGE_TITLE' => array('Y', 'string', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Тайтл страницы',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Тайтл страницы',
                ),
            )),
            'UF_SORT' => array('Y', 'integer', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Сортировка',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Сортировка',
                ),
                'SETTINGS' => array(
                    'DEFAULT_VALUE' => 500,
                ),
            )),
            'UF_META_TITLE' => array('Y', 'string', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Мета данные страницы - тайтл',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Мета данные страницы - тайтл',
                ),
            )),
            'UF_KEYWORDS' => array('Y', 'string', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Мета данные страницы - кейвордсы',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Мета данные страницы - кейвордсы',
                ),
            )),
            'UF_DESCRIPTION' => array('Y', 'string', array(
                'EDIT_FORM_LABEL' => array(
                    'ru' => 'Мета данные страницы - описание',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru' => 'Мета данные страницы - описание',
                ),
            )),
        );
        if (Loader::includeModule("aniart.main")){
            if (Loader::includeModule("highloadblock")){

                $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('NAME'=>$this->HL_NAME)));
                if ( !($arData = $rsData->fetch()) ){
                    $result = Bitrix\Highloadblock\HighloadBlockTable::add(array(
                        'NAME' => $this->HL_NAME,//должно начинаться с заглавной буквы и состоять только из латинских букв и цифр
                        'TABLE_NAME' => 'seopageid',//должно состоять только из строчных латинских букв, цифр и знака подчеркивания
                    ));
                    if (!$result->isSuccess()) {
                        $errors = $result->getErrorMessages();
                    } else {
                        $highBlockID = $result->getId();
                    }
                }else{
                    $highBlockID = $arData['ID'];
                }

                $oUserTypeEntity = new CUserTypeEntity();

                $sort = 500;

                foreach ($arTableProp as $fieldName => $fieldValue) {
                    $aUserField = array(
                        'ENTITY_ID' => 'HLBLOCK_' . $highBlockID,
                        'FIELD_NAME' => $fieldName,
                        'USER_TYPE_ID' => $fieldValue[1],
                        'SORT' => $sort,
                        'MULTIPLE' => 'N',
                        'MANDATORY' => $fieldValue[0],
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'Y',
                        'EDIT_IN_LIST' => 'Y',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(),
                    );

                    if (isset($fieldValue[2]) && is_array($fieldValue[2])) {
                        $aUserField = array_merge($aUserField, $fieldValue[2]);
                    }

                    $resProperty = CUserTypeEntity::GetList(
                        array(),
                        array('ENTITY_ID' => $aUserField['ENTITY_ID'], 'FIELD_NAME' => $aUserField['FIELD_NAME'])
                    );

                    if ($aUserHasField = $resProperty->Fetch()) {
                        $idUserTypeProp = $aUserHasField['ID'];
                        $oUserTypeEntity->Update($idUserTypeProp, $aUserField);
                    } else {
                        $oUserTypeEntity->Add($aUserField);
                    }

                    $sort += 100;
                }

                RegisterModule($this->MODULE_ID);


                $eventManager = EventManager::getInstance();

                $eventManager->addEventHandler('main', 'OnEpilog', ['\Aniart\Seo\Observers\BitrixObserver', 'onEpilog']);

                return true;
            }else{
                echo CAdminMessage::ShowNote("Модуль highloadblock не установлен в системе");

                return false;
            }
        }else{
            echo CAdminMessage::ShowNote("Модуль aniart.main не установлен в системе");

            return false;
        }

    }

    function UnInstallDB($arParams = Array())
    {
        if (Loader::includeModule("highloadblock")){
            $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('NAME'=>$this->HL_NAME)));
            if ( ($arData = $rsData->fetch()) ){
                Bitrix\Highloadblock\HighloadBlockTable::delete($arData['ID']);
            }
        }else{
            echo CAdminMessage::ShowNote("Модуль highloadblock не установлен в системе");

            return false;
        }

        UnRegisterModule($this->MODULE_ID);

        return true;
    }
}
?>