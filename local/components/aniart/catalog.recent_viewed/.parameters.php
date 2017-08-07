<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"PAGE_ELEMENT_COUNT" => array(
			"PARENT" => "VISUAL",
			"NAME" => "PAGE_ELEMENT_COUNT",
			"TYPE" => "STRING",
			"DEFAULT" => "5",
		), 
                "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);
?>