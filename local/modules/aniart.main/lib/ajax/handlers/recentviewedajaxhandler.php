<?php


namespace Aniart\Main\Ajax\Handlers;

use Aniart\Main\Ajax\AbstractAjaxHandler;
use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Sale\Fuser;

class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
    public function deleteItem()
    {
        $viewedIterator = CatalogViewedProductTable::getList(array(
            'select' => array('ID'),
            'filter' => array('=FUSER_ID' => Fuser::getId(), '=SITE_ID' => SITE_ID, '=ELEMENT_ID' => $this->post['productId'])
        ));

        if ($arFields = $viewedIterator->fetch()) {
            CatalogViewedProductTable::delete($arFields['ID']);
            $this->setOK();
        }
    }
}