<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Просмотренные товары',
	"DESCRIPTION" => 'Просмотренные товары компонент aniart',
	"ICON" => "/images/cat_list.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => '',
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "catalog_cmpx",
			),
		),
	),
);

?>