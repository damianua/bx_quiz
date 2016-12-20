<?php
use Bitrix\Catalog\CatalogViewedProductTable;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Loader::includeModule('catalog');

$arParams["PRICE_ID"] = 1;

$arParams['ELEMENTS_ID'] = [];

$saleUserID = CSaleBasket::GetBasketUserID();
$filter = array('=FUSER_ID' => $saleUserID, '=SITE_ID' => SITE_ID);

$dbl = CatalogViewedProductTable::getList(array(
    'select' => array('PRODUCT_ID', 'ELEMENT_ID'),
    'filter' => $filter,
    'order' => array('DATE_VISIT' => 'DESC')
));

while ($res = $dbl->Fetch()) {
    $arParams['ELEMENTS_ID'][] = $res['ELEMENT_ID'];
}

$cacheCell = app('Cacher', [$arParams]);

if (!$arResult = $cacheCell->load()) {
    $productRepository = app('ProductsRepository');
    if ($arParams['ELEMENTS_ID']) {
        $arResult['ELEMENTS'] = $productRepository->getItems([
            'filter' => ['ID' => $arParams['ELEMENTS_ID']],
            'select' => ['CATALOG_GROUP_' . $arParams["PRICE_ID"]]
        ]);
    }
        $cacheCell->save($arResult);

}

$this->IncludeComponentTemplate();