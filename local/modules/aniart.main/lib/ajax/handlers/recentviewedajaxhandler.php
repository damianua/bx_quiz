<?php


namespace Aniart\Main\Ajax\Handlers;


use Aniart\Main\Ajax\AbstractAjaxHandler;
use Bitrix\Catalog;
use Bitrix\Main\Loader;

class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
	public function deleteItem()
	{

	    if(
	        !isset($this->request['productId'])
            ||
            empty($this->request['productId'])
            ||
            !Loader::includeModule('iblock')
            ||
            !Loader::includeModule('sale')
        )
            throw new \Exception('Product ID is empty');

        $skipUserInit = false;
        if (!Catalog\Product\Basket::isNotCrawler())
            $skipUserInit = true;

        $basketUserId = (int)\CSaleBasket::GetBasketUserID($skipUserInit);
        if ($basketUserId <= 0)
        {
            return array();
        }

        $filter = array(
            '=FUSER_ID' => $basketUserId,
            '=SITE_ID' => SITE_ID,
            '=ELEMENT_ID' => $this->request['productId']
        );

        $viewedIterator = Catalog\CatalogViewedProductTable::getList(array(
            'select' => array('ID'),
            'filter' => $filter,
            'order' => array('DATE_VISIT' => 'DESC'),
            'limit' => 1
        ));

        if($arViewed = $viewedIterator->fetch())
        {
            Catalog\CatalogViewedProductTable::delete($arViewed['ID']);
            echo json_encode([]);
        }
	}
}