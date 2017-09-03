<?php

namespace Aniart\Seo;

use \Bitrix\Highloadblock\HighloadBlockTable;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Config\Option;

class CreateHBTable
{

    public static $highloadTableName = 'seotable';
    private $arFields;


    public function __construct()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new SystemException("Highloadblock module don't install");
        }
        $this->arFields = array(
            "SORT" => array("NAME" => "UF_SORT", "TYPE" => "string"),
            "PAGE" => array("NAME" => "UF_PAGE", "TYPE" => "string"),
            "PAGE_TITLE" => array("NAME" => "UF_PAGE_TITLE", "TYPE" => "string"),
            "META_TITLE" => array("NAME" => "UF_META_TITLE", "TYPE" => "string"),
            "KEYWORDS" => array("NAME" => "UF_KEYWORDS", "TYPE" => "string"),
            "DESCRIPTION" => array("NAME" => "UF_DESCRIPTION", "TYPE" => "string"),
        );
    }

    public function createTable()
    {
        $hlblockID = self::getHBID();
        if (!$hlblockID) {

            $result = HighloadBlockTable::add(array(
                'NAME' => 'SeoTableData',
                'TABLE_NAME' => self::$highloadTableName
            ));
            if (!$result->isSuccess()) {
                $message = $result->getErrorMessages();
                throw new SystemException(implode("\n",$message));
            }
            Option::set("aniart.seo", "hl_block_id",$hlblockID);
            $this->createUserFields();

        }


    }

    public function deleteTable(){

       return HighloadBlockTable::delete (self::getHBID());
    }

    public static function getHBID(){
        $hlblock = HighloadBlockTable::getList(
            array("filter" => array(
                'TABLE_NAME' => self::$highloadTableName
            ),
                'select' => array("ID")
            )
        )->fetch();
        return $hlblock['ID'];
    }

    private function createUserFields()
    {
        $oUserTypeEntity = new \CUserTypeEntity();
        foreach ($this->arFields as $fkey => $field){

            $aUserFields = array(

                'ENTITY_ID' => "HLBLOCK_" . self::getHBID(),
                'FIELD_NAME' => 'UF_' . mb_strtoupper($fkey),
                'USER_TYPE_ID' => $field["TYPE"],
                'XML_ID' => 'UF_'. mb_strtoupper($fkey),
                'SORT' => 200,
                'MULTIPLE' => 'N',
                'MANDATORY' => 'N',
                'EDIT_IN_LIST'      => '',
                'IS_SEARCHABLE'     => 'N',

                'SETTINGS'          => array(
                    'DEFAULT_VALUE' => '',
                    'SIZE'          => '20',
                    'ROWS'          => '1',
                    'MIN_LENGTH'    => '0',
                    'MAX_LENGTH'    => '0',
                    'REGEXP'        => '',
                ),

                'EDIT_FORM_LABEL'   => array(
                    'ru'    => $field['NAME'],
                    'en'    => $field['NAME'],
                ),

                'LIST_COLUMN_LABEL' => array(
                    'ru'    => $field['NAME'],
                    'en'    => $field['NAME'],
                ),

                'LIST_FILTER_LABEL' => array(
                    'ru'    => $field['NAME'],
                    'en'    => $field['NAME'],
                ),

                'ERROR_MESSAGE'     => array(
                    'ru'    => 'Ошибка при заполнении пользовательского свойства',
                    'en'    => 'An error in completing the user field',
                ),

            );

            $iUserFieldId   = $oUserTypeEntity->Add($aUserFields);

        }
    }
}