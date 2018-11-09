<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Sale\Fuser;

if (!$arParams['ELEMENTS_COUNT'])
    $arParams['ELEMENTS_COUNT'] = 5;
$arViewed = array();

$viewedIterator = \Bitrix\Catalog\CatalogViewedProductTable::getList(array(
    'select' => array('ELEMENT_ID'),
    'filter' => array('=FUSER_ID' => Fuser::getId(), '=SITE_ID' => SITE_ID),
    'order' => array('DATE_VISIT' => 'DESC'),
    'limit' => $arParams["ELEMENTS_COUNT"]
));

while ($arFields = $viewedIterator->fetch()) {
    $arViewed[] = $arFields['ELEMENT_ID'];
}
if ($arViewed) {
    $productRepository = app()->make('ProductsRepository'); //same as app('ProductsRepository')
    $arResult['ELEMENTS'] = $productRepository->getItemsByIds($arViewed);
}
$this->IncludeComponentTemplate();