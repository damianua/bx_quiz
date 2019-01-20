<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Catalog;
use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
{
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}
if (!Loader::includeModule('sale'))
{
    return array();
}

$skipUserInit = false;
if (!Catalog\Product\Basket::isNotCrawler())
    $skipUserInit = true;

$basketUserId = (int)CSaleBasket::GetBasketUserID($skipUserInit);
if ($basketUserId <= 0)
{
    return array();
}

$ids = array();

$filter = array(
    '=FUSER_ID' => $basketUserId,
    '=SITE_ID' => SITE_ID
);

$viewedIterator = Catalog\CatalogViewedProductTable::getList(array(
    'select' => array('ELEMENT_ID'),
    'filter' => $filter,
    'order' => array('DATE_VISIT' => 'DESC'),
    'limit' => 5
));

while ($viewedProduct = $viewedIterator->fetch())
{
    $ids[] = (int)$viewedProduct['ELEMENT_ID'];
}

//STUBS
$productRepository = app()->make('ProductsRepository'); //same as app('ProductsRepository')
$arResult['ELEMENTS'] = $productRepository->getItemsByIds($ids);

$this->IncludeComponentTemplate();