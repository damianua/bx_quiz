<?php

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
$modulePath = dirname(__FILE__);

include $modulePath.'/lib/createhtable.php';

//Подключаем зависимые модули
Bitrix\Main\Loader::includeModule('aniart.main');
Bitrix\Main\Loader::includeModule('highloadblock');
Bitrix\Main\Loader::includeModule('iblock');


app()->bind(array(
    'SeoPage' => '\Aniart\Main\Models\SeoPage',

));
app()->singleton(array(

    'SeoParamsCollector' => '\Aniart\Main\Seo\SeoParamsCollector',
    'SeoPagesRepository' => function(\Aniart\Main\ServiceLocator $locator){
        return new Aniart\Main\Repositories\SeoPagesRepository(\Aniart\Seo\CreateHBTable::getHBID());
    },
    'SmartSeo' => function(\Aniart\Main\ServiceLocator $locator) {
        $smartSeo = \Aniart\Main\SmartSeo\SmartSeo::getInstance();
        try {
            $smartSeo->init(new \Aniart\Main\SmartSeo\Repositories\HLBlockPagesRepository());
            return $smartSeo;
        } catch (Exception $e) {
        }
    },

));
