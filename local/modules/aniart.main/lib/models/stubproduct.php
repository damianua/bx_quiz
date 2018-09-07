<?php


namespace Aniart\Main\Models;


use Aniart\Main\Interfaces\ProductInterface;

class StubProduct extends AbstractModel implements ProductInterface
{
    public function getId()
    {
        return $this->fields['ID'];
    }

    public function getPreviewPicture()
    {
        return $this->fields['PICTURE_SRC'];
    }

    public function getName()
    {
        return $this->fields['NAME'];
    }

    public function getPrice($format = false)
    {
        return $format ? CurrencyFormat($this->fields['PRICE'], $this->fields['CURRENCY']) : $this->fields['PRICE'];
    }

    public function getDetailPageUrl()
    {
        return $this->fields['DETAIL_PAGE_URL'];
    }
}