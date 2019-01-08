<?php


namespace Aniart\Main\Ajax\Handlers;


use Aniart\Main\Ajax\AbstractAjaxHandler;
<<<<<<< HEAD
=======
use \Bitrix\Main;
use \Bitrix\Catalog;
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master

class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
	public function deleteItem()
	{
<<<<<<< HEAD
		die('1');
=======
        if (!Main\Loader::includeModule('sale'))
        {
            return array();
        }

        $UserId = (int)\CSaleBasket::GetBasketUserID(false);

        $filter = array(
            '=FUSER_ID'     => $UserId,
            '=SITE_ID'      => \Bitrix\Main\Context::getCurrent()->getSite(),
            'ELEMENT_ID'    => $this -> post['productId']
        );

        $resViewed = Catalog\CatalogViewedProductTable::getList(array(
            'select' => array('ID'),
            'filter' => $filter,
            'order' => array('DATE_VISIT' => 'DESC'),
            'limit' => 1
        ));
        while ($elementViewed = $resViewed->fetch())
        {
            $Id = $elementViewed['ID'];
        }

        if (intval($Id)>0){
            $res = Catalog\CatalogViewedProductTable::delete($Id);
            if($res->isSuccess()) {
                $this->setOK('Ok');
            }
        }
>>>>>>> 8b2bd5b... TASC - modul SEO, component recent_viewed. On master
	}
}