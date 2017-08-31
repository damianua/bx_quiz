<?php
use Bitrix\Main;
use Bitrix\Iblock;
use Bitrix\Catalog;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application; 

class CCatalogRecentViewed extends CBitrixComponent
{
    public function __construct($component = null) {
        parent::__construct($component);
    }
    private function checkParams(){
        if(!IntVal($this->arParams['PAGE_ELEMENT_COUNT']))
        {
            $this->arParams['PAGE_ELEMENT_COUNT'] = 5;
        }
    }
    
    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');
        $basketUserId = (int)CSaleBasket::GetBasketUserID(false);
        
        
//        var_dump($basketUserId);
        
        $filter = array('=FUSER_ID' => $basketUserId, '=SITE_ID' => SITE_ID);
        $viewedIterator = Catalog\CatalogViewedProductTable::getList(array(
                'select' => array('PRODUCT_ID', 'ELEMENT_ID', "ID"),
                'filter' => $filter,
                'order' => array('DATE_VISIT' => 'DESC'),
                'limit' => $this->arParams['PAGE_ELEMENT_COUNT']
        ));
        $arElementIds = [];
        while ( $viewedProduct = $viewedIterator->fetch() )
        {
            $arElementIds[] = $viewedProduct["ELEMENT_ID"];
            $this->arResult['ITEMS_IDS'][$viewedProduct["ID"]] 
                    = $viewedProduct["ELEMENT_ID"];
        }
        
        $filter = [ "ID" => $this->arResult['ITEMS_IDS'] ];
        $select = ["IBLOCK_ID", "ID", "DETAIL_PAGE_URL", "NAME", "DETAIL_PICTURE"];
        $dbElements = CIBlockElement::GetList ( 
                array(), 
                $filter, 
                false, 
                false, 
                $select
        );
        while($arElement = $dbElements->GetNext()){
            $arElement["DETAIL_PICTURE"] = [
                "SRC"=>CFile::GetPath($arElement["DETAIL_PICTURE"])
            ];
            $this->arResult["ITEMS_UNSORTED"][$arElement["ID"]] = $arElement;
        }
        $this->arResult["ITEMS"] = [];
        /*Sorting*/
        foreach ( $this->arResult['ITEMS_IDS'] as $viewedIndexId=>$item_id )
        {
            $this->arResult["ITEMS"][] = $this->arResult["ITEMS_UNSORTED"][$item_id]+["data-id"=>$viewedIndexId];
        }
        unset($this->arResult['ITEMS_IDS']);
        unset($this->arResult["ITEMS_UNSORTED"]);
        $this->includeComponentTemplate();
    }
};