<?
use Bitrix\Main\Loader,
    Bitrix\Catalog,
    Bitrix\Currency;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */
/** @global CUserTypeManager $USER_FIELD_MANAGER */
if (!Loader::includeModule('catalog'))
    return;

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$iblockFilter = (
!empty($arCurrentValues['IBLOCK_TYPE'])
    ? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
    : array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
    $arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
unset($arr, $rsIBlock, $iblockFilter);


$arAscDesc = array(
    "asc" => GetMessage("CVP_SORT_ASC"),
    "desc" => GetMessage("CVP_SORT_DESC"),
);

$showFromSection = isset($arCurrentValues['SHOW_FROM_SECTION']) && $arCurrentValues['SHOW_FROM_SECTION'] == 'Y';

$arComponentParameters = array(
    "GROUPS" => array(
        "PRICES" => array(
            "NAME" => GetMessage("CVP_PRICES"),
        ),
        "BASKET" => array(
            "NAME" => GetMessage("CVP_BASKET"),
        ),
    ),
    "PARAMETERS" => array(
        "DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
            "DETAIL",
            "DETAIL_URL",
            GetMessage("CVP_DETAIL_URL"),
            "",
            "URL_TEMPLATES"
        ),
         "SHOW_NAME" => array(
            "PARENT" => "VISUAL",
            "NAME" => GetMessage("CVP_SHOW_NAME"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        "SHOW_IMAGE" => array(
            "PARENT" => "VISUAL",
            "NAME" => GetMessage("CVP_SHOW_IMAGE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        'MESS_BTN_BUY' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('CVP_MESS_BTN_BUY'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('CVP_MESS_BTN_BUY_DEFAULT')
        ),
        'MESS_BTN_DETAIL' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('CVP_MESS_BTN_DETAIL'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('CVP_MESS_BTN_DETAIL_DEFAULT')
        ),
        "PAGE_ELEMENT_COUNT" => array(
            "PARENT" => "VISUAL",
            "NAME" => GetMessage("CVP_PAGE_ELEMENT_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "5",
        ),
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CVP_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CVP_IBLOCK"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
        ),

        "CACHE_TIME" => array("DEFAULT"=>36000000),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CVP_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
    ),
);
