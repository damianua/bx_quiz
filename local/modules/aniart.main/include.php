<?php
/**
 *Точка входа модуля aniart.main
 */

$modulePath = dirname(__FILE__);
include $modulePath.'/vars.php';
include $modulePath.'/utils.php';
include $modulePath.'/misc.php';
include $modulePath.'/events.php';

//Подключаем зависимые модули
Bitrix\Main\Loader::includeModule('highloadblock');
Bitrix\Main\Loader::includeModule('iblock');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::includeModule('sale');

app()->bind([
	'Product' => '\Aniart\Main\Models\CatalogProduct'
]);

app()->bind([
    'Cacher' => '\Aniart\Main\Cacher\BXCacheCell'
]);

app()->singleton([
	'ProductsRepository' => function(\Aniart\Main\ServiceLocator $locator){
		return new \Aniart\Main\Repositories\CatalogProductsRepository(CATALOG_IBLOCK_ID);
	}
]);
//Ajax-обработчики
\Aniart\Main\Ajax\AjaxHandlerFactory::init([
	'basket' => '\Aniart\Main\Ajax\Handlers\BasketAjaxHandler',
	'recent_viewed' => '\Aniart\Main\Ajax\Handlers\RecentViewedAjaxHandler'
]);