<?php
use Bitrix\Highloadblock\HighloadBlockTable;

define('SEO_HL_NAME', 'AniArtSeo');

$rsData = HighloadBlockTable::getList(array('filter' => array('NAME' => SEO_HL_NAME)));
if ($arData = $rsData->fetch()) {
    define('HL_SEO_PAGES_ID', $arData["ID"]);
}
