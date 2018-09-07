<?php
/**
 *Точка входа модуля aniart.seo
 */

if (!Bitrix\Main\Loader::includeModule('aniart.seo'))
    return 0;

$modulePath = dirname(__FILE__);
require $modulePath.'/vars.php';
require $modulePath.'/utils.php';
require $modulePath.'/misc.php';
include $modulePath.'/events.php';

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
	}
]);
//Ajax-обработчики
\Aniart\Main\Ajax\AjaxHandlerFactory::init([
	'basket' => '\Aniart\Main\Ajax\Handlers\BasketAjaxHandler',
	'recent_viewed' => '\Aniart\Main\Ajax\Handlers\RecentViewedAjaxHandler'
]);
