<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
//STUBS
global $USER;
$user_id = $USER->GetID();


$arParams['COUNT_ELEMENT'] = $arParams['COUNT_ELEMENT'] ? $arParams['COUNT_ELEMENT'] : 5;

$arParams["SORT_BY1"] = $arParams["SORT_BY1"] ? $arParams["SORT_BY1"] : 'ID';
$arParams["SORT_BY2"] = $arParams["SORT_BY2"] ? $arParams["SORT_BY2"] : 'SORT';

$arParams["SORT_ORDER1"] = $arParams["SORT_ORDER1"] ? $arParams["SORT_ORDER1"] : 'ASC';
$arParams["SORT_ORDER2"] = $arParams["SORT_ORDER2"] ? $arParams["SORT_ORDER2"] : 'ASC';

if ($this->StartResultCache(false, 'viewed_products' . $user_id)) {
	
	$productRepository = app('ProductsRepository');
	
	$arSort = array(
		$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
		$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"]
	);
	
	$productRepository->setFilter($arParams['FILTER_NAME']);
	
	$arNavParams = array(
		"nPageSize" => (!empty($arParams["COUNT_ELEMENT"])) ? $arParams["COUNT_ELEMENT"] : 5,
		"bDescPageNumbering" => 'N',
		"bShowAll" => 'N',
	);
	
	$productRepository->setNav($arNavParams);
	$productRepository->setOrder($arSort);
	
	$arResult['ELEMENTS'] = $productRepository->getViewedProductIds(null, $arParams["COUNT_ELEMENT"]);
	
	$this->SetResultCacheKeys(array(
		"ELEMENTS",
	));
	$this->IncludeComponentTemplate();
}