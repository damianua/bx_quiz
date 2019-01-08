<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//STUBS
$productRepository = app()->make('ProductsRepository'); //same as app('ProductsRepository')
<<<<<<< HEAD
$arResult['ELEMENTS'] = $productRepository->getItemsByIds($someIds = []);
=======
$arResult['ELEMENTS'] = $productRepository->getItemsByIds($arResult['ELEMENT_VIEWED']);
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
