<?php

namespace Aniart\Seo;

use \Bitrix\Highloadblock\HighloadBlockTable;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Config\Option;

/**
 * HBProvider - клас инкапсулирующий работу с Хайлоад блоком
*/
class HBProvider {

    private $__highloadName;
    private $__highloadTableName;
    private $__highloadBlockId;
    private $__arFieldsDefination;
    
    private $__pageTitle;
    private $__metaTitle;
    private $__keywords;
    private $__description;
    
    /**
     * @throws SystemException
     */
    public function __construct() 
    {
        if ( !Loader::includeModule('highloadblock') ) {
            throw new SystemException("Module \"highloadblock\" don't loaded");
        }
        
        $this->__arFieldsDefination =  [
            "SORT" => ["NAME" => "UF_SORT", "TYPE" => "string"],
            "PAGE" => ["NAME" => "UF_PAGE", "TYPE" => "string"],
            "PAGE_TITLE" => ["NAME" => "UF_PAGE_TITLE", "TYPE" => "string"],
            "META_TITLE" => ["NAME" => "UF_META_TITLE", "TYPE" => "string"],
            "KEYWORDS" => ["NAME" => "UF_KEYWORDS", "TYPE" => "string"],
            "DESCRIPTION" => ["NAME" => "UF_DESCRIPTION", "TYPE" => "string"],
        ];
                
    }

    public function Init($page=false) 
    {
        $this->__highloadName = "Seodata";
        $this->__highloadTableName = "seodata";
        $this->__highloadBlockId = 0;

        $arTableData = [
            'NAME' => $this->__highloadName,
            'TABLE_NAME' => $this->__highloadTableName
        ];
        /* Проверка наличия Highload блока */

        $dbHlblocks = HighloadBlockTable::getList([
            "filter" => $arTableData
        ]);
        if ($arrHlblock = $dbHlblocks->Fetch()) {
            $this->__highloadBlockId = $arrHlblock["ID"];
        } else {
            /* Создание Highload блока */
            $result = HighloadBlockTable::add($arTableData);
            if (!$result->isSuccess()) 
            {
                $errors = $result->getErrorMessages();
                if (is_array($errors)) 
                {
                    $msg .= implode("\n", $errors);
                } 
                else 
                {
                    $msg .= $errors;
                }
                throw new SystemException($msg);
            } 
            else 
            {
                $this->__highloadBlockId = $result->getId();
                
                \Bitrix\Main\Config\Option::set("aniart.seo", "hl_block_id", $this->__highloadBlockId);        
            }
        }
        

        /* Создание полей Highload блока */
        $userTypeEntity = new \CUserTypeEntity();
        foreach ( $this->__arFieldsDefination as $ufId => $ufDefination ) 
        {
            $userTypeData = [
                'ENTITY_ID' => "HLBLOCK_" . $this->__highloadBlockId,
                'FIELD_NAME' => 'UF_' . mb_strtoupper($ufId),
                'USER_TYPE_ID' => $ufDefination["TYPE"],
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => $ufDefination["NAME"],
                    'en' => $ufDefination["NAME"],
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => $ufDefination["NAME"],
                    'en' => $ufDefination["NAME"],
                ],
                'SETTINGS' => [],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => $ufDefination["NAME"],
                    'en' => $ufDefination["NAME"],
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ошибка при заполнении пользовательского свойства "' . $ufDefination["NAME"] . '"',
                    'en' => 'An error in completing the user field "' . $ufDefination["NAME"] . '"',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => '',
                    'en' => '',
                ]
            ];
            $userTypeId = $userTypeEntity->Add ( $userTypeData );
        }
        if($page){
            $this->ReadDataForPage($page);
        }
    }
    
    public function Delete(){
        
        return HighloadBlockTable::delete ( $this->__highloadBlockId );
    }
    
    /**
     * 
     * @param type $arFilter - масив, где ключ элемента - это название поля для фильтрации, 
     *                                    значние - значение для фильтрации
     * @param type $arOrder
     * @param type $arSelect
     * @return type
     */
    public function GetList ( $arFilter=[], $arOrder=["ID"=>"ASC"], $arSelect=["*"] )
    {
        $hlblock = HighloadBlockTable::getById($this->__highloadBlockId)->fetch(); 
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_class = $entity->getDataClass();    
        $rsData = $entity_class::getList(
            [
                "filter" => $arFilter,
                'select' => $arSelect,
                'order'  => $arOrder
            ]
        );
        $arResNormalized = array();
        while ( $arRes = $rsData->Fetch() )
        {
            $arResOriginal[] = $arRes;
        }
        foreach ( $arResOriginal as $arrItem ) {
            $arItemNorm = array();
            foreach ( $arrItem as $key=>$val ) {
                $arItemNorm[preg_replace("|UF_|", "\\1", $key)] = $val;
            }
            $arResNormalized[] = $arItemNorm;
        }
        return $arResNormalized;
    }
    
    /**
     * @param type $page - относительный путь страницы
     * @return type
     */
    public function ReadDataForPage( $page ) 
    {
        
        $arrRes = $this->GetList(
            $filter     = ["UF_PAGE"=>$page],
            $arOrder    = ["ID"=>"ASC"], 
            $arSelect   = ["*"]
        );
        print_r($arrRes);
        foreach ( $arrRes as $item )
        {
            $this->__pageTitle = $item["PAGE_TITLE"];
            $this->__metaTitle = $item["META_TITLE"];
            $this->__keywords  = $item["KEYWORDS"];
            $this->__description = $item["DESCRIPTION"];
        }
    }
    
    /**
     * 
     * @return type string
    */
    public function GetPageTitle()
    {
        return $this->__pageTitle?$this->__pageTitle:"";
    }   
    
    /**
     * 
     * @return type string
    */
    public function GetMetaTitle()
    {
        return $this->__metaTitle?$this->__metaTitle:"";
    }   
    
    /**
     * 
     * @return type string
    */
    public function GetKeywords()
    {
        return $this->__keywords?$this->__keywords:"";
    }
    
    /**
     * 
     * @return type string
    */
    public function GetDescription()
    {
        return $this->__description?$this->__description:"";
    }    
}