<?php


namespace Aniart\Main\Models;


use Aniart\Main\Interfaces\ProductInterface;

class StubProduct extends AbstractHLElementModel implements ProductInterface
{

    public function getPreviewPicture()
    {
        return $this->__get("PREVIEW_PICTURE") ? \CFile::GetPath($this->__get("PREVIEW_PICTURE")) : \CFile::GetPath($this->__get("PROPERTY_MORE_PHOTO_VALUE"));
    }

    public function getName()
    {
        return $this->__get("NAME");
    }

    public function getPrice($format = false)
    {
        $price = $this->__get("PRICE");
        return $format ? CurrencyFormat($price, $this->__get("CURRENCY")) : $price;
    }

    public function getDetailPageUrl()
    {
        return $this->__get("DETAIL_PAGE_URL");
    }
}