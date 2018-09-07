<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Main\Context;
use Bitrix\Sale\Fuser;
$arParams['ID'] = (int) $arParams['ID'];
$arParams['ELEMENT_COUNT'] = (int) $arParams['ELEMENT_COUNT'];
if ($arParams['ELEMENT_COUNT'] <= 0 || $arParams['ELEMENT_COUNT'] > 10) {
    $arParams['ELEMENT_COUNT'] = 10;
}
//STUBS
$productRepository = app()->make('ProductsRepository'); //same as app('ProductsRepository')
$filter = array(
    '=FUSER_ID' => Fuser::getId(),
    '=SITE_ID' => Context::getCurrent()->getSite()
);
if ($arParams['ID'] > 0) {
    $filter['!=ELEMENT_ID'] = (int) $arParams['ID'];
}
$someIds = [];
$viewedIterator = CatalogViewedProductTable::getList(array(
    'select' => array('ELEMENT_ID'),
    'filter' => $filter,
    'order' => array('DATE_VISIT' => 'DESC'),
    'limit' => $arParams['ELEMENT_COUNT']
));
while ($viewedProduct = $viewedIterator->fetch())
{
    $someIds[] = (int)$viewedProduct['ELEMENT_ID'];
}
$arResult['ELEMENTS'] = $productRepository->getItemsByIds($someIds);