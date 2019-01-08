<?php
use Bitrix\Main\Loader;

if (Loader::includeModule("highloadblock")){
    $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('NAME'=>'SeoPagesId')));
    if ( ($arData = $rsData->fetch()) ){
        define('HL_SEO_PAGES_ID', $arData['ID']);
    }
}else{
    echo CAdminMessage::ShowNote("Модуль highloadblock не установлен в системе");

    return false;
}