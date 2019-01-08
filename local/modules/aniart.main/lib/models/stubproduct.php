<?php


namespace Aniart\Main\Models;

<<<<<<< HEAD

use Aniart\Main\Interfaces\ProductInterface;

class StubProduct implements ProductInterface
{

	public function getPreviewPicture()
	{
		return '/upload/iblock/0cb/0cbcdd686c12b9217dee4c3367cec4a9.jpg';
	}

	public function getName()
	{
		return 'Товар '.randString(8);
	}

	public function getPrice($format = false)
	{
		$price = rand(1, 1000);
		return $format ? CurrencyFormat($price, 'RUB') : $price;
=======
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
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
	}

	public function getDetailPageUrl()
	{
<<<<<<< HEAD
		return '/catalog/pants/pants-flower-glade/';
=======
        return $this->fields['DETAIL_PAGE_URL'];
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
	}
}