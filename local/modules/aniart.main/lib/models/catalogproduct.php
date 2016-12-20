<?php


namespace Aniart\Main\Models;


use Aniart\Main\Interfaces\ProductInterface;

class CatalogProduct extends IblockElementModel  implements ProductInterface
{

    public function getPreviewPicture()
    {
        return $this->PREVIEW_PICTURE;
    }

    public function getPrice($format = false)
    {
        
    }

    public function getDetailPageUrl()
    {
        return $this->DETAIL_PAGE_URL;
    }
}