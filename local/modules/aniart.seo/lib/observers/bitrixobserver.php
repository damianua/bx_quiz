<?php

namespace Aniart\SEO\Observers;

use Aniart\SEO\Seo\SeoParamsCollector;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class BitrixObserver
{
    public function getMeta()
    {
        Loader::includeModule("highloadblock");
        $tableID = \COption::GetOptionString('aniart.seo', "table_id");
        global $APPLICATION;
        $path = $APPLICATION->GetCurDir();
        $hlblock = HL\HighloadBlockTable::getById($tableID)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_PAGE"=>$path)  // Задаем параметры фильтра выборки
        ));
        while($arData = $rsData->Fetch()){
            return $arData;
        }
    }
    public static function onEpilog()
    {
        self::setSeoParams();
    }

    protected static function setSeoParams()
    {
        $seo = new SeoParamsCollector;
        $dataMeta = self::getMeta();
        if($dataMeta["UF_PAGE"] != '') {
            $seo->setPageTitle($dataMeta["UF_PAGE_TITLE"], true);
            $seo->setMetaTitle($dataMeta["UF_META_TITLE"], true);
            $seo->setKeywords($dataMeta["UF_KEYWORDS"], true);
            $seo->setDescription($dataMeta["UF_DESCRIPTION"], true);
            $seo->process();
        }
    }
}