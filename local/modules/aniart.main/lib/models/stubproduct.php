<?php


namespace Aniart\Main\Models;

use Aniart\Main\Models\IblockElementModel;
use Aniart\Main\Interfaces\ProductInterface;

class StubProduct extends IblockElementModel implements ProductInterface
{
    protected $fields = [];
    public function __construct($data)
    {
        $this->fields  = $data;
    }

    public function getPrice($format = false)
    {
        $rsGroup = \Bitrix\Catalog\GroupTable::getList();
        while($arGroup=$rsGroup->fetch())
        {
            if ($arGroup['BASE'] == "Y") {
                $baseGroup =array('ID' => $arGroup['ID'], 'XML_ID' => $arGroup['XML_ID']);
            }
        }

        $rsPrice = \Bitrix\Catalog\PriceTable::getList(array(
            'filter'=>array('CATALOG_GROUP.XML_ID'=>$baseGroup['XML_ID'],'PRODUCT_ID'=>$this->fields['ID'])
        ));

        while($price=$rsPrice->fetch())
        {
            $priceFormat = \CCurrencyLang::CurrencyFormat($price['PRICE'],$price['CURRENCY'],'true');
        }

        return $priceFormat;
    }

    public function getDetailPageUrl()
    {
        return $this->fields['DETAIL_PAGE_URL'];
    }
}