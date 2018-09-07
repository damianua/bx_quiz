<?php


namespace Aniart\Main\Ajax\Handlers;


use Aniart\Main\Ajax\AbstractAjaxHandler;
use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Main\Context;
use Bitrix\Sale\Fuser;

class RecentViewedAjaxHandler extends AbstractAjaxHandler
{
	public function deleteItem()
	{
        $filter = array(
            '=FUSER_ID' => Fuser::getId(),
            '=SITE_ID' => Context::getCurrent()->getSite(),
            '=ELEMENT_ID' => $this->post['productId'],
        );
        $viewedIterator = CatalogViewedProductTable::getList(array(
            'select' => array('ID'),
            'filter' => $filter,
            'limit' => 1,
            'order' => array('DATE_VISIT' => 'DESC'),
        ));

        $result = [
            'success' => false,
        ];
        if ($viewedProduct = $viewedIterator->fetch())
        {
            $resultDelete = CatalogViewedProductTable::delete($viewedProduct['ID']);
            $result['success'] = $resultDelete->isSuccess();


        }
        header('Content-Type: application/json');
        echo json_encode($result);
        die;
	}
}