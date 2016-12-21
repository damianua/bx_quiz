<?php


namespace Aniart\Main\Models;


use Aniart\Main\Interfaces\ProductInterface;
use CFile;

class CatalogProduct extends IblockElementModel  implements ProductInterface
{

    public function getPreviewPicture()
    {
        $file = CFile::GetFileArray($this->PREVIEW_PICTURE);
        return $file['SRC'];
    }

    public function getPrice($format = false)
    {
        $fields = $this->getFields();
        $price = $fields['CATALOG_PRICE_' . $fields['PRICE_ID']];
        return $format ? CurrencyFormat($price, $fields['CATALOG_CURRENCY_' . $fields['PRICE_ID']]) : $price;
    }

    public function getDetailPageUrl()
    {
        return $this->DETAIL_PAGE_URL;
    }
}