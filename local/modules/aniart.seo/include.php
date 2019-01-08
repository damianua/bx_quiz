<?php
/**
 *Точка входа модуля aniart.main
 */

$modulePath = dirname(__FILE__);
include $modulePath.'/vars.php';
include $modulePath.'/events.php';
include $modulePath.'/misc.php';

//Подключаем зависимые модули
Bitrix\Main\Loader::includeModule('highloadblock');

app()->bind([
    'SeoPage' => '\Aniart\Seo\Models\SeoPage',
]);

app()->singleton([
    'SeoParamsCollector' => '\Aniart\Seo\Seo\SeoParamsCollector',
    'SeoPagesRepository' => function(\Aniart\Main\ServiceLocator $locator){
        return new Aniart\Seo\Repositories\SeoPagesRepository(HL_SEO_PAGES_ID);
    },
    'SmartSeo' => function(\Aniart\Main\ServiceLocator $locator) {
        $smartSeo = \Aniart\Seo\SmartSeo\SmartSeo::getInstance();
        try {
            $smartSeo->init(new \Aniart\Seo\SmartSeo\Repositories\HLBlockPagesRepository());
            return $smartSeo;
        } catch (Exception $e) {
        }
    },

]);